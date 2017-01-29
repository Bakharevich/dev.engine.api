<?php

namespace App\Console\Commands;

use App\CategoryGroup;
use Illuminate\Console\Command;
use App\Site;
use App\City;
use App\JobScraper;
use App\Category;
use MongoDB\Driver\Exception\AuthenticationException;

class PrepareTam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrapers:prepare-tam {domain}';

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
        $this->info('Preparation started');

        $domain = $this->argument('domain');

        //$citiesLimit = $this->ask('How many cities you want to add?', 0);

        $countryId = 36;
        $locale = "ru";

        // check if such domain exists
        $site = Site::where('domain', $domain)->first();

        if (!$site) {
            $site = new Site;
            $site->name = $domain;
            $site->country_id = $countryId;
            $site->city_id = 0;
            $site->locale = $locale;
            $site->domain = $domain;
            $site->html_code = '';
            $site->save();
        }
        $siteId = $site->id;

        // all cities
        //$citiesArr = $this->request('https://tam.by/?call=catalogCities');
        //$arr = json_decode($citiesArr);

        $cities[] = [
            'name' => 'Минск',
            'url' => 'https://tam.by'
        ];

        foreach ($cities as $c) {
            // get city info
            $city = City::where('country_id', $countryId)->where('name', $c['name'])->first();
            if (!$city) {
                $this->error('Unknown city');
            }

            $this->line("City {$city->name} found. ID: {$city->id}");

            // get main city page
            $page = $this->request($c['url']);

            // first get UL
            preg_match_all("|<ul class=\"category-list\">(.+?)</ul>|", $page, $ulResult);

            $reg = "|<a href=\"(.+?)\" onclick=\"window.location='.*?'; return false;\"><span>(.+?)</span></a>|is";
            preg_match_all($reg, $ulResult[1][0], $citiesPre);
            //print_r($citiesPre); exit();

            // checking category groups for after city
            if (!empty($citiesPre[1])) {
                foreach ($citiesPre[1] as $index => $group) {
                    $categoryGroup = $citiesPre[2][$index];

                    // check if we must ignore that group
                    if ($this->checkIfGroupIgnored($categoryGroup)) {
                        $this->error("Group {$categoryGroup} ignored, as it's in black list");
                        continue;
                    }

                    $groupUrl = $citiesPre[1][$index];
                    //$this->line("Getting {$groupUrl}");
                    $categoryPage = $this->request($groupUrl);

                    $reg = "|<dd>.*?<a.*?href=\"(.+?)\".*?>(.+?)</a>|";
                    preg_match_all($reg, $categoryPage, $categories);

                    foreach ($categories[2] as $i => $category) {
                        // get category info from arr
                        $categoryInfo = $this->getCategoryInfo($categories[2][$i]);

                        if (!empty($categoryInfo['name'])) $name = $categoryInfo['name'];
                        else $name = $categories[2][$i];

                        if (!empty($categoryInfo['domain'])) $newDomain = $categoryInfo['domain'];
                        else $newDomain = str_slug($name);

                        $categoryOriginalUrl = !empty($categories[1][$i]) ? $categories[1][$i] : "";
                        //$this->line($categoryGroup . "|" . $name . "|" . $newDomain); continue;

                        // check if category with such name for that region exists
                        $existingCategory = Category::where('site_id', $siteId)
                            ->where('name', $name)
                            ->where('city_id', $city->id)
                            ->first();

                        if ($existingCategory) {
                            $this->line("Category {$name} exists");
                            continue;
                        }

                        // add to categories, check before that for that name and city_id
                        $newCategory = Category::create([
                            'site_id' => $siteId,
                            'city_id' => $city->id,
                            'category_group_id' => 0,
                            'name' => $name,
                            'name_single' => $name,
                            'meta_title' => $name . ' в ' . $city->name_where,
                            'description_top' => '',
                            'description_bottom' => '',
                            'domain' => $newDomain,
                            'icon' => '',
                            'url' => "http://{$site->domain}/{$city->domain}/{$newDomain}"
                        ]);

                        // add to jobs_scrapers
                        $scraper = JobScraper::create([
                            'site_id' => $siteId,
                            'scraper' => 'Tam',
                            'category_id' => $newCategory->id,
                            'city_id' => $city->id,
                            'url' => $categoryOriginalUrl,
                            'pages' => 2
                        ]);

                        $this->line("Category {$name} added");

                        //exit();
                        //break;
                    }

                    //break;
                }
            }

            // adding groups for cities
            $groups = $this->getGroups();

            if (!empty($groups)) {
                foreach ($groups as $group) {
                    $name1 = !empty($group['name1']) ? $group['name1'] : '';
                    $name2 = !empty($group['name2']) ? $group['name2'] : '';

                    // check if such group exists
                    $check = CategoryGroup::where('site_id', $siteId)->
                        where('city_id', $city->id)->
                        where('name1', $name1)->
                        where('name2', $name2)->
                        first();

                    if (!$check) {
                        CategoryGroup::create([
                            'site_id' => $siteId,
                            'city_id' => $city->id,
                            'name1' => $name1,
                            'name2' => $name2
                        ]);

                        $this->info("Group {$name1} added");
                    }
                    else {
                        $this->line("Group {$name1} exist. Skipped.");
                    }
                }
            }

            // attaching categories to groups
            $this->info('Add categories in categories_group');
            $categories = Category::where('site_id', $siteId)->where('city_id', $city->id)->get();

            foreach ($categories as $category) {
                $groupId = $this->getGroupId($siteId, $city->id, $category->name);

                if (!empty($groupId)) {
                    if (empty($category->category_group_id)) {
                        $category->category_group_id = $groupId;
                        $category->save();

                        $this->info("Category {$category->name} added to group {$groupId}");
                    }
                }
                else {
                    $this->error("Couldn't find group for category {$category->name}");
                }
            }

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
        $exploded = explode(" ", $arr);

        $res = [];

        if (count($exploded) > 1) {
            $name1 = str_replace(",", "", $exploded[0]);

            if (!empty($exploded[2])) $name2 = $exploded[1] . " " . $exploded[2];
            else $name2 = $exploded[1];

            $res = [
                'name1' => $name1,
                'name2' => $name2
            ];
        }
        else {
            $res = [
                'name1' => $exploded[0]
            ];
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
