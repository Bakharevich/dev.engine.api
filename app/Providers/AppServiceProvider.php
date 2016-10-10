<?php

namespace App\Providers;

use App\Repositories\CompanyRepository;
use Illuminate\Support\ServiceProvider;
use Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('shout', function ($string) {
            return trim(strtoupper($string), '(\'\')');
        });

        Blade::directive('company-rating', function ($rating) {
            return trim(strtoupper($rating) . " STARS!", '(\'\')');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
