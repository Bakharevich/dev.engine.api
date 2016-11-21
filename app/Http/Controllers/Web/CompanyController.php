<?php

namespace App\Http\Controllers\Web;

use App\CompanyReview;
use App\Http\Controllers\Controller;

use Illuminate\Foundation\Testing\HttpException;
use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;

use App\Category;
use App\Company;


class CompanyController extends Controller
{
    public function show(Request $request, $companyDomain)
    {
        // get company
        $company = Company::with('options')->with('category')->
                            with(['reviews' => function($query) {
                                $query->limit(10);
                                $query->orderBy('created_at', 'desc');
                            }])->
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

    public function reviewsGet(Request $request, $companyDomain)
    {
        // get company
        $company = Company::with('category')->
                            with(['reviews' => function($query) {
                                $query->orderBy('created_at', 'desc');
                            }])->
                            where('domain', $companyDomain)->where('site_id', $request->get('site')->id)->first();

        // title
        $metaTitle = 'Отзывы ' . $company->category->name_single . " " . $company->name;

        $metaDescription =
            $company->category->name_single . " " . $company->name .
            " " . trans('cities.in') . " " . trans('cities.' . $request->site->city->name . '_where') . ". 
                " . trans('company.meta-description-default');

        $metaKeywords =
            $company->name . ", " . $company->category->name_single . ", " . trans('cities.' . $request->site->city->name)
            . ", " . trans('company.meta-description-default');

        // generate meta
        $meta = [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $metaKeywords
        ];

        return view('company.reviews', [
            'company' => $company,
            'meta' => $meta
        ]);
    }

    public function reviewsPost(Request $request, $companyDomain)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'rating' => 'int|required',
            'review' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors()->all(), 406);
        }

        // get company
        $company = Company::select('id')->where('domain', $companyDomain)->where('site_id', $request->get('site')->id)->first();

        CompanyReview::create([
            'service_id' => 0,
            'company_id' => $company['id'],
            'name' => $request->input('name'),
            'rating' => $request->input('rating'),
            'review' => $request->input('review')
        ]);

        // set flash message

        return response()->redirectTo($company->url);
    }
}
