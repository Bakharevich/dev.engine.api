<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Site;
use App\Category;
use App\Company;
use App\News;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.xml for every site';

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
        $this->info("Started to generate sitemap.xml...");

        // main sitemap file
        $xmlSitemapIndex = new \SimpleXMLElement('<sitemapindex />');
        $xmlSitemapIndex->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        // get all sites
        $sites = Site::all();

        foreach ($sites as $site) {
            $this->info($site->domain);

            // init xml
            $xml = new \SimpleXMLElement('<urlset />');
            $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
            $xml->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
            $xml->addAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

            /*
             * CATEGORIES
             */
            $categories = Category::where('site_id', $site->id)->get();

            if (!empty($categories)) {
                foreach ($categories as $category) {
                    $this->line("Category " . $category->name);

                    $url = $xml->addChild('url');
                    $url->addChild('loc', $category->url);
                    $url->addChild('priority', 0.9);

                    /*
                     * COMPANIES
                     */
                    $companies = Company::where('category_id', $category->id)->get();

                    foreach ($companies as $company) {
                        if (!preg_match("|/$|", $company->url)) $companyUrl = $company->url . "/";
                        else $companyUrl = $company->url;

                        // main company URL
                        $url = $xml->addChild('url');
                        $url->addChild('loc', $company->url);
                        $url->addChild('priority', 0.8);
                        $url->addChild('lastmod', date("c", strtotime($company['updated_at'])));

                        // reviews
                        $url = $xml->addChild('url');
                        $url->addChild('loc', $companyUrl . 'reviews');
                        $url->addChild('priority', 0.7);
                        $url->addChild('lastmod', date("c", strtotime($company['updated_at'])));

                        // photos
                        $url = $xml->addChild('url');
                        $url->addChild('loc', $companyUrl . 'photos');
                        $url->addChild('priority', 0.7);
                        $url->addChild('lastmod', date("c", strtotime($company['updated_at'])));
                    }
                }
            }

            /*
             * NEWS
             */
            $news = News::where('site_id', $site->id)->get();

            if (!empty($news)) {
                foreach ($news as $new) {
                    $url = $xml->addChild('url');
                    $url->addChild('loc', $new->url);
                    $url->addChild('priority', 0.7);
                    $url->addChild('lastmod', date("c", strtotime($new['updated_at'])));
                }
            }

            $xml->asXML("public/sitemaps/sitemap_{$site->domain}.xml");
        }

        $this->info('Generation finished');
    }
}
