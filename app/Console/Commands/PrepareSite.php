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
        //print_r($citiesPre); exit();

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

                // update categories of city
                $this->info('Update categories icons');
                $categories = Category::where('site_id', $siteId)->where('city_id', $cityData->id)->get();

                foreach ($categories as $category) {
                    if (empty($category->icon)) {
                        $category->icon = $this->getIconByName($category->name);
                        $category->save();
                        $this->line($category->name . ' updated');
                    }
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close ($ch);

        return $data;
    }

    protected function getIconByName($category)
    {
        $arr = [
            'Environment' => 'fa fa-globe',
            'Computer' => 'fa fa-laptop',
            'Jewellery' => 'fa fa-diamond',
            'Real Estate' => 'fa fa-building ',
            'Software' => 'fa fa-desktop',
            'Home' => 'fa fa-home',
            'Music' => 'fa fa-music',
            'U.V. Systems' => 'fa fa-tint',
            'Car' => 'fa fa-car',
            'Customized .Net Application' => 'fa fa-user',
            'Food' => 'fa fa-cutlery',
            'Restaurants - 24 Hrs. Coffee Shops' => 'fa fa-coffee',
            'School' => 'fa fa-graduation-cap',
            'Gems' => 'fa fa-diamond',
            'Health' => 'fa fa-heartbeat',
            'Electricity' => 'fa fa-bolt',
            'Mobile' => 'fa fa-mobile',
            'Bank' => 'fa fa-usd',
            'Furniture' => 'fa fa-bed',
            'Design' => 'fa fa-paint-brush',
            'Doctor' => 'fa fa-user-md',
            'Power' => 'fa fa-power-off',
            'Saree' => 'fa fa-female',
            'Transport' => 'fa fa-bus',
            'Book' => 'fa fa-book',
            'Finance' => 'fa fa-money',
            'Shop' => 'fa fa-shopping-cart ',
            'Hospital' => 'fa fa-hospital-o ',
            'Beauty' => 'fa fa-female',
            'Video' => 'fa fa-video-camera',
            'Air Conditioner' => 'fa fa-snowflake-o',
            'Travel' => 'fa fa-plane',
            'Banking' => 'fa fa-money',
            'Mobile Phone' => 'fa fa-mobile',
            'Gift' => 'fa fa-gift',
            'Marketing' => 'fa fa-comments',
            'Computer Software' => 'fa fa-laptop',
            'Diamond' => 'fa fa-diamond',
            'Hotel' => 'fa fa-h-square ',
            'www' => 'fa fa-globe',
            'Car Accessories' => 'fa fa-car',
            'Box' => 'fa fa-archive',
            'Jewelry' => 'fa fa-diamond',
            'Medicine' => 'fa fa-medkit',
            'Fashion' => 'fa fa-female',
            'Gold' => 'fa fa-diamond',
            'Construction' => 'fa fa-building ',
            'Rice' => 'fa fa-cutlery',
            'Engineering' => 'fa fa-gavel',
            'Ups' => 'fa fa-envelope ',
            'Internet' => 'fa fa-internet-explorer',
            'Craft' => 'fa fa-wrench',
            'Restaurant' => 'fa fa-cutlery',
            'Insurance' => 'fa fa-envelope',
            'Dress' => 'fa fa-shopping-bag',
            'Fan' => 'fa fa-smile-o',
            'Plastic' => 'fa fa-cubes',
            'Courier' => 'fa fa-truck',
            'Paper' => 'fa fa-file-o',
            'Gas' => 'fa fa-fire',
            'Agriculture' => 'fa fa-apple',
            'Camera' => 'fa fa-camera',
            'Advertising' => 'fa fa-newspaper-o',
            'Battery' => 'fa fa-battery-full',
            'Share Market' => 'fa fa-line-chart',
            'Automobile' => 'fa fa-car',
            'Web' => 'fa fa-internet-explorer',
            'Beauty Parlour' => 'fa fa-female',
            'Bags' => 'fa fa-suitcase',
            'Iron' => 'fa fa-industry',
            'Energy' => 'fa fa-sun-o',
            'Footwear' => 'fa fa-shopping-cart',
            'Diamond Rings' => 'fa fa-diamond',
            'Security' => 'fa fa-user-secret',
            'Logistics' => 'fa fa-truck',
            'Tiles' => 'fa fa-cubes',
            'Sports' => 'fa fa-futbol-o',
            'Machine' => 'fa fa-truck',
            'Bikes' => 'fa fa-motorcycle',
            'College' => 'fa fa-graduation-cap',
            'Tourism' => 'fa fa-plane',
            'Homeopathy' => 'fa fa-heartbeat',
            'Tea' => 'fa fa-coffee',
            'Aluminium' => 'fa fa-industry',
            'Hardware' => 'fa fa-desktop',
            'T-Shirt' => 'fa fa-shopping-cart ',
            'Shirts' => 'fa fa-shopping-cart ',
            'Jeans' => 'fa fa-shopping-cart ',
            'Computer Hardware' => 'fa fa-laptop',
            'Kitchen' => 'fa fa-cutlery',
            'Steel' => 'fa fa-industry',
            'Film' => 'fa fa-film',
            'Coffee' => 'fa fa-coffee',
            'Bed' => 'fa fa-bed',
            'Sales' => 'fa fa-cart-arrow-down',
            'Web Designing' => 'fa fa-picture-o',
            'Diamond Jewellery' => 'fa fa-diamond',
            'Dance' => 'fa fa-child',
            'Accounting' => 'fa fa-calculator',
            'Houses' => 'fa fa-home',
            'Cotton' => 'fa fa-envira',
            'Courier Services' => 'fa fa-truck',
            'Event Management' => 'fa fa-birthday-cake',
            'Printer' => 'fa fa-file-text-o',
            'Ac' => 'fa fa-snowflake-o',
            'Ball' => 'fa fa-futbol-o',
            'Bar' => 'fa fa-beer',
            'Photo' => 'fa fa-camera-retro',
            'Ice Cream' => 'fa fa-cutlery',
            'Inverter' => 'fa fa-bolt',
            'Diseases' => 'fa fa-ambulance',
            'Eye' => 'fa fa-eye',
            'Mica' => 'fa fa-industry',
            'Door' => 'fa fa-home',
            'Oil' => 'fa fa-tint',
            'Boutique' => 'fa fa-shopping-cart ',
            'Fabrication' => 'fa fa-industry',
            'News Paper' => 'fa fa-newspaper-o',
            'Granite' => 'fa fa-cubes',
            'Western Union' => 'fa fa-telegram',
            'Watches' => 'fa fa-clock-o',
            'Transportation' => 'fa fa-truck',
            'Gold Jewellery' => 'fa fa-diamond',
            'Tools' => 'fa fa-wrench',
            'Cement' => 'fa fa-industry',
            'Architect' => 'fa fa-building-o',
            'Advocate' => 'fa fa-legal',
            'Alarm' => 'fa fa-bell',
            'Projector' => 'fa fa-video-camera',
            'Handbags' => 'fa fa-shopping-bag',
            'Glass' => 'fa fa-eye',
            'Packaging' => 'fa fa-archive',
            'Commercial' => 'fa fa-user-circle-o',
            'Medical' => 'fa fa-medkit',
            'Yoga' => 'fa fa-heart',
            'Silk' => 'fa fa-leaf',
            'Die' => 'fa fa-industry',
            'Soap' => 'fa fa-shower',
            'Pharmaceutical' => 'fa fa-medkit',
            'Art' => 'fa fa-picture-o',
            'Vehicle' => 'fa fa-bicycle',
            'Refrigerator' => 'fa fa-snowflake-o',
            'Pulses' => 'fa fa-industry',
            'Diesel' => 'fa fa-tint',
            'Life Insurance' => 'fa fa-handshake-o',
            'Management' => 'fa fa-group',
            'Ice' => 'fa fa-snowflake-o',
            'Plant' => 'fa fa-pagelines',
            'Vegetable' => 'fa fa-apple',
            'Stationery' => 'fa fa-industry',
            'Bathroom' => 'fa fa-bath',
            'Bolt' => 'fa fa-industry',
            'Homes' => 'fa fa-home',
            'Bearing' => 'fa fa-gears',
            'Wood' => 'fa fa-tree',
            'Interior Decoration' => 'fa fa-picture-o',
            'Home Appliances' => 'fa fa-cutlery',
            'Bricks' => 'fa fa-cubes',
            'Petroleum' => 'fa fa-tint',
            'Clothes' => 'fa fa-shopping-cart ',
            'Brass' => 'fa fa-industry',
            'Post Office' => 'fa fa-envelope-open',
            'Fire' => 'fa fa-fire',
            'Beverages' => 'fa fa-glass',
            'Children' => 'fa fa-child',
            'Magazine' => 'fa fa-newspaper-o ',
            'Hair' => 'fa fa-female',
            'Metal' => 'fa fa-industry',
            'Toilet' => 'fa fa-bath',
            'Religion' => 'fa fa-university',
            'Stainless Steel' => 'fa fa-industry',
            'Toys' => 'fa fa-child',
            'Audio' => 'fa fa-headphones',
            'Lawyer' => 'fa fa-legal',
            'Dairy' => 'fa fa-file-text-o',
            'Homeopathic Medicine' => 'fa fa-heartbeat',
            'Laptop' => 'fa fa-laptop',
            'Fashion Designing' => 'fa fa-scissors',
            'Library' => 'fa fa-book',
            'Export' => 'fa fa-truck',
            'Solar Energy' => 'fa fa-sun-o',
            'Ayurvedic' => 'fa fa-heartbeat',
            'Sweets' => 'fa fa-birthday-cake',
            'Jacket' => 'fa fa-shopping-cart ',
            'Telecom' => 'fa fa-phone',
            'Marble' => 'fa fa-industry',
            'Ayurvedic Medicine' => 'fa fa-heartbeat',
            'Sewing Machine' => 'fa fa-hand-spock-o',
            'Designer' => 'fa fa-magic',
            'Leather' => 'fa fa-industry',
            'Healthy Food' => 'fa fa-cutlery',
            'Wedding Cards' => 'fa fa-venus-mars',
            'Fabric' => 'fa fa-industry',
            'Safety' => 'fa fa-user-secret',
            'Warehouse' => 'fa fa-home',
            'Marriage' => 'fa fa-venus-mars',
            'Dermatologist' => 'fa fa-user-md',
            'Shorts' => 'fa fa-shopping-cart ',
            'Seal' => 'fa fa-industry',
            'Foreign Exchange' => 'fa fa-money'
        ];

        if (!empty($arr[$category])) return $arr[$category];
        else return '';
    }
}
