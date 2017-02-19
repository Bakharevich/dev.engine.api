<?php
Route::any('/contact', 'Web\PageController@contact');
Route::get('/companies/create', 'Web\CompanyController@create');
Route::post('/companies/create', 'Web\CompanyController@store');
Route::get('/companies/success', 'Web\CompanyController@success');
Route::get('/page/{slug}', 'Web\PageController@show');
Route::get('/blog/{slug}', 'Web\NewsController@show');
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/admin/companies', 'Admin\CompanyController@index');

Auth::routes();

$domains = [
    'yelpster.dev', 'yelpster.net', 'ibelarus.dev', 'ibelarus.by', 'infomalaysia.net', '24india.in'
];

foreach ($domains as $domain) {
    Route::group(['domain' => '{companyDomain}.' . $domain], function() {
        Route::get('/', 'Web\CompanyController@show');
        Route::get('/photos', 'Web\CompanyController@photos');
        Route::post('/reviews', 'Web\CompanyController@reviewsPost');
        Route::get('/reviews', 'Web\CompanyController@reviewsGet');
    });
}

// Route for future use
//Route::get('/{city}/{category}/{company}', 'CompanyController@show');

Route::get('/{city}/{category}', 'Web\CategoryController@show');
Route::get('/{city}/', 'Web\CityController@show');
Route::get('/', 'Web\IndexController@index')->name('index');