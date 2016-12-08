<?php
use App\Scrapers\Yelp;
use App\Repositories\CompanyRepository;
//use GuzzleHttp;

Route::get('/logout', 'Auth\LoginController@logout');
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

Route::group(['domain' => '{companyDomain}.ibelarus.by'], function() {
    Route::get('/', 'Web\CompanyController@show');
});

Route::get('/test/', function() {
    /*
    $str = "home-treasures-kuala-lumpur-2?search_key=53607";
    $str = preg_replace("|(\?.*)|", "", $str);
    echo $str;
    */
    $pages = 9;
    for ($i = 1; $i <= $pages; $i++) {
        echo $i . "<br/>";
    }
});

Route::get('/categoriesstats/', function() {
    $category = 'https://www.yelp.com/search?find_loc=Kuala+Lumpur&cflt=shopping';

    $categoryPage = file_get_contents($category);

    $reg = "#<a class='category-browse-anchor js-search-header-link' href=\"(.+?)\">(.+?)</a>#";
    preg_match_all($reg, $categoryPage, $matches);

    //echo "<pre>"; print_r($matches);

    $result = [];

    if (!empty($matches)) {
        $i = 1;
        foreach ($matches[1] as $index => $value) {
            if ($i < 49) {
                $i++;
                continue;
            }
            $url = 'https://www.yelp.com' . $matches[1][$index];
            $name = $matches[2][$index];

            $page = file_get_contents($url);

            $reg = "|<span class=\"pagination-results-window\">.*?Showing.*?of ([0-9]{1,}).*?</span>|is";
            preg_match_all($reg, $page, $match);
            //echo "<pre>"; print_r($match);

            if (!empty($match[1][0])) $result[$match[1][0]] = ['url' => $url, 'name' => $name];

            if ($i == 70) break;
            $i++;
        }
    }

    krsort($result);

    echo "<pre>"; print_r($result);
});

Route::get('/', 'Web\IndexController@index')->name('index');


//Route::get('/{city}/{category}/{company}', 'CompanyController@show');
Route::get('/{city}/{category}', 'Web\CategoryController@show');
Route::get('/{city}/', 'Web\CityController@show');





Route::get('/home', 'HomeController@index');
