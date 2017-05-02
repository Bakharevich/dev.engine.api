<?php

namespace App\Http\Controllers\Web;

use App\CompanyReview;
use App\Http\Controllers\Controller;

use App\Repositories\CategoryRepository;
use App\Repositories\CompanyRepository;
use Illuminate\Foundation\Testing\HttpException;
use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;

use App\Category;
use App\Company;
use App\City;
use Mail;
use App\Http\Requests\CompanyCreate;


class CompanyController extends Controller
{
    public function create(Request $request, CategoryRepository $categoryRepository)
    {
        // categories
        $categories = [];

        return view('company.create', [
            'categories' => $categoryRepository->allForSite($request->site->id)
        ]);
    }

    public function store(CompanyCreate $request, CompanyRepository $companyRepository)
    {
        // prepare params
        $params = $request->only([
            'category_id', 'name', 'address', 'tel', 'website', 'description', 'price_range'
        ]);
        $params['site_id'] = $request->site->id;

        $company = CompanyRepository::create($params);
        //dd($company);

        Mail::send('emails.notification-company', ['request' => $request->all(), 'site' => $request->site, 'company' => $company], function ($m) use ($request) {
            $m->from('ilya@bakharevich.by', $request->site->domain);

            $m->to('ilya@bakharevich.by')->subject('New company at ' . $request->site->domain);
        });

        return redirect('/companies/success');
    }

    public function success(Request $request)
    {
        return view('company.success');
    }

    public function show(Request $request, $companyDomain)
    {
        // get company
        $company = Company::with('options')->with('category')->
                            with(['reviews' => function($query) {
                                $query->limit(10);
                                $query->orderBy('created_at', 'desc');
                            }])->
                            with(['photos' => function($query) {
                                $query->limit(6)->orderBy('pos');
                            }])->
                            with('hours')->
                            where('domain', $companyDomain)->where('site_id', $request->get('site')->id)->first();

        if (!$company) {
            throw new HttpException(404);
        }
//dd($company->category->city_id);
        // get city of company
        $city = City::where('id', $company->category->city_id)->first();
        //dd($city);
        if (!$city) {
            throw new HttpException(404);
        }
//        dd($city);
        $request->merge(compact('city'));

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
                " - " . $city->name;
        }

        // description
        if (!empty($company->meta_description)) {
            $metaDescription = $company->meta_description;
        }
        else {
            $metaDescription =
                $company->category->name_single . " " . $company->name .
                " - " . $city->name . ". 
                " . trans('company.meta-description-default');
        }

        // keywords
        if (!empty($company->meta_keywords)) {
            $metaKeywords = $company->meta_keywords;
        }
        else {
            $metaKeywords =
                $company->name . ", " . $company->category->name_single . ", " . $city->name
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

    public function photos(Request $request, $companyDomain)
    {
        // get company
        $company = Company::with('category')->
                            with(['photos' => function($query) {
                                //$query->limit(6);
                            }])->
                            where('domain', $companyDomain)->where('site_id', $request->get('site')->id)->first();

        // title
        $metaTitle = trans('company.photos') . ' - ' . $company->category->name_single . " " . $company->name;

        $metaDescription =
            $company->category->name_single . " " . $company->name .
            " " . trans('cities.in') . " " . trans('cities.' . $request->site->city->name . '_where') . ". 
                " . trans('company.meta-description-default');

        $metaKeywords =
            "фотографии, " . $company->name . ", " . $company->category->name_single . ", " . trans('cities.' . $request->site->city->name)
            . ", " . trans('company.meta-description-default');

        // generate meta
        $meta = [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $metaKeywords
        ];

        return view('company.photos', [
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
        $metaTitle = trans('company.reviews') . ' - ' . $company->category->name_single . " " . $company->name;

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
        $company = Company::select('id', 'name', 'address', 'tel')->where('domain', $companyDomain)->where('site_id', $request->get('site')->id)->first();

        $review = CompanyReview::create([
            'service_id' => 0,
            'company_id' => $company['id'],
            'name' => $request->input('name'),
            'rating' => $request->input('rating'),
            'review' => $request->input('review')
        ]);

        Mail::send('emails.notification-comment', ['request' => $request->all(), 'site' => $request->site, 'company' => $company], function ($m) use ($request) {
            $m->from('ilya@bakharevich.by', $request->site->domain);

            $m->to('ilya@bakharevich.by')->subject('New comment at ' . $request->site->domain);
        });

        // set flash message
        $request->session()->flash('message', 'Комментарий успешно добавлен');

        return response()->redirectTo($company->url);
    }
}
