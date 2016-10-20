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
        $this->limitCompanies = 1; // 0 means unlimited
    }

    public function setOption($key, $content)
    {
        $this->page[$key] = $content;
    }

    public function getOption($key)
    {
        return $this->page[$key];
    }

    public function process($url)
    {
        // get category page
        $categoryPage = $this->request($url);

        // parse category for all companies
        $companies = TamScraper::getCompaniesList($categoryPage, 1);

        foreach ($companies as $company) {
            // check if company exists
            $checkResult = $this->checkIfExists($this->getParam('site_id'), $company['domain']);
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

            print_r($companyData);

        }
    }

    protected function prepareCompany($page)
    {
        $data = [];

        $data['company']['site_id']          = $this->getParam('site_id');
        $data['company']['category_id']      = $this->getParam('category_id');
        $data['company']['original_url']     = $this->getParam('company_url');
        $data['company']['name']             = TamScraper::name($page);
        $data['company']['address']          = TamScraper::address($page);
        $data['company']['tel']              = TamScraper::telephone($page);
        $data['company']['website']          = TamScraper::website($page);
        $data['company']['rating']           = TamScraper::rating($page);
        $data['company']['price_range']      = TamScraper::priceRange($page);
        $data['company']['latitude']         = TamScraper::latitude($page);
        $data['company']['longitude']        = TamScraper::longitude($page);
        $data['company']['description']      = TamScraper::description($page);
        $data['company']['domain']           = str_slug($data['company']['name']);
        $data['company']['last_review']      = '';
        $data['company']['url']              = "http://" . $data['company']['domain'] . "." . $this->getParam('domain') . "/";
//        $data['reviews']                     = $this->getReviews($page);
        $data['hours']                       = TamScraper::getHours($page);
//        $data['options']                     = $this->getOptions($page);
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