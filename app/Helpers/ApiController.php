<?php

namespace App\Helpers;

use App\Helpers\ApiContract;
use GuzzleHttp;

class ApiController implements ApiContract
{
    private $domain;
    private $client;

    public function __construct($domain)
    {
        $this->domain = $domain;
        $this->client = new GuzzleHttp\Client();
    }

    public function getSites()
    {
        $res = $this->client->request(
            'GET',
            env('API_URL') . '/site');

        $data = GuzzleHttp\json_decode($res->getBody());

        return $data;
        //echo $res->getStatusCode();
        // "200"
        //echo $res->getHeader('content-type');
        // 'application/json; charset=utf8'
        //echo $res->getBody();
        //return response()->json([1], 200);
    }

    public function getCategoriesByDomain()
    {

    }
}