<?php
use App\Scrapers\Yelp;
use App\Repositories\CompanyRepository;
//use GuzzleHttp;

Route::group(['domain' => '{companyDomain}.yelpster.net'], function() {
    Route::get('/', 'Web\CompanyController@show');
});

Route::group(['domain' => '{companyDomain}.ibelarus.by'], function() {
    Route::get('/', 'Web\CompanyController@show');
});

Route::get('/', 'Web\IndexController@index')->name('index');


//Route::get('/{city}/{category}/{company}', 'CompanyController@show');
Route::get('/{city}/{category}', 'Web\CategoryController@show');
Route::get('/{city}/', 'Web\CityController@show');
