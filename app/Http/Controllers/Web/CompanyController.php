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
        //dd($company);

        /**********************
         *      SET META
         * TODO: rebuild
         **********************/
        // title
        if (!empty($company->meta_title)) {
            $metaTitle = $company->meta_title;
        }
        else {
            $metaTitle = $company->category->name_single . " " . $company->name .
                " " . trans('cities.in') . " " . trans('cities.' . $request->site->city->name . '_where');
        }

        // description
        if (!empty($company->meta_description)) {
            $metaDescription = $company->meta_description;
        }
        else {
            $metaDescription =
                $company->category->name_single . " " . $company->name .
                " " . trans('cities.in') . " " . trans('cities.' . $request->site->city->name . '_where') . ". 
                " . trans('company.meta-description-default');
        }

        // keywords
        if (!empty($company->meta_keywords)) {
            $metaKeywords = $company->meta_keywords;
        }
        else {
            $metaKeywords =
                $company->name . ", " . $company->category->name_single . ", " . trans('cities.' . $request->site->city->name)
                 . ", " . trans('company.meta-description-default');
        }

        // image
        if (!empty($company->meta_image)) {
            $metaImage = $company->meta_image;
        }
        else {
            if (!empty($company->photos[0])) {
                $metaImage = $company->photos[0]->url;
            }
            else {
                $metaImage = "";
            }
        }

        // generate meta
        $meta = [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $metaKeywords,
            'image' => $metaImage
        ];


        return view('company', [
            'company' => $company,
            'meta' => $meta
        ]);
    }
}
