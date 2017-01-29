<?php
namespace App\Scrapers;

use App\Category;
use App\CategoryCompany;
use App\Company;
use App\CompanyPhoto;
use App\CompanyReview;
use App\CompanyHour;
use App\Option;
use App\OptionGroup;
use App\Scrapers\Scraper;
use App\Scrapers\ScraperInterface;
use App\Scrapers\FoursquareScraper;
use App\Scrapers\TamScraper;
use App\Repositories\CompanyRepository;

use Intervention\Image\ImageManagerStatic as Image;
use File;
use DB;
use Str;

class Tam extends Scraper implements ScraperInterface
{
    public function __construct()
    {
        $this->limitPhotos = 20;
        $this->limitCompanies = 0; // 0 means unlimited

        $this->companyRepository = new \App\Repositories\CompanyRepository();
    }

    public function process($url)
    {
        // get category page
        $categoryPage = $this->request($url);

        // parse category for all companies
        $companies = TamScraper::getCompaniesList($categoryPage, $this->limitCompanies);

        //print_r($companies); exit();

        foreach ($companies as $company) {
            echo "Company {$company['domain']} is being processed...\n";

            $checkCompany = $this->checkIfExists($this->getParam('site_id'), $company['domain']);

            // if company exists, check it for category
            if ($checkCompany) {
                echo "Company {$checkCompany->original_url} exists. Checking category...\n";

                $checkIfCompanyExistsInCategory = $this->checkIfCompanyExistInCategory($this->getParam('category_id'), $checkCompany->id);

                if (!$checkIfCompanyExistsInCategory) {
                    // company doesn't exist with that category, add it
                    echo "Company {$checkCompany->original_url} DOES NOT exist in category {$this->getParam('category_id')}. Added.\n\n";

                    CategoryCompany::create([
                        'category_id' => $this->getParam('category_id'),
                        'company_id' => $checkCompany->id
                    ]);
                }
                else {
                    // company exist in that category, skip it
                    echo "Company {$checkCompany->original_url} exist in category {$this->getParam('category_id')}. Skipped.\n\n";
                }

                continue;
            }
            else {
                echo "Does not exist\n";
            }

            // set company url
            $this->setParam('company_url', $company['page']);

            // get company page
            $companyPage = $this->request($company['page']);

            // get text from page
            $companyData = $this->prepareCompany($companyPage);

            //$companyInfo = $this->processCompany($this->getParam('site_id'), $companyData);
            $companyInfo = CompanyRepository::create($companyData);

            // hours
            $hours = TamScraper::hours($companyPage);
            $this->processHours($companyInfo->id, $hours);

            // options
            $options = TamScraper::options($companyPage);
            $this->processOptions($this->getParam('site_id'), $companyInfo->id, $options);

            /*
             * ================ PHOTOS ================
             */

            // get photo page
            //$photosPage = $this->request($company['photos']);

            $photos = TamScraper::photos($companyPage);
            if (!empty($photos)) {
                echo "Has " . count($photos) . " photos\n";

                $lastPhoto = $this->processPhotos($companyInfo->id, $photos);
                $companyInfo->main_photo_url = $lastPhoto->url;

                echo count($photos) . " are being processed\n";
            }

            /*
            $foursquareUrl = TamScraper::foursquareUrl($companyPage);
            if (!empty($foursquareUrl)) {
                // reviews
                echo "Taking reviews from Foursquare...\n";
                $foursquarePage = $this->request($foursquareUrl . "?tipsSort=recent");
                $reviews        = FoursquareScraper::reviews($foursquarePage);
                $lastReview     = $this->processReviews($companyInfo->id, 1, $reviews);

                // photos
                echo "Taking photos from Foursquare ...\n";
                $foursquarePage = $this->request($foursquareUrl . "/photos");
                $photos         = FoursquareScraper::photos($foursquarePage);
                $lastPhoto      = $this->processPhotos($companyInfo->id, $photos);

                // update company info
                $companyInfo->main_photo_url = $lastPhoto->url;
                $companyInfo->last_review = $lastReview;
                $companyInfo->amount_comments = count($reviews);
            }
            */

            // save updated columns
            $companyInfo->save();

            echo $companyInfo->name . " added.\n\n";
            //exit();
        }
    }

    protected function prepareCompany($page)
    {
        $data = [];

        $data['site_id']          = $this->getParam('site_id');
        $data['category_id']      = $this->getParam('category_id');
        $data['original_url']     = $this->getParam('company_url');
        $data['name']             = TamScraper::name($page);
        $data['address']          = TamScraper::address($page);
        $data['tel']              = TamScraper::telephone($page);
        $data['website']          = TamScraper::website($page);
        $data['rating']           = TamScraper::rating($page);
        $data['price_range']      = TamScraper::priceRange($page);
        $data['latitude']         = TamScraper::latitude($page);
        $data['longitude']        = TamScraper::longitude($page);
        $data['description']      = TamScraper::description($page);
        $data['scraper_unique']   = $this->getParam('company_url');
        $data['last_review']      = '';

        // get original slug
        $slug = TamScraper::getSlug($this->getParam('company_url'));

        // check if slug will be very long
        if (!empty($slug)) {
            $slugOur = strlen(str_slug($data['name']));
            $slugOriginal = strlen($slug);

            $lenDifference = abs($slugOriginal - $slugOur);

            if ($lenDifference > 4) {
                $data['domain'] = $slug;
            }
            else {
                $data['domain'] = str_slug($data['name']);
            }
        }

        $data['url'] = "http://" . $data['domain'] . "." . $this->getParam('domain') . "/";

        return $data;
    }

    public function getPagePartOfUrl($page)
    {
        $page = ($page - 1);

        if ($page > 1) return "page" . $page . "/";
    }
}