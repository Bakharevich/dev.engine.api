<?php

namespace App\Console\Commands;

use App\CategoryGroup;
use Illuminate\Console\Command;
use App\Site;
use App\City;
use App\JobScraper;
use App\Category;
use MongoDB\Driver\Exception\AuthenticationException;

class PrepareYelp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrapers:prepare-yelp {domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating new categories, jobs and other stuff for Tam';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $domain = $this->argument('domain');

        // check if such domain exists
        $site = Site::where('domain', $domain)->first();

        if (!$site) {
            $this->warn("{$domain} doesn't exist in DB");
            exit();
        }
        $siteId = $site->id;
        $countryId = $site->country_id;

        // list of websites
        $sites = [
            159 => 'https://en.yelp.my'
        ];

        // set domain of scraped site
        $scrapedUrl = $sites[$site->country_id];
        $this->line("Domain for scraping: {$scrapedUrl}");

        // all cities
        $cities = [];

        $pageLocations = $this->request("{$scrapedUrl}/locations");
        preg_match_all('|<ul class="cities">(.+?)</ul>|s', $pageLocations, $locations);
        if (!empty($locations[1][0])) {
            preg_match_all('|<a href="(.+?)">(.+?)</a>|', $locations[1][0], $citiesArr);

            if (!empty($citiesArr[1])) {
                foreach ($citiesArr[1] as $index => $value) {
                    $cityName = $citiesArr[2][$index];
                    $url = $scrapedUrl . $citiesArr[1][$index];

                    // check if city exists
                    $cityData = \App\City::where('country_id', $site->country_id)->where('name', $cityName)->first();

                    // add city if it exists in DB
                    if ($cityData) {
                        $cities[] = [
                            'name'        => $cityName,
                            'url'         => $url,
                            'city_id'     => $cityData->id,
                            'city_domain' => $cityData->domain
                        ];
                    }
                }
            }
        }
        else {
            $this->warn('Could not find locations');
            exit();
        }

        //print_r($cities); exit();
        $cities = [['name' => 'Kuala Lumpur', 'url' => 'https://en.yelp.my/kl', 'city_id' => 1735161, 'city_domain' => 'kuala-lumpur']];

        foreach ($cities as $row) {
            $link = $scrapedUrl . "/search?find_loc=" . urlencode($row['name']);

            $mainPage = $this->request($link);

            // get link to groups
            preg_match_all('|<a class=\'category-browse-anchor js-search-header-link\' href="(.+?)">(.+?)</a>|', $mainPage, $groupsArr);

            if (empty($groupsArr[1])) {
                $this->warn("Could not get the list of groups");
                continue;
            }

            // add groups to array
            $groups = [];
            foreach ($groupsArr[1] as $index => $group) {
                $groups[$groupsArr[2][$index]] = $scrapedUrl . $groupsArr[1][$index];
            }

            // iterate through groups
            foreach ($groups as $name => $link) {
                $names = $this->getGroupNames($name);

                // check if group exists
                $categoryGroup = \App\CategoryGroup::where('site_id', $site->id)->where('city_id', $row['city_id'])->where('name1', $names['name1'])->first();

                // if not, add it
                if (!$categoryGroup) {
                    $categoryGroup = \App\CategoryGroup::create([
                        'site_id' => $site->id,
                        'city_id' => $row['city_id'],
                        'name1' => $names['name1'],
                        'name2' => $names['name2']
                    ]);
                    $this->info("Group {$categoryGroup->name1} added");
                } else {
                    $this->line("Group {$categoryGroup->name1} exists");
                }

                // visit group page
                $groupPage = $this->request($link);

                // get all categories of group
                preg_match_all("|<a class='category-browse-anchor js-search-header-link' href=\"(.+?)\">(.+?)</a>|", $groupPage, $categoriesArr);
                if (empty($categoriesArr[1])) {
                    $this->warn("Could not able to find categories in {$name}");
                }

                // add categories to array
                $categories = [];
                foreach ($categoriesArr[1] as $index => $value) {
                    $categories[$categoriesArr[2][$index]] = $scrapedUrl . $categoriesArr[1][$index];
                }

                // iterate through categories
                foreach ($categories as $name => $url) {
                    // check if category exists
                    $existingCategory = Category::where('site_id', $site->id)
                        ->where('name', $name)
                        ->where('city_id', $row['city_id'])
                        ->first();

                    if ($existingCategory) {
                        $this->line("Category {$name} exists");
                        continue;
                    }

                    // set vars
                    $domain = str_slug($name);

                    // add to categories, check before that for that name and city_id
                    $newCategory = Category::create([
                        'site_id' => $site->id,
                        'city_id' => $row['city_id'],
                        'category_group_id' => $categoryGroup->id,
                        'name' => $name,
                        'name_single' => '',
                        'meta_title' => $name . ' in ' . $row['name'],
                        'description_top' => '',
                        'description_bottom' => '',
                        'domain' => $domain ,
                        'icon' => '',
                        'url' => "http://{$site->domain}/{$row['city_domain']}/{$domain}"
                    ]);

                    // add to jobs_scrapers
                    $scraper = JobScraper::create([
                        'site_id' => $site->id,
                        'scraper' => 'Yelp',
                        'category_id' => $newCategory->id,
                        'city_id' => $row['city_id'],
                        'url' => htmlspecialchars_decode($url),
                        'pages' => 13
                    ]);

                    $this->line("Category {$name} added");
                }
            }

            $this->info("Preparation for {$row['name']} is finished");
        }


        exit();

