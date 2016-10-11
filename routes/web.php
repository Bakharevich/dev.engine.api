<?php
use App\Scrapers\Yelp;
use App\Repositories\CompanyRepository;
//use GuzzleHttp;

$domains = [
    'yelpster.dev', 'yelpster.net', 'ibelarus.dev', 'ibelarus.by'
];

foreach ($domains as $domain) {
    Route::group(['domain' => '{companyDomain}.' . $domain], function() {
        Route::get('/', 'Web\CompanyController@show');
    });
}

Route::group(['domain' => '{companyDomain}.ibelarus.by'], function() {
    Route::get('/', 'Web\CompanyController@show');
});

Route::get('/', 'Web\IndexController@index')->name('index');


//Route::get('/{city}/{category}/{company}', 'CompanyController@show');
Route::get('/{city}/{category}', 'Web\CategoryController@show');
Route::get('/{city}/', 'Web\CityController@show');
