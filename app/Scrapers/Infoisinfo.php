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
use App\Scrapers\InfoisinfoScraper;
use App\Repositories\CompanyRepository;

use Intervention\Image\ImageManagerStatic as Image;
use File;
use DB;
use Str;

class Infoisinfo extends Scraper implements ScraperInterface
{
    public function __construct()
    {
        $this->limitPhotos = 20;
        $this->limitCompanies = 0; // 0 means unlimited
    }

    public function process($url)
    {
        // get category page
        $categoryPage = $this->request($url);

        // parse category for all companies
        $companies = InfoisinfoScraper::getCompaniesList($categoryPage, $this->limitCompanies);
        //echo "<pre>"; print_r($companies);

        foreach ($companies as $company) {
            // check if company exists
            $checkResult = $this->checkIfExists($this->getParam('site_id'), $company['domain']);
            //$checkResult = '';
            if ($checkResult) {
                echo $company['domain'] . " exists. Skipping...\n";
                continue;
            }
            else {
                echo $company['domain'] . " started...\n";
            }

            // set company url
            $this->setParam('company_url', $company['domain']);

            // get company page
            $companyPage = $this->request($company['domain']);

            // get text from page
            $companyData = $this->prepareCompany($companyPage);
            $companyInfo = $this->processCompany($this->getParam('site_id'), $companyData);

            // hours
            $hours = InfoisinfoScraper::hours($companyPage);
            $this->processHours($companyInfo->id, $hours);

            // get photo page
            $photos = InfoisinfoScraper::photos($companyPage);
            if (!empty($photos)) {
                $lastPhoto = $this->processPhotos($companyInfo->id, $photos);
                $companyInfo->main_photo_url = $lastPhoto->url;
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
        $data['name']             = InfoisinfoScraper::name($page);
        $data['address']          = InfoisinfoScraper::address($page);
        $data['tel']              = InfoisinfoScraper::telephone($page);
        $data['website']          = InfoisinfoScraper::website($page);
        $data['rating']           = InfoisinfoScraper::rating($page);
        $data['latitude']         = InfoisinfoScraper::latitude($page);
        $data['longitude']        = InfoisinfoScraper::longitude($page);
        $data['description']      = InfoisinfoScraper::description($page);
        $data['domain']           = str_slug($data['name']);
        $data['scraper_unique']   = $this->getParam('company_url');
        $data['last_review']      = '';
        $data['url']              = "http://" . $data['domain'] . "." . $this->getParam('domain') . "/";

        return $data;
    }



    public function getPagePartOfUrl($page)
    {
        if ($page > 1) return "/" . $page;
    }
}