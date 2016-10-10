<?php

use Illuminate\Database\Seeder;

class Yelpster extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // sites
        DB::table('sites')->insert([
            'id' => 1,
            'country_id' => 77,
            'city_id' => 2643743,
            'name' => '<i class="fa fa-hand-peace-o" aria-hidden="true"></i> Yelpster',
            'media_url' => 'http://static.engine.dev/yelpster/',
            'media_path' => '/home/vagrant/sites/personal/static.engine.dev/public/yelpster/',
            'domain' => 'yelpster.dev',
            'headline' => 'Find the best places in city',
            'menu_type' => 2,
            'locale' => 'en'
        ]);

        // categories_groups
        DB::table('categories_groups')->insert([
            'id' => 1,
            'site_id' => 1,
            'name1' => 'Restaurants',
            'name2' => '& cafes'
        ]);
        DB::table('categories_groups')->insert([
            'id' => 2,
            'site_id' => 1,
            'name1' => 'Beauty',
            'name2' => '& Spas'
        ]);
        DB::table('categories_groups')->insert([
            'id' => 3,
            'site_id' => 1,
            'name1' => 'Health',
            'name2' => '& Medical'
        ]);
        DB::table('categories_groups')->insert([
            'id' => 4,
            'site_id' => 1,
            'name1' => 'Nighlife',
            'name2' => 'in UK'
        ]);
        DB::table('categories_groups')->insert([
            'id' => 5,
            'site_id' => 1,
            'name1' => 'Shopping',
            'name2' => '& stores'
        ]);

        // categories
        DB::table('categories')->insert([
            'id' => 4,
            'site_id' => 1,
            'category_group_id' => 1,
            'name' => 'Breakfast & Brunch',
            'domain' => 'breakfast-brunch',
            'description_top' => 'Start your weekend right with this epic list of the best brunches in London, from waffles and pancakes to fry-ups and eggs every which way',
            'description_bottom' => '<p>Brunch is a combination of breakfast and lunch eaten usually during the late morning, but it can extend to as late as 3pm.[1][2] The word is a portmanteau of breakfast and lunch.[3] Brunch originated in England in the late 19th century and became popular in the United States in the 1930s.</p><p>Brunch in London is bigger than ever. You can bearly set foot out your front door at the weekend without stumbling across a steaming pan of shakshuka or finding the waft of waffles in the air. So let us guide you to the best spots in town for a kick-ass weekend brunch in London, from boozy bottomless brunches to traditional Full English fry-ups and even New York-style feasts, you can start off your weekend in style.</p>',
            'icon' => 'fa-cutlery',
            'url' => 'http://yelpster.dev/london/breakfast-brunch/'
        ]);
    }
}
