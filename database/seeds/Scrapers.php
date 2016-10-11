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
        DB::table('jobs_scrapers')->insert([
            'site_id' => 1,
            'scraper' => 'Yelp',
            'category_id' => 4,
            'url' => 'https://www.yelp.com/search?find_loc=London&cflt=breakfast_brunch'
        ]);

        DB::table('jobs_scrapers')->insert([
            'site_id' => 1,
            'scraper' => 'Yelp',
            'category_id' => 5,
            'url' => 'https://www.yelp.com/search?find_loc=London&cflt=bars'
        ]);
    }
}
