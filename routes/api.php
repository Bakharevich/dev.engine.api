<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* SITE */
Route::get('sites/getByDomain', 'Api\SiteController@getByDomain')->name('sites.getByDomain');
Route::resource('sites', \Api\SiteController::class);

/* CITIES */
Route::get('cities/getByDomain', 'Api\CityController@getByDomain')->name('cities.getByDomain');
Route::resource('cities', \Api\CityController::class);

/* CATEGORIES */
Route::get('categories/getAllBySiteId', 'Api\CategoryController@getAllBySiteId')->name('categories.getAllBySiteId');
Route::get('categories/getByDomain', 'Api\CategoryController@getByDomain')->name('categories.getByDomain');
Route::resource('categories', \Api\CategoryController::class);

/* COMPANIES */
Route::get('companies/getAllByCategoryDomain', 'Api\CompanyController@getAllByCategoryDomain')->name('companies.getAllByCategoryDomain');
Route::get('companies/getAllByCategoryId', 'Api\CompanyController@getAllByCategoryId')->name('companies.getAllByCategoryId');
Route::get('companies/getHtmlByCategoryId', 'Api\CompanyController@getHtmlByCategoryId')->name('companies.getHtmlByCategoryId');
Route::get('companies/getByDomain', 'Api\CompanyController@getByDomain')->name('companies.getByDomain');
Route::get('companies/search', 'Api\CompanyController@search')->name('companies.search');
Route::post('companies/quote', 'Api\CompanyController@quote')->name('companies.quote');
Route::resource('companies', \Api\CompanyController::class);



/*
Route::get('sites', function (Request $request, \App\Site $site) {
    return response()->json($site->all(), 200);
});

Route::get('categories', function (Request $request, \App\CompanyCategory $companyCategory) {
    return response()->json($companyCategory->all(), 200);
});*/