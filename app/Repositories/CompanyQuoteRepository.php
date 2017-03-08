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

        // send email

        return $quote;

        return $quote;
    }

    public function getByCategory($categoryId, $selectedOptions = [])
    {
        $query = Company::query();

        $query->join('category_company', 'category_company.company_id', '=', 'companies.id');
        $query->where('category_company.category_id', $categoryId);

        /* Selected options case */
        if (!empty($selectedOptions)) {
            $query->join('company_option', 'company_option.company_id', '=', 'companies.id');
            $query->whereIn('company_option.option_id', $selectedOptions);
        }

        $query->orderBy('is_premium', 'desc')->orderBy('pos', 'asc');

        $companies = $query->paginate(20);

        return $companies;
    }
}