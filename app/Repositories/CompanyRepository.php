<?php
namespace App\Repositories;

use App\CategoryCompany;
use App\Company;
use App\CompanyQuote;
use App\CompanyContact;
use App\Site;

class CompanyRepository {
    public static function find($id)
    {
        $company = Company::with('categories')->find($id);

        return $company;
    }

    public static function create($request)
    {
        // get site info
        $site = Site::find($request['site_id']);

        if (empty($request['domain'])) $request['domain'] = str_slug($request['name']);

        // set domain
        $request['url'] = "http://" . $request['domain'] . "." . $site->domain . "/";

        // check if company with such URL exists
        $ifExists = Company::where('site_id', $request['site_id'])->where('url', $request['url'])->first();

        // add company
        if ($ifExists) {
            for ($i = 1; $i <= 300; $i++) {
                // set new domain and url
                $newDomain = $request['domain'] . "-" . $i;
                $newUrl = "http://" . $newDomain . "." . $site->domain . "/";

                // check if it's free
                $isFree = Company::where('site_id', $request['site_id'])->where('url', $newUrl)->first();

                if (!$isFree) {
                    $request['domain'] = $newDomain;
                    $request['url'] = $newUrl;

                    // add company
                    $company = Company::create([
                        'category_id' => $request['category_id'],
                        'name' => $request['name'],
                        'address' => $request['name'],
                        'tel' => $request['tel'],
                        'website' => $request['website'],
                        'description' => $request['description'],
                        'price_range' => $request['price_range']
                    ]);

                    break;
                }
            }
        }
        else {
            $company = Company::create([
                'category_id' => $request['category_id'],
                'name' => $request['name'],
                'address' => $request['name'],
                'tel' => $request['tel'],
                'website' => $request['website'],
                'description' => $request['description'],
                'price_range' => $request['price_range']
            ]);
        }
        //dd($company);


        // update company position
        $company->pos = $company->id;

        // save updated data
        $company->save();

        // add to categories
        CategoryCompany::firstOrCreate([
            'category_id' => $request['category_id'],
            'company_id' => $company->id
        ]);

        return $company;
    }

    public static function update($companyId, $data)
    {
        // @todo: Update geo position when update address
        $company = Company::where('id', $companyId)->update($data);

        return $company;
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

    public static function createQuote($request)
    {
        // add quote to DB
        $quote = CompanyQuote::create([
            'company_id' => $request[''],
            'tel' => $request['tel'],
            'email' => $request['email'],
            'quote' => $request['quote'],
            'state' => !empty($request['state']) ? $request['state'] : 1
        ]);

        // send email

        return $quote;
    }
}