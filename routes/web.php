<?php
use App\Scrapers\Yelp;
use App\Repositories\CompanyRepository;
//use GuzzleHttp;

Route::group(['domain' => '{companyDomain}.yelpster.dev'], function() {
    Route::get('/', 'Web\CompanyController@show');
});

Route::get('/', 'Web\IndexController@index')->name('index');



Route::get('/test/', function () {
    echo "YELP<br/><br/>";

    $url = 'https://www.yelp.com/search?find_loc=London&cflt=breakfast_brunch';

    $Yelp = new Yelp();
    $Yelp->setParams([
        'media_url' => 'http://static.engine.dev/yelpster/',
        'media_path' => '/home/vagrant/sites/personal/static.engine.dev/public/yelpster/',
        'domain' => 'yelpster.dev',
        'site_id' => 1,
        'category_id' => 4
    ]);
    $Yelp->process($url);
});

Route::get('/options/', function() {
    $url = 'https://www.yelp.com/biz/yarok-berlin?sort_by=date_desc';

    $client = new GuzzleHttp\Client();

    $res = $client->request(
        'GET',
        $url,
        [ ]
    );

    $file = $res->getBody()->getContents();
});

Route::get('/process/', function () {
    echo "Processing...<br/>";
//    $jobs[] = [
//        'scraper' => 'Yelp',
//        'site_id' => 1,
//        'category_id' => 4,
//        'url' => 'https://www.yelp.com/search?find_loc=London&cflt=breakfast_brunch'
//    ];
    $jobs = App\JobScraper::limit(1)->orderBy('amount_executed', 'asc')->get();

    $manager = new \App\Scrapers\ScraperManager();

    foreach ($jobs as $job) {
        // define class name
        $className = '\\App\Scrapers\\' . $job->scraper;
        $scraperObject = new $className;

        // get site
        $site = \App\Site::find($job->site_id);

        // set params
        $scraperObject->setParams([
            'media_url' => $site->media_url,
            'media_path' => $site->media_path,
            'domain' => $site->domain,
            'site_id' => $site->id,
            'category_id' => $job->category_id
        ]);

        $manager->process($scraperObject, $job->url);

        $jobScraper = App\JobScraper::find($job['id']);
        $jobScraper->amount_executed += 1;
        $jobScraper->executed_at = Carbon\Carbon::now();
        $jobScraper->save();
    }
});

//Route::get('/{city}/{category}/{company}', 'CompanyController@show');
Route::get('/{city}/{category}', 'Web\CategoryController@show');
Route::get('/{city}/', 'Web\CityController@show');
