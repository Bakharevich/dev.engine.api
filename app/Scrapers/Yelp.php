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

use Intervention\Image\ImageManagerStatic as Image;
use File;
use DB;
use Str;

class Yelp extends Scraper implements ScraperInterface {
    private $companyRepo;

    public function __construct()
    {
        //$this->companyRepo = $companyRepo;
        $this->limitPhotos = 20;
        $this->limitCompanies = 0; // 0 means unlimited
    }

    public function process($url)
    {
        // parse category for all companies
        $companies = $this->parseCategory($url);

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
            
            // get text from page
            $companyText = $this->parseCompanyText($company['page']);

            // if empty name, don't add company
            if (empty($companyText['company']['name'])) {
                echo "PROBLEM: Unknown name\n";
                continue;
            }

            // add scraper unique
            $companyText['company']['scraper_unique'] = $company['domain'];

            // get photos from page
            $companyPhotos = $this->parseCompanyPhotos($company['photos']);

            // create company
            $companyData = $this->processCompany($this->getParam('site_id'), $companyText['company']);

            // add reviews
            $lastReview = $this->processReviews($companyData->id, 0, $companyText['reviews']);

            // download and save photos
            if (!empty($companyPhotos)) {
                $lastPhoto = $this->processPhotos($companyData->id, $companyPhotos);
            }
            else $lastPhoto = false;

            // get options, check in database, add if not exists, connect to company
            $this->processOptions($this->getParam('site_id'), $companyData->id, $companyText['options']);

            // process company type
            $this->processTypes($companyText['types'], $companyData);

            // save hours
            $this->processHours($companyData->id, $companyText['hours']);

            // update company info according last changes
            if ($lastPhoto) {
                $companyData->main_photo_url = $lastPhoto->url;
                $companyData->meta_image = $lastPhoto->url;
            }
            if ($lastReview) {
                $companyData->last_review = $lastReview;
            }
            $companyData->save();

            //echo "<pre>"; print_r($companyText); print_r($companyPhotos); echo "</pre>";
            echo $companyData->name . " added.\n";
        }
    }

    public function parseCategory($url)
    {
        $categoryPage = $this->request($url);

        $reg = '|href=\"/biz/(.+?)\" data-hovercard-id=\".*?\"|';

        preg_match_all($reg, $categoryPage, $companiesScraped);

        $companies = [];
        if (!empty($companiesScraped)) {
            foreach ($companiesScraped[1] as $index => $value) {
                $companies[] = [
                    'domain' => $value,
                    'page' => 'https://yelp.com/biz/' . $value . '?sort_by=date_desc',
                    'photos' => 'https://yelp.com/biz_photos/' . $value,
                ];
            }
        }

        // slice to 1 company for testing purposes
        if ($this->limitCompanies > 0) $companies = array_slice($companies, 0, $this->limitCompanies);

//        $companies = [];
//        $companies[] = [
//            'page' => 'https://yelp.com/biz/museum-dental-suites-london?sort_by=date_desc',
//            'photos' => 'https://yelp.com/biz_photos/yarok-berlin',
//            'domain' => 'museum-dental-suites-london'
//        ];
//        $companies[] = [
//            'page' => 'https://yelp.com/biz/smilepod-london-2?sort_by=date_desc',
//            'photos' => 'https://yelp.com/biz_photos/yarok-berlin',
//            'domain' => 'smilepod-london-2'
//        ];
//        $companies[] = [
//            'page' => 'https://yelp.com/biz/38-devonshire-street-london-2?sort_by=date_desc',
//            'photos' => 'https://yelp.com/biz_photos/yarok-berlin',
//            'domain' => '38-devonshire-street-london-2'
//        ];

        return $companies;
    }

    public function parseCompanyText($url)
    {
        // get company page
        $page = $this->request($url);

        $data                                = [];

        $data['company']['site_id']          = $this->getParam('site_id');
        $data['company']['category_id']      = $this->getParam('category_id');
        $data['company']['original_url']     = $url;
        $data['company']['name']             = $this->getName($page);
        $data['company']['address']          = $this->getAddress($page);
        $data['company']['tel']              = $this->getTel($page);
        $data['company']['website']          = $this->getWebsite($page);
        $data['company']['rating']           = $this->getRating($page);
        $data['company']['price_range']      = $this->getPriceRange($page);
        $data['company']['latitude']         = $this->getLatitude($page);
        $data['company']['longitude']        = $this->getLongitude($page);
        $data['company']['domain']           = str_slug($data['company']['name']);
        $data['company']['description']      = '';
        $data['company']['last_review']      = '';
        $data['company']['url']              = "http://" . $data['company']['domain'] . "." . $this->getParam('domain') . "/";
        $data['reviews']                     = $this->getReviews($page);
        $data['hours']                       = $this->getHours($page);
        $data['options']                     = $this->getOptions($page);
        $data['company']['amount_comments']  = count($data['reviews']);
        $data['types']                       = $this->getTypes($page);

        // add meta data
        $data['company']['meta_title']       = $data['company']['name'] . " - " . $this->getParam('city')->name;
        $data['company']['meta_keywords']    = $data['company']['name'] . ", " . $this->getParam('city')->name . ", " . $this->getParam('category')->name . ", reviews of " . $data['company']['name'];
        $data['company']['meta_description'] = "Reviews of " . $data['company']['name'] . " in " . $this->getParam('city')->name . ": ";

        if (!empty($data['reviews'][0]['review'])) $data['company']['meta_description'] .= str_limit(strip_tags($data['reviews'][0]['review']), $limit = 80, $end = '...');

        return $data;
    }

    public function parseCompanyPhotos($url)
    {
        $page = $this->request($url);

        $reg = "|<img.*?class=\"photo-box-img\".*?height=\"226\".*?src=\"(.+?)/258s.jpg\".*?width=\"226\">|";
        preg_match_all($reg, $page, $matches);

        $photos = [];

        if (!empty($matches[1])) {
            foreach ($matches[1] as $photo) {
                $photos[] = $photo . "/o.jpg";
            }

            return $photos;
        }
    }

    public function processTypes($types, $company)
    {
        // get category name
        $category = Category::where('id', $this->getParam('category_id'))->first();

        // check if "options_groups" exist for such category_id (using help column "comment")
        $ifOptionGroupExists = OptionGroup::where('comment', 'Category ' . $this->getParam('category_id'))->first();

        if (!$ifOptionGroupExists) {
            // if not, create with "name" = type, "icon" = of that category, "comment" = category_id
            $optionGroup = OptionGroup::create([
                'site_id' => $this->getParam('site_id'),
                'name' => 'Type',
                'icon' => $category->icon,
                'comment' => 'Category ' . $this->getParam('category_id')
            ]);

            $optionGroupId = $optionGroup->id;

            // link option_group to category_id
            Category::find($this->getParam('category_id'))->options_groups()->attach([
                'option_group_id' => $optionGroupId
            ]);
        }
        else {
            $optionGroupId = $ifOptionGroupExists->id;
        }

        // using last_insert_id add option to "options" with "type" name and last_insert_id
        foreach ($types as $type) {
            // check if such option already exists
            $ifOptionExists = Option::where('option_group_id', $optionGroupId)->where('name', $type)->first();

            if (!$ifOptionExists) {
                $optionData = Option::create([
                    'option_group_id' => $optionGroupId,
                    'name' => $type
                ]);

                $optionId = $optionData->id;
            }
            else {
                $optionId = $ifOptionExists->id;
            }

            // add new type options to company
            Company::find($company->id)->options()->attach([
                'option_id' => $optionId
            ]);
        }
    }

    private function getName($data)
    {
        /*$reg = '|<h1.*?>(.+?)</h1>|is';*/
        $reg = "|Start your review of <strong>(.+?)</strong>|";
        preg_match_all($reg, $data, $matches);

        if (empty($matches[1][0])) {
            $reg = "|With so few reviews, your opinion of <strong>(.+?)</strong> could be huge|";
            preg_match_all($reg, $data, $matches);
        }

        if (empty($matches[1][0])) {
            $reg = "|<h2>Recommended Reviews <b>(.+?)</b></h2>|";
            preg_match_all($reg, $data, $matches);
        }

        if (empty($matches[1][0])) {
            $reg = "|<h2>Recommended Reviews <b>(.+?)</b></h2>|";
            preg_match_all($reg, $data, $matches);
        }

        if (empty($matches[1][0])) {
            $reg = "|<h2>Photos for (.+?)</h2>|";
            preg_match_all($reg, $data, $matches);
        }

        if (empty($matches[1][0])) {
            $reg = "|<h2>Photo for (.+?)</h2>|";
            preg_match_all($reg, $data, $matches);
        }

        if (empty($matches[1][0])) {
            $reg = "|Hey there trendsetter! You could be the first review for.*?<strong>(.+?)</strong>|is";
            preg_match_all($reg, $data, $matches);
        }

        if (!empty($matches[1][0])) {
            return trim(htmlspecialchars_decode($matches[1][0], ENT_QUOTES));
        }
    }

    private function getAddress($data)
    {
        $reg = '|<span itemprop="streetAddress">(.+?)</span>|';
        preg_match_all($reg, $data, $street);
        $street = !empty($street[1][0]) ? trim($street[1][0]) : '';

        $reg = '|<span itemprop="postalCode">(.+?)</span>|';
        preg_match_all($reg, $data, $postalCode);
        $postalCode = !empty($postalCode[1][0]) ? trim($postalCode[1][0]) : '';

        $reg = '|<span itemprop="addressLocality">(.+?)</span>|';
        preg_match_all($reg, $data, $locality);
        $locality = !empty($locality[1][0]) ? trim($locality[1][0]) : '';

        return $street . ", " . $postalCode . ", " . $locality;
    }

    private function getTel($data)
    {
        $reg = '|<span class="biz-phone.*?>(.+?)</span>|is';
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches);

        if (!empty($matches[1][0])) return trim($matches[1][0]);
    }

    private function getWebsite($data)
    {
        $reg = '|<a href="\/biz_redir\?url=(.+?)&.*?">|is';
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches);

        if (!empty($matches[1][0])) return urldecode(trim($matches[1][0]));
    }

    private function getRating($data)
    {
        $reg = '|<meta itemprop=\"ratingValue\" content="(.+?)">|';
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches);

        if (!empty($matches[1][0])) return urldecode(trim($matches[1][0]));
    }

    private function getPriceRange($data)
    {
        $reg = '|<span class="business-attribute price-range".*?>(.+?)</span>|';
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches);

        if (!empty($matches[1][0])) return count(trim($matches[1][0]));
    }

    private function getLatitude($data)
    {
        $reg = '|latitude&#34;: (.+?),|';
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches);

        if (!empty($matches[1][0])) return trim($matches[1][0]);
    }

    private function getLongitude($data)
    {
        $reg = '|longitude&#34;: (.+?)}|';
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches);

        if (!empty($matches[1][0])) return trim($matches[1][0]);
    }

    private function getReviews($data)
    {
        $reg = "|<p lang=\"en\">(.+?)</p>|";
        preg_match_all($reg, $data, $comments);

        $reg = "|<li class=\"user-name\">.*?<a class=\"user-display-name\".*?>(.+?)</a>.*?</li>|is";
        preg_match_all($reg, $data, $names);

        $reg = "|<meta itemprop=\"datePublished\" content=\"(.+?)\">|is";
        preg_match_all($reg, $data, $dates);

        /*
        $reg = "|<div class=\"review-wrapper\">.*?<i.*?star-img stars_(.+?)\".*?>|is";
        */
        $reg = "|<div class=\"review-wrapper\">.*?<div class=\"i-stars.*?rating-very-large\" title=\"(.+?) star rating\">|is";
        preg_match_all($reg, $data, $ratings);

//        echo "<pre>";
//        print_r($comments[1]);
//        print_r($names[1]);
//        print_r($dates[1]);
//        print_r($ratings[1]);
//        echo "</pre>";
//        exit();

        $reviews = [];
        if (!empty($comments[1]) && !empty($names[1]) && !empty($ratings[1])) {
            foreach ($comments[1] as $index => $value) {
                $name   = !empty($names[1][$index]) ? $names[1][$index] : '';
                $review = !empty($comments[1][$index]) ? $comments[1][$index] : '';
                $date   = !empty($dates[1][$index]) ? $dates[1][$index] : '';
                $rating = !empty($ratings[1][$index]) ? $ratings[1][$index] : '';

                $reviews[] = [
                    'name' => $name,
                    'review' => $review,
                    'date' => $date,
                    'rating' => $rating
                ];
            }
        }

        //echo "REVIEWS:"; print_r($reviews); exit();

        return $reviews;
    }

    private function getHours($data)
    {
        $reg = "#<th scope=\"row\">(Mon|Tue|Wed|Thu|Fri|Sat|Sun)</th>.*?<td>.*?<span class=\"nowrap\">(.+?)</span> - <span class=\"nowrap\">(.+?)</span>.*?</td>#is";
        preg_match_all($reg, $data, $matches);

        $hours = [];
        if (!empty($matches)) {
            foreach ($matches[1] as $index => $value) {
                $hours[] = [
                    'day' => strtolower($matches[1][$index]),
                    'open' => date("H:i", strtotime($matches[2][$index])),
                    'close' => date("H:i", strtotime($matches[3][$index]))
                ];
            }
        }

        return $hours;
    }

    private function getOptions($data)
    {
        $reg = "#<dl>.*?<dt class=\"attribute-key\">(.+?)</dt>.*?<dd>(.+?)</dd>.*?</dl>#is";
        preg_match_all($reg, $data, $matches);
        //echo "<pre>"; print_r($matches); echo "</pre>";

        $options = [];
        if (!empty($matches)) {
            foreach ($matches[1] as $index => $value) {
                $groupName = trim($matches[1][$index]);
                $options[$groupName][] = trim($matches[2][$index]);
            }
        }
        //print_r($options); exit();

        return $options;
    }

    public function getPagePartOfUrl($page = 1)
    {
        $page = ($page - 1) * 10;

        if ($page > 1) return "&start=" . $page;
    }

    public function getTypes($data)
    {
        $reg = "#<span class=\"category-str-list\">(.+?)</span>#is";
        preg_match_all($reg, $data, $preMatches);

        if (!empty($preMatches)) {
            $reg = "|<a.*?>(.+?)</a>|";
            preg_match_all($reg, $preMatches[1][0], $matches);

            if (!empty($matches[1])) return $matches[1];
        }
    }
}