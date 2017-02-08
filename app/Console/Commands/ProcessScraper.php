<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\JobScraper;
use Carbon\Carbon;

class ProcessScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrapers:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scrapers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Scraping started...\n\n");

        $jobs = JobScraper::limit(1)->where('is_active', 1)->orderBy('amount_executed', 'asc')->get();

        $manager = new \App\Scrapers\ScraperManager();

        if (!empty($jobs)) {
            foreach ($jobs as $job) {
                // update scraper row before launching of scraper
                $jobScraper = JobScraper::find($job['id']);
                $jobScraper->amount_executed += 1;
                $jobScraper->executed_at = Carbon::now();
                $jobScraper->save();

                // define class name
                $className = '\\App\Scrapers\\' . $job->scraper;
                $scraperObject = new $className;

                // get site
                $site = \App\Site::find($job->site_id);

                // get city
                $city = \App\City::find($job->city_id);

                // get category
                $category = \App\Category::find($job->category_id);

                // set params
                $scraperObject->setParams([
                    'media_url' => $site->media_url,
                    'media_path' => $site->media_path,
                    'domain' => $site->domain,
                    'site_id' => $site->id,
                    'category_id' => $job->category_id,
                    'city' => $city,
                    'category' => $category
                ]);

                // iterate number of pages to take
                for ($i = 1; $i <= $job->pages; $i++) {
                    // getting part for page
                    $pagePart = $scraperObject->getPagePartOfUrl($i);

                    // forming final url
                    $url = $job->url . $pagePart;

                    // processing scraper
                    $manager->process($scraperObject, $url);
                }
            }

            $this->info('Scraping ended');
        }
        else {
            $this->error('There are no active jobs scrapers');
        }
    }
}
