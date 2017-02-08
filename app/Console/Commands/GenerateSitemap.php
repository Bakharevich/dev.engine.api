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
    protected $signature = 'sitemap:generate {site_id?}';

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

        $siteId = $this->argument('site_id');

        // main sitemap file
        $xmlSitemapIndex = new \SimpleXMLElement('<sitemapindex />');
        $xmlSitemapIndex->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        // get all sites
        $query = Site::query();

        if (!empty($siteId)) {
            $query->where('id', $siteId);
        }

        $sites = $query->get();

        if (count($sites) < 1) {
            $this->warn("Site with id {$siteId} not found");
        }

        foreach ($sites as $site) {
            $this->info($site->domain);

            // main sitemapindex
            $xmlSitemapIndex = new \SimpleXMLElement('<sitemapindex />');
            $xmlSitemapIndex->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

            /*
             * CATEGORIES
             */
            // init xml
            $xmlCategory = new \SimpleXMLElement('<urlset />');
            $xmlCategory->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
            $xmlCategory->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
            $xmlCategory->addAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

            // get categories
            $categories = Category::where('site_id', $site->id)->get();

            if (!empty($categories)) {
                foreach ($categories as $category) {
                    $url = $xmlCategory->addChild('url');
                    $url->addChild('loc', $category->url);
                    $url->addChild('priority', 1);
                }
            }

            // save to disc
            $filename = "sitemap_{$site->domain}_categories.xml";
            $xmlCategory->asXML("public/sitemaps/{$filename}");

            // add to main sitemap file
            $sitemap = $xmlSitemapIndex->addChild('sitemap');
            $sitemap->addChild('loc', "http://{$site->domain}/sitemaps/{$filename}");
            $sitemap->addChild('lastmod', date("c"));

            $this->line("Categories generated");


            /*
             * COMPANIES
             */
            $companiesCount = Company::where('site_id', $site->id)->count();
            $this->line("Companies' count: {$companiesCount}");

            for ($i = 0; $i <= $companiesCount; $i = $i + 16000) {
                $xmlCompanies = new \SimpleXMLElement('<urlset />');
                $xmlCompanies->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
                $xmlCompanies->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
                $xmlCompanies->addAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

                // get companies step by step
                $companies = Company::where('site_id', $site->id)->take(16000)->skip($i)->get();

                foreach ($companies as $company) {
                    if (!preg_match("|/$|", $company->url)) $companyUrl = $company->url . "/";
                    else $companyUrl = $company->url;

                    // main company URL
                    $url = $xmlCompanies->addChild('url');
                    $url->addChild('loc', $company->url);
                    $url->addChild('priority', 0.9);
                    $url->addChild('lastmod', date("c", strtotime($company['updated_at'])));

                    // reviews
                    if (!empty($company->amount_comments)) {
                        $url = $xmlCompanies->addChild('url');
                        $url->addChild('loc', $companyUrl . 'reviews');
                        $url->addChild('priority', 0.7);
                        $url->addChild('lastmod', date("c", strtotime($company['updated_at'])));
                    }

                    // photos
                    if (!empty($company->main_photo_url)) {
                        $url = $xmlCompanies->addChild('url');
                        $url->addChild('loc', $companyUrl . 'photos');
                        $url->addChild('priority', 0.7);
                        $url->addChild('lastmod', date("c", strtotime($company['updated_at'])));
                    }
                }

                $filename = "sitemap_{$site->domain}_companies_{$i}.xml";

                // save XML to disc
                $xmlCompanies->asXML("public/sitemaps/" . $filename);
                unset($xmlCompanies);

                // add to main sitemap file
                $sitemap = $xmlSitemapIndex->addChild('sitemap');
                $sitemap->addChild('loc', "http://{$site->domain}/sitemaps/{$filename}");
                $sitemap->addChild('lastmod', date("c"));
            }
            //exit();


            /*
             * NEWS
             */
            $xmlNews = new \SimpleXMLElement('<urlset />');
            $xmlNews->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
            $xmlNews->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
            $xmlNews->addAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

            $news = News::where('site_id', $site->id)->get();

            if (!empty($news) && count($news) > 0) {
                foreach ($news as $new) {
                    if (!empty($new['updated_at'])) $date = $new['updated_at'];
                    else $date = date("Y-m-d H:i:s");

                    $url = $xmlNews->addChild('url');
                    $url->addChild('loc', $new->url);
                    $url->addChild('priority', 0.);
                    $url->addChild('lastmod', date("c", strtotime($date)));
                }

                $filename = "sitemap_{$site->domain}_news.xml";
                $xmlNews->asXML("public/sitemaps/{$filename}");

                // add to main sitemap file
                $sitemap = $xmlSitemapIndex->addChild('sitemap');
                $sitemap->addChild('loc', "http://{$site->domain}/sitemaps/{$filename}");
                $sitemap->addChild('lastmod', date("c"));
            }


            /*
             * MAIN SITEMAP FILE
             */
            $xmlSitemapIndex->asXML("public/sitemaps/sitemap_{$site->domain}.xml");
        }

        $this->info('Generation finished');
    }
}
