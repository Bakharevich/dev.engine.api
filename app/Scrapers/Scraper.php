<?php
namespace App\Scrapers;

use App\Company;
use GuzzleHttp;
use DB;

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
    
    public function setParams($params)
    {
        $this->params = $params;
    }
    
    public function getParam($key)
    {
        return $this->params[$key];
    }
}