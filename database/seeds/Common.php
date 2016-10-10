<?php

use Illuminate\Database\Seeder;

class Common extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // countries
        DB::table('countries')->insert([
            'id' => 192,
            'name' => 'Russia'
        ]);
        DB::table('countries')->insert([
            'id' => 36,
            'name' => 'Belarus'
        ]);
        DB::table('countries')->insert([
            'id' => 231,
            'name' => 'Ukraine'
        ]);
        DB::table('countries')->insert([
            'id' => 77,
            'name' => 'United Kingdom'
        ]);

        // cities
        DB::table('cities')->insert([
            'id' => 625144,
            'country_id' => 36,
            'latitude' => 53.9000000,
            'longitude' => 27.5666700,
            'name' => 'Minsk',
            'domain' => 'minsk'
        ]);
        DB::table('cities')->insert([
            'id' => 524901,
            'country_id' => 192,
            'latitude' => 55.7522200,
            'longitude' => 37.6155600,
            'name' => 'Moscow',
            'domain' => 'moscow'
        ]);
        DB::table('cities')->insert([
            'id' => 498817,
            'country_id' => 192,
            'latitude' => 59.9386300,
            'longitude' => 30.3141300,
            'name' => 'Saint Petersburg',
            'domain' => 'spb'
        ]);
        DB::table('cities')->insert([
            'id' => 703448,
            'country_id' => 231,
            'latitude' => 50.4546600,
            'longitude' => 30.5238000,
            'name' => 'Kiev',
            'domain' => 'kiev'
        ]);
        DB::table('cities')->insert([
            'id' => 2643743,
            'country_id' => 77,
            'latitude' => 51.5085300,
            'longitude' => -0.1257400,
            'name' => 'London',
            'domain' => 'london'
        ]);
    }
}
