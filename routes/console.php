<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('scrape', function () {
    Artisan::call('scrapers:process');
})->describe('Start scraping process');

Artisan::command('sitemap', function () {
    Artisan::call('sitemap:generate');
})->describe('Generate sitemap.xml for every site');

Artisan::command('prepare', function () {
    Artisan::call('scrapers:prepare-site');
})->describe('Creating new categories, jobs and other stuff');