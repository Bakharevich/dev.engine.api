<?php

use Illuminate\Database\Seeder;

class Scrapers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('jobs_scrapers')->insert([
//            'site_id' => 1,
//            'scraper' => 'Yelp',
//            'category_id' => 4,
//            'city_id' => 2643743,
//            'url' => 'https://www.yelp.com/search?find_loc=London&cflt=breakfast_brunch'
//        ]);
//
        DB::table('jobs_scrapers')->insert([
            'site_id' => 1,
            'scraper' => 'Yelp',
            'category_id' => 5,
            'city_id' => 2643743,
            'url' => 'https://www.yelp.com/search?find_loc=London&cflt=bars'
        ]);

        DB::table('jobs_scrapers')->insert([
            'site_id' => 2,
            'scraper' => 'Tam',
            'category_id' => 1,
            'city_id' => 625144,
            'url' => 'http://tam.by/eda/restorany-kafe/',
            'pages' => 1
        ]);
    }
}
