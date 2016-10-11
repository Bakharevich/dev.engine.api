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
        echo "Scraping started...\n\n";

        $jobs = JobScraper::limit(1)->orderBy('amount_executed', 'asc')->get();

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

            $jobScraper = JobScraper::find($job['id']);
            $jobScraper->amount_executed += 1;
            $jobScraper->executed_at = Carbon::now();
            $jobScraper->save();
        }
    }
}