        // update categories of city
        $this->info('Update categories icons');
        $categories = Category::where('site_id', $siteId)->where('city_id', $city->id)->get();

        foreach ($categories as $category) {
            if (empty($category->icon)) {
                $category->icon = $this->getIconByName($category->name);
                $category->save();
                $this->line($category->name . ' icon updated');
            }
        }


        //$bar->finish();
        $this->line('');
    }

    protected function request($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, "postvar1=value1&postvar2=value2&postvar3=value3");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close ($ch);

        return $data;
    }

    protected function getGroups()
    {
        $res = [];

        $arr = $this->getGroupedCategories();

        foreach ($arr as $index => $value) {
            if (!$this->checkIfGroupIgnored($index)) {
                $res[] = $this->getGroupNames($index);
            }
        }

        return $res;
    }

    protected function getGroupNames($arr)
    {
        if (preg_match("|&|", $arr)) {
            $exploded = explode("&", $arr);

            $res = [];

            if (count($exploded) > 1) {
                $name1 = trim(str_replace("&", "", $exploded[0]));
                $name2 = "& " . trim($exploded[1]);

                $res = [
                    'name1' => $name1,
                    'name2' => $name2
                ];
            }
            else {
                $res = [
                    'name1' => $exploded[0],
                    'name2' => ''
                ];
            }
        }
        else {
            $exploded = explode(" ", $arr);

            $res = [];

            if (count($exploded) > 1) {
                $name1 = $exploded[0];

                array_shift($exploded);
                $name2 = '';
                foreach ($exploded as $str) {
                    $name2 .= $str . ' ';
                }

                $res = [
                    'name1' => trim($name1),
                    'name2' => trim($name2)
                ];
            }
            else {
                $res = [
                    'name1' => trim($exploded[0]),
                    'name2' => ''
                ];
            }
        }

        return $res;
    }

    protected function getGroupId($siteId, $cityId, $name)
    {
        // find group name by category name
        $categoriesInGroups = $this->getGroupedCategories();

        $groupName = "";
        $groupId = 0;

        foreach ($categoriesInGroups as $group => $arr) {
            foreach ($arr as $category) {
                if ($category == $name) {
                    $groupName = $group;
                    break;
                }
            }
        }

        if (!empty($groupName)) {
            // find id of group
            //$exploded = explode(" ", $groupName);
            $names = $this->getGroupNames($groupName);

            if (count($names) > 1) {
                $result = CategoryGroup::where('site_id', $siteId)->
                    where('city_id', $cityId)->
                    where('name1', $names['name1'])->
                    where('name2', $names['name2'])->
                    first();

                if ($result) $groupId = $result->id;
            }
            else {
                $result = CategoryGroup::where('site_id', $siteId)->
                    where('city_id', $cityId)->
                    where('name1', $names['name1'])->
                    first();

                if ($result) $groupId = $result->id;
            }
        }

        return $groupId;
    }

    protected function getGroupedCategories()
    {
        if (empty($this->groups)) {
            $lines = file_get_contents(storage_path('scrapers/tam.txt'));

            $arr = explode("\n", $lines);

            $res = [];

            foreach ($arr as $category) {
                $catInfo = explode("|", $category);

                $res[$catInfo[0]][] = $catInfo[1];
            }

            $this->groups = $res;
        }
        /*
        $categoriesInGroups['Public Services'] = [
            'Restaurant', 'Hotel', 'Tourism', 'Travel', 'Dance', 'Film', 'Sports', 'Beauty', 'Beauty Parlour',
            'College', 'School', 'Doctor', 'Health', 'Homeopathy', 'Hospital', 'Medicine','Book'
        ];
        $categoriesInGroups['Home Services'] = [
            'Air Conditioner', 'Bed', 'Home', 'Kitchen', 'Real Estate', 'Tiles'
        ];*/

        return $this->groups;
    }

    protected function getCategoryInfo($name)
    {
        if (empty($this->arr)) {
            $lines = file_get_contents(storage_path('scrapers/tam.txt'));

            $arr = explode("\n", $lines);

            $res = [];

            foreach ($arr as $category) {
                $catInfo = explode("|", $category);

                $res[$catInfo[1]] = [
                    'name' => $catInfo[1],
                    'domain' => $catInfo[2]
                ];
            }

            $this->arr = $res;
        }

        if (!empty($this->arr[$name])) return $this->arr[$name];
        else return [];
    }

    protected function checkIfGroupIgnored($name)
    {
        $arr = [
            'Бытовые услуги',
            'Государство и общество',
            'Домашние животные',
            'Связь'
        ];

        if (in_array($name, $arr)) return true;
        else return false;
    }

    protected function getIconByName($category)
    {
        $cats = file_get_contents(storage_path('scrapers/tam.txt'));
        $icons = file_get_contents(storage_path('scrapers/tam_icons.txt'));

        $catsArr = explode("\n", $cats);
        $iconsArr = explode("\n", $icons);

        $res = [];

        foreach ($catsArr as $index => $cat) {
            $name = explode("|", $cat);

            // check for fa-
            if (!preg_match("|^fa-|", $iconsArr[$index]) && !preg_match("|^glyphicon|", $iconsArr[$index])) {
                $iconsArr[$index] = "fa-" . $iconsArr[$index];
            }

            // add fa prefix
            if (!preg_match("|^glyphicon|", $iconsArr[$index])) {
                $iconsArr[$index] = "fa " . $iconsArr[$index];
            }

            $res[$name[1]] = $iconsArr[$index];
        }

        if (!empty($res[$category])) return $res[$category];
        else return '';
    }


}
