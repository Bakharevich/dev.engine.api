<?php
namespace App\Scrapers;

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

class Yelp extends Scraper implements ScraperInterface {
    private $companyRepo;

    public function __construct()
    {
        //$this->companyRepo = $companyRepo;
    }

    public function process($url)
    {
        // parse category for all companies
        $companies = $this->parseCategory($url);

        foreach ($companies as $company) {
            // check if company exists
            $checkResult = $this->checkIfExists($this->getParam('site_id'), $company['page']);
            if ($checkResult) {
                echo $company['page'] . " exists. Skipping...\n";
                continue;
            }
            
            // get text from page
            $companyText = $this->parseCompanyText($company['page']);

            // get photos from page
            $companyPhotos = $this->parseCompanyPhotos($company['photos']);

            // create company
            $companyData = $this->saveCompany($companyText['company']);

            // add reviews
            $lastReview = $this->processReviews($companyText['reviews'], $companyData);

            // download and save photos
            $lastPhoto = $this->processPhotos($companyPhotos, $companyData);

            // get options, check in database, add if not exists, connect to company
            $this->processOptions($companyText['options'], $companyData);

            // save hours
            $this->processHours($companyText['hours'], $companyData);

            // update company info according last changes
            $companyData->main_photo_url = $lastPhoto->url;
            $companyData->last_review = $lastReview;
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
                    'page' => 'https://yelp.com/biz/' . $value . '?sort_by=date_desc',
                    'photos' => 'https://yelp.com/biz_photos/' . $value,
                ];
            }
        }

        // slice to 1 company for testing purposes
        //$companies = array_slice($companies, 0, 1);

//        $companies = [];
//        $companies[] = [
//            'page' => 'https://yelp.com/biz/yarok-berlin?sort_by=date_desc',
//            'photos' => 'https://yelp.com/biz_photos/yarok-berlin'
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

    public function saveCompany($company)
    {
        //$this->companyRepo->create($company);
        return Company::create($company);
    }

    public function processReviews($reviews, $company)
    {
        $lastReview = '';

        foreach ($reviews as $review) {
            $row = $this->saveReview($review, $company);

            $lastReview = $row->review;
        }

        return $lastReview;
    }

    public function saveReview($review, $company)
    {
        $review['company_id'] = $company->id;

        return CompanyReview::create($review);
    }

    public function processOptions($options, $company)
    {
        $existGroups = [];
        $existOptions = [];

        // check if options exist. if not, add them
        foreach ($options as $group => $option) {
            // check if such group exists
            $groupExist = OptionGroup::where('name', $group)->first();

            if ($groupExist) {
                // add existing group to array
                $existGroups[$groupExist->name] = $groupExist->id;

                // check if such option exist at that group
                $optionExist = Option::where('option_group_id', $groupExist->id)->where('name', $option)->first();

                // if exists, add existing id to array
                if ($optionExist) {
                    $existOptions[] = $optionExist->id;
                }
                // if not exists, add to DB
                else {
                    $lastOption = Option::create([
                        'option_group_id' => $groupExist->id,
                        'name' => $option
                    ]);

                    $existOptions[] = $lastOption->id;
                }
            }
            else {
                // add new group
                $lastGroup = OptionGroup::create([
                    'name' => $group,
                    'icon' => ''
                ]);
                $existGroups[$lastGroup->name] = $lastGroup->id;

                // add new option
                $lastOption = Option::create([
                    'option_group_id' => $lastGroup->id,
                    'name' => $option
                ]);
                $existOptions[] = $lastOption->id;
            }
        }

        //echo "<pre>"; print_r($existGroups); print_r($existOptions);

        // add options to company
        Company::find($company->id)->options()->attach($existOptions);
    }

    public function processHours($hours, $company)
    {
        foreach ($hours as $hour)
        {
            $this->saveHours($hour, $company);
        }
    }

    public function saveHours($hour, $company)
    {
        $hour['company_id'] = $company->id;

        CompanyHour::create($hour);
    }

    public function processPhotos($photos, $company)
    {
        $photos = array_slice($photos, 0, 1);
        $photos = array_reverse($photos);

        foreach ($photos as $url) {
            $file = $this->request($url);

            $extension = File::extension($url);

            $name = uniqid() . "." . $extension;

            Image::make($file)->save($this->getParam('media_path') . "companies/" . $name)->fit(500, null, function($constraint) {
                //$constraint->aspectRatio();
                //$constraint->upsize();
                //$constraint->down
            })->save($this->getParam('media_path') . "companies/500/" . $name);

            // save photo
            $photoData = $this->savePhoto([
                'company_id' => $company->id,
                'filename' => $name,
                'url' => $this->getParam('media_url') . 'companies/500/' . $name
            ]);
        }

        return $photoData;
    }

    public function savePhoto($params)
    {
        return CompanyPhoto::create($params);
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


        if (!empty($matches[1][0])) return trim($matches[1][0]);
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
        $reg = '|<span class="business-attribute price-range">(.+?)</span>|';
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
        $reg = "|<p itemprop=\"description\" lang=\"en\">(.+?)</p>|";
        preg_match_all($reg, $data, $comments);

        $reg = "|<li class=\"user-name\">.*?<a class=\"user-display-name\".*?>(.+?)</a>.*?</li>|is";
        preg_match_all($reg, $data, $names);

        $reg = "|<meta itemprop=\"datePublished\" content=\"(.+?)\">|is";
        preg_match_all($reg, $data, $dates);

        $reg = "|<div class=\"review-content\">.*?<meta itemprop=\"ratingValue\" content=\"(.+?)\">|is";
        preg_match_all($reg, $data, $ratings);

//        echo "<pre>";
//        print_r($comments[1]);
//        print_r($names[1]);
//        print_r($dates[1]);
//        print_r($ratings[1]);
//        echo "</pre>";


        $reviews = [];
        if (!empty($comments[1]) && !empty($names[1])) {
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
                $options[$groupName] = trim($matches[2][$index]);
            }
        }

        return $options;
    }
}