<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Common::class);
        $this->call(Scrapers::class);
        $this->call(Yelpster::class);
        $this->call(Ibelarus::class);
    }
}
