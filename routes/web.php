<?php
use \App\Scrapers\InfoisinfoScraper;

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

// test route
//Route::get('/infoisinfo', function () {
//    $data = [];
//
//    $url = "https://bangalore.infoisinfo.co.in/card/aed_courier_service/3508708";
//
//    $page = file_get_contents($url);
//
//    // get key to make request for phone
//    $key = InfoisinfoScraper::getPhoneKey($page);
//    echo $key . "<br/>";
//
//    if (!empty($key)) {
//        // get url link for phones
//        $phoneUrl = InfoisinfoScraper::getPhoneUrlPage($url, $key);
//
//        $pagePhone = file_get_contents($phoneUrl);
//
//        $data['tel'] = InfoisinfoScraper::telephone($pagePhone);
//    }
//
//    $data['name']             = InfoisinfoScraper::name($page);
//    $data['address']          = InfoisinfoScraper::address($page);
//    //$data['tel']              = InfoisinfoScraper::telephone($page);
//    $data['website']          = InfoisinfoScraper::website($page);
//    $data['rating']           = InfoisinfoScraper::rating($page);
//    $data['latitude']         = InfoisinfoScraper::latitude($page);
//    $data['longitude']        = InfoisinfoScraper::longitude($page);
//    $data['description']      = InfoisinfoScraper::description($page);
//
//    echo "<pre>"; print_r($data);
//});

// Route for future use
//Route::get('/{city}/{category}/{company}', 'CompanyController@show');

Route::get('/{city}/{category}', 'Web\CategoryController@show');
Route::get('/{city}/', 'Web\CityController@show');
Route::get('/', 'Web\IndexController@index')->name('index');