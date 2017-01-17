<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Site;
use App\City;
use App\JobScraper;
use App\Category;

class PrepareSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrapers:prepare-site {target} {domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating new categories, jobs and other stuff';

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

        $target = $this->argument('target');
        $domain = $this->argument('domain');
        $citiesLimit = $this->ask('How many cities you want to add?', 0);

        // check if domain has http
        if (preg_match('#http:\/\/|https:\/\/#', $target)) $target = "http://";
        //$url = "http://www.infoisinfo.co.in";
        //$domain = "24india.in";

        $countryId = 105;
        $locale = "en";

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
        $page = $this->request($target);

        $reg = "|inner clearfix border-pc.*?>(.+?)</div>|is";
        preg_match_all($reg, $page, $citiesPre);

        $targetCities = [];

        if (!empty($citiesPre[1][0])) {
            $reg = "|<a.*?href=\"(.+?)\".*?>(.+?)</a>|is";
            preg_match_all($reg, $citiesPre[1][0], $cities);

            foreach ($cities[1] as $index => $city) {
                if (preg_match("#-state|-district#", $cities[1][$index])) continue;

                if ($cities[2][$index] == "Ahmadabad") $cities[2][$index] = "Ahmedabad";

                $targetCities[] = [
                    'name' => $cities[2][$index],
                    'url' => $cities[1][$index]
                ];
            }
        }

        //print_r($targetCities); exit();

        // create progress bar with amount of cities
        $progressBarInit = !empty($citiesLimit) ? $citiesLimit : count($targetCities);
        $bar = $this->output->createProgressBar($progressBarInit);

        // show all cities
        //echo "<pre>"; print_r($targetCities);

        // compare with our cities
        $citiesCounter = 0;
        foreach ($targetCities as $city) {
            $cityData = City::where('name', $city['name'])->first();

            if ($cityData) {
                $this->line("{$city['name']} exists in DB. ID: {$cityData->id}");
                //continue;

                // get city page
                $this->info("Getting city page {$city['url']}");
                $cityPage = file_get_contents($city['url']);

                $reg = "|<div id='inner5'>(.*?)</div>|is";
                preg_match_all($reg, $cityPage, $categoriesPre);

                if (!empty($categoriesPre[1][0])) {
                    $reg = "|<a.*?href=\"(.+?)\".*?>(.+?)</a>|is";
                    preg_match_all($reg, $categoriesPre[1][0], $categories);

                    //echo "<pre>"; print_r($categories);

                    foreach ($categories[1] as $index => $category) {
                        $name = $categories[2][$index];
                        $newDomain = str_slug($name);

                        // check if category with such name for that region exists
                        $existingCategory = Category::where('site_id', $siteId)
                            ->where('name', $name)
                            ->where('city_id', $cityData->id)
                            ->first();

                        if ($existingCategory) {
                            $this->info("Category {$name} exists");
                            continue;
                        }

                        // add to categories, check before that for that name and city_id
                        $newCategory = Category::create([
                            'site_id' => $siteId,
                            'city_id' => $cityData->id,
                            'category_group_id' => 0,
                            'name' => $name,
                            'name_single' => $name,
                            'meta_title' => $name . ' in ' . $cityData->name,
                            'description_top' => '',
                            'description_bottom' => '',
                            'domain' => $newDomain,
                            'icon' => '',
                            'url' => "http://{$site->domain}/{$cityData->domain}/{$newDomain}"
                        ]);

                        // add to jobs_scrapers
                        $scraper = JobScraper::create([
                            'site_id' => $siteId,
                            'scraper' => 'Infoisinfo',
                            'category_id' => $newCategory->id,
                            'city_id' => $cityData->id,
                            'url' => $categories[1][$index],
                            'pages' => 15
                        ]);

                        //exit();
                    }
                }
                else {
                    $this->error("No categories in {$city['name']}");
                }

                //exit();
            }
            else {
                $this->error("{$city['name']} not exists");
            }

            //exit();
            $citiesCounter++;
            $bar->advance();
            $this->line('');

            if (!empty($citiesLimit) && $citiesCounter >= $citiesLimit) break;
        }

        $bar->finish();
        $this->line('');
    }

    protected function request($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, "postvar1=value1&postvar2=value2&postvar3=value3");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close ($ch);

        return $data;
    }
}
