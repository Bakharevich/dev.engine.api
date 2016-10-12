<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Testing\HttpException;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Category;
use App\Company;


class CompanyController extends Controller
{
    public function show(Request $request, $companyDomain)
    {
        // get company
        $company = Company::with('options')->with('category')->with('reviews')->
                            with(['photos' => function($query) {
                                $query->limit(6);
                            }])->
                            with('hours')->
                            where('domain', $companyDomain)->where('site_id', $request->get('site')->id)->first();

        // generate meta
        $meta = [
            'title' => $company->meta_title,
            'description' => $company->meta_description,
            'keywords' => $company->meta_keywords,
            'image' => $company->meta_image
        ];


        return view('company', [
            'company' => $company,
            'meta' => $meta
        ]);
    }
}
