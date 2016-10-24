<?php
namespace App\Scrapers;

use App\Company;
use App\CompanyPhoto;
use App\CompanyReview;
use App\CompanyHour;
use App\OptionGroup;
use App\Option;

use GuzzleHttp;
use Intervention\Image\ImageManagerStatic as Image;
use File;
use DB;
use Str;

class Scraper  {
    public $source;

    public function __construct()
    {
        $this->source = '';
    }

    public function request($url, $params = [])
    {
        $client = new GuzzleHttp\Client();

        $res = $client->request(
            'GET',
            $url
            //['proxy' => 'http://RUS185759:iaNDMQh6b2@146.185.201.243:8080']
        );

        $file = $res->getBody()->getContents();

        $this->source = $file;

        return $file;
    }

    public function checkIfExists($siteId, $scraperUnique)
    {
        $res = Company::where('site_id', $siteId)->where('scraper_unique', $scraperUnique)->first();

        if ($res) return true;
    }

    public function setParam($key, $content)
    {
        $this->params[$key] = $content;
    }
    
    public function setParams($params)
    {
        $this->params = $params;
    }
    
    public function getParam($key)
    {
        return $this->params[$key];
    }

    public function processCompany($siteId, $company)
    {
        // check if company with such URL exists
        $ifExists = Company::where('site_id', $siteId)->where('url', $company['url'])->first();

        if ($ifExists) {
            // check if such data unique
            for ($i = 1; $i <= 50; $i++) {
                // set new domain and url
                $newDomain = $company['domain'] . "-" . $i;
                $newUrl = "http://" . $newDomain . "." . $this->getParam('domain') . "/";;

                // check if it's free
                $isFree = Company::where('site_id', $siteId)->where('url', $newUrl)->first();

                if (!$isFree) {
                    $company['domain'] = $newDomain;
                    $company['url'] = $newUrl;

                    return Company::create($company);
                    break;
                }
            }
        }
        else {
            return Company::create($company);
        }
    }

    public function processHours($companyId, $hours)
    {
        $insert = [];

        foreach ($hours as $hour) {
            $insert[] = [
                'company_id' => $companyId,
                'day' => $hour['day'],
                'open' => $hour['open'],
                'close' => $hour['close']
            ];
        }

        return CompanyHour::insert($insert);
    }

    public function processReviews($companyId, $serviceId, $reviews)
    {
        $insert = []; $lastReview = '';

        foreach ($reviews as $review) {
            $insert[] = [
                'company_id' => $companyId,
                'service_id' => $serviceId,
                'name' => $review['name'],
                'review' => $review['review'],
                'created_at' => $review['date'],
                'rating' => $review['rating']
            ];

            $lastReview = $review['review'];
        }

        CompanyReview::insert($insert);

        return $lastReview;
    }

    public function processOptions($siteId, $companyId, $options)
    {
        $existGroups = [];
        $existOptions = [];

        // check if options exist. if not, add them
        foreach ($options as $group => $option) {
            // check if such group exists
            $groupExist = OptionGroup::where('site_id', $siteId)->where('name', $group)->first();

            if ($groupExist) {
                // add existing group to array
                $existGroups[$groupExist->name] = $groupExist->id;

                // check if such option exist at that group
                foreach ($option as $opt) {
                    $optionExist = Option::where('option_group_id', $groupExist->id)->where('name', $opt)->first();

                    // if exists, add existing id to array
                    if ($optionExist) {
                        $existOptions[] = $optionExist->id;
                    }
                    // if not exists, add to DB
                    else {
                        $lastOption = Option::create([
                            'option_group_id' => $groupExist->id,
                            'name' => $opt
                        ]);

                        $existOptions[] = $lastOption->id;
                    }
                }
            }
            else {
                // add new group
                $lastGroup = OptionGroup::create([
                    'site_id' => $siteId,
                    'name' => $group,
                    'icon' => ''
                ]);
                $existGroups[$lastGroup->name] = $lastGroup->id;

                // process array of options
                foreach ($option as $opt) {
                    // add new option
                    $lastOption = Option::create([
                        'option_group_id' => $lastGroup->id,
                        'name' => $opt
                    ]);
                    $existOptions[] = $lastOption->id;
                }
            }
        }

        // add options to company
        Company::find($companyId)->options()->attach($existOptions);
    }

    public function processPhotos($companyId, $photos)
    {
        $photos = array_slice($photos, 0, $this->limitPhotos);
        $photos = array_reverse($photos);

        foreach ($photos as $url) {
            $file = $this->request($url);

            // get image extension
            $extension = File::extension($url);

            // generate unique name
            $name = uniqid() . "." . $extension;

            // resize and save photos
            Image::make($file)
                ->save($this->getParam('media_path') . "companies/" . $name, 75)
                ->fit(500, null, function($constraint) {})
                ->save($this->getParam('media_path') . "companies/500/" . $name, 75);

            // add photo to DB
            $lastPhoto = CompanyPhoto::create([
                'company_id' => $companyId ,
                'filename' => $name,
                'url' => $this->getParam('media_url') . 'companies/500/' . $name
            ]);
        }

        return $lastPhoto;
    }
}