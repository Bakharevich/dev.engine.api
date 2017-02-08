<?php
namespace App\Scrapers;

use App\CategoryCompany;
use App\Company;
use App\CompanyPhoto;
use App\CompanyReview;
use App\CompanyHour;
use App\OptionGroup;
use App\Option;

use App\Repositories\ProxyRepository;
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
        // get proxy
        $proxy = ProxyRepository::best();
        $ip   = (string) $proxy->ip;
        $port = (int) $proxy->port;
        $proxyString = $ip . ":" . $port;

        // if production, use proxy
        $curl = [];
        if (getenv('APP_ENV') == "production") {
            $curl = [
                CURLOPT_PROXY => $proxyString,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 1,
                //CURLOPT_SSLVERSION => 3,
                CURLOPT_FOLLOWLOCATION => FALSE,
                CURLOPT_TIMEOUT => 30
            ];
        }

        $client = new GuzzleHttp\Client([
            'timeout' => 30,
            'curl' => $curl
        ]);

        try {
            $res = $client->request(
                'GET',
                $url
            );

            $body = $res->getBody()->getContents();

            $this->source = $body;

            return $body;
        }
        catch (GuzzleHttp\Exception\ServerException $e) {
            echo "!!! ERROR 500: " . $e->getMessage();

            return '';
        }
        catch (GuzzleHttp\Exception\ClientException $e) {
            echo "!!! Problem with url: " . $e->getMessage();

            return '';
        }
    }

    public function checkIfExists($siteId, $scraperUnique)
    {
        $res = Company::where('site_id', $siteId)->where('scraper_unique', $scraperUnique)->first();

        if ($res) return $res;
    }

    public function checkIfCompanyExistInCategory($categoryId, $companyId)
    {
        $res = CategoryCompany::where('category_id', $categoryId)->where('company_id', $companyId)->first();

        if ($res) return $res;
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

            if (!empty($file)) {

                // get image extension
                $extension = File::extension($url);

                // use default extension as some sites returns photos without it
                if (empty($extension)) $extension = "jpg";

                // generate unique name
                $name = uniqid() . "." . $extension;

                // resize and save photos
                Image::make($file)
                    ->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save($this->getParam('media_path') . "companies/" . $name, 75)
                    ->fit(400, null, function ($constraint) {
                    })
                    ->save($this->getParam('media_path') . "companies/500/" . $name, 70);

                // add photo to DB
                $lastPhoto = CompanyPhoto::create([
                    'company_id' => $companyId,
                    'filename' => $name,
                    'url' => $this->getParam('media_url') . 'companies/500/' . $name
                ]);
            }
            else {
                $lastPhoto = false;
            }
        }

        return $lastPhoto;
    }
}