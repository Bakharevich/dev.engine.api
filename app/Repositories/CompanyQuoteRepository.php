<?php
namespace App\Repositories;

use App\CategoryCompany;
use App\Company;
use App\CompanyQuote;
use App\Site;

class CompanyQuoteRepository {
    public static function create($request)
    {
        // add quote to DB
        $quote = CompanyQuote::create([
            'company_id' => $request['company_id'],
            'tel' => $request['tel'],
            'email' => $request['email'],
            'quote' => $request['quote'],
            'state' => !empty($request['state']) ? $request['state'] : 1
        ]);

        return $quote;
    }
}