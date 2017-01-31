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
use App\Repositories\CompanyRepository;
use App\Scrapers\YelpScraper;

use Intervention\Image\ImageManagerStatic as Image;
use File;
use DB;
use Str;

class Yelp extends Scraper implements ScraperInterface {
    public function __construct()
    {
        $this->limitPhotos = 20; // 0 means unlimited
        $this->limitCompanies = 0; // 0 means unlimited
        $this->companyRepository = new \App\Repositories\CompanyRepository();
    }

    public function process($url)
    {
        // get category
        $pageCategory = $this->request($url);

        // get companies from category
        $companies = YelpScraper::companiesList($pageCategory);

        // iterate companies
        foreach ($companies as $company) {
            // check if company exists
            $checkCompany = $this->checkIfExists($this->getParam('site_id'), $company['domain']);

            // if company exists, check it for category
            if ($checkCompany) {
                echo "Company {$checkCompany->name} exists. Checking category...\n";

                $checkIfCompanyExistsInCategory = $this->checkIfCompanyExistInCategory($this->getParam('category_id'), $checkCompany->id);

                if (!$checkIfCompanyExistsInCategory) {
                    // company doesn't exist with that category, add it
                    echo "Company {$checkCompany->name} DOES NOT exist in category {$this->getParam('category_id')}. Added.\n\n";

                    CategoryCompany::create([
                        'category_id' => $this->getParam('category_id'),
                        'company_id' => $checkCompany->id
                    ]);
                }
                else {
                    // company exist in that category, skip it
                    echo "Company {$checkCompany->name} exist in category {$this->getParam('category_id')}. Skipped.\n\n";
                }

                continue;
            }
            else {
                echo "{$company['domain']} does not exist\n";
            }

            // set params
            $this->setParam('company_url', $company['page']);
            $this->setParam('scraper_unique', $company['domain']);

            // get company page
            $pageCompany = $this->request($company['page']);

            // prepare companies array
            $companyData = $this->company($pageCompany);

            // creating company
            $companyInfo = CompanyRepository::create($companyData);

            // get photos from page
            $pagePhotos = $this->request($company['photos']);
            $photos = YelpScraper::photos($pagePhotos);

            if (!empty($photos)) {
                if (!empty($this->limitPhotos)) $photos = array_slice($photos, 0, $this->limitPhotos);

                echo "Downloading " . count($photos) . " photos\n";

                $lastPhoto = $this->processPhotos($companyInfo->id, $photos);
                $companyInfo->main_photo_url = $lastPhoto->url;
                $companyInfo->meta_image = $lastPhoto->url;
            }

            // get reviews
            $reviews = YelpScraper::reviews($pageCompany);
            if (!empty($reviews)) {
                $lastReview = $this->processReviews($companyInfo->id, 2, $reviews);
                $companyInfo->amount_comments = count($reviews);
                $companyInfo->last_review = $lastReview;
            }

            // get options
            $options = YelpScraper::options($pageCompany);
            if (!empty($options)) {
                $this->processOptions($this->getParam('site_id'), $companyInfo->id, $options);
            }

            // get hours
            $hours = YelpScraper::hours($pageCompany);
            if (!empty($hours)) {
                $this->processHours($companyInfo->id, $hours);
            }

            $companyInfo->save();

            echo $companyInfo->name . " added\n\n";
        }
    }



    public function company($page)
    {
        $data                     = [];
        $data['site_id']          = $this->getParam('site_id');
        $data['category_id']      = $this->getParam('category_id');
        $data['original_url']     = $this->getParam('company_url');
        $data['name']             = YelpScraper::name($page);
        $data['address']          = YelpScraper::address($page);
        $data['tel']              = YelpScraper::tel($page);
        $data['website']          = YelpScraper::website($page);
        $data['rating']           = YelpScraper::rating($page);
        $data['price_range']      = YelpScraper::pricerange($page);
        $data['latitude']         = YelpScraper::latitude($page);
        $data['longitude']        = YelpScraper::longitude($page);
        $data['scraper_unique']   = $this->getParam('scraper_unique');
        $data['description']      = '';
        $data['last_review']      = '';

        return $data;
    }

    public function getPagePartOfUrl($page = 1)
    {
        $page = ($page - 1) * 10;

        if ($page > 1) return "&start=" . $page;
    }
}