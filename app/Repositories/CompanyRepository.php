<?php
namespace App\Repositories;

use App\Company;
use App\Site;

class CompanyRepository {
    public static function create($request)
    {
        // get site info
        $site = Site::find($request['site_id']);

        // set domain
        $request['domain'] = str_slug($request['name']);
        $request['url'] = "http://" . $request['domain'] . "." . $site->domain . "";

        // check if company with such URL exists
        $ifExists = Company::where('site_id', $request['site_id'])->where('url', $request['url'])->first();

        if ($ifExists) {
            for ($i = 1; $i <= 50; $i++) {
                // set new domain and url
                $newDomain = $request['domain'] . "-" . $i;
                $newUrl = "http://" . $newDomain . "." . $site->domain;;

                // check if it's free
                $isFree = Company::where('site_id', $request['site_id'])->where('url', $newUrl)->first();

                if (!$isFree) {
                    $request['domain'] = $newDomain;
                    $request['url'] = $newUrl;

                    $company = Company::create($request);
                    break;
                }
            }
        }
        else {
            $company = Company::create($request);
        }

        return $company;
    }
}