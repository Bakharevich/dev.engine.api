<?php
namespace App\Scrapers;

use App\Category;
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
        $this->limitCompanies = 10; // 0 means unlimited
    }

    public function process($url)
    {
        // get category page
        $categoryPage = $this->request($url);

        // parse category for all companies
        $companies = TamScraper::getCompaniesList($categoryPage, $this->limitCompanies);

        foreach ($companies as $company) {
            // check if company exists
            $checkResult = $this->checkIfExists($this->getParam('site_id'), $company['domain']);
            //$checkResult = '';
            if ($checkResult) {
                echo $company['page'] . " exists. Skipping...\n";
                continue;
            }
            else {
                echo $company['page'] . " started...\n";
            }

            // set company url
            $this->setParam('company_url', $company['page']);

            // get company page
            $companyPage = $this->request($company['page']);

            // get text from page
            $companyData = $this->prepareCompany($companyPage);
            $companyInfo = $this->processCompany($this->getParam('site_id'), $companyData);

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
            $photosPage = $this->request($company['photos']);

            //$photos = TamScraper::photos($photosPage);

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

            // save updated columns
            $companyInfo->save();

            echo $companyInfo->name . " added.\n";
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
        $data['domain']           = str_slug($data['name']);
        $data['scraper_unique']   = $this->getParam('company_url');
        $data['last_review']      = '';
        $data['url']              = "http://" . $data['domain'] . "." . $this->getParam('domain') . "/";

//        $data['reviews']                     = $this->getReviews($page);
//        $data['company']['amount_comments']  = count($data['reviews']);
//        $data['types']                       = $this->getTypes($page);
//
//        // add meta data
//        $data['company']['meta_title']       = $data['company']['name'] . " - " . $this->getParam('city')->name;
//        $data['company']['meta_keywords']    = $data['company']['name'] . ", " . $this->getParam('city')->name . ", " . $this->getParam('category')->name . ", reviews of " . $data['company']['name'];
//        $data['company']['meta_description'] = "Reviews of " . $data['company']['name'] . " in " . $this->getParam('city')->name . ": ";
//
//        if (!empty($data['reviews'][0]['review'])) $data['company']['meta_description'] .= str_limit(strip_tags($data['reviews'][0]['review']), $limit = 80, $end = '...');

        return $data;
    }



    public function getPagePartOfUrl($page)
    {
        $page = ($page - 1);

        if ($page > 1) return "page" . $page . "/";
    }
}