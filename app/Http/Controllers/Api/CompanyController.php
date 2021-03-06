<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use DB;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->companyRepository = new \App\Repositories\CompanyRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getAllByCategoryId(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'category_id' => 'required|integer',
            'page' => 'integer',
            'options' => ''
        ] );
        //DB::enableQueryLog();

        $selectedOptions = $request->input('option', []);
        //echo "<pre>"; print_r($selectedOptions); exit();

        if ( $validator->fails() ) {
            return response()->json( ['error' => $validator->errors()->all()], 406 );
        }

        // set page values for paginator
        $request->page = $request->input('page', 1);

        // get companies from repo
        $companies = $this->companyRepository->getByCategory($request->input('category_id'), $selectedOptions);

        return response()->json(
            $companies
        );
    }

    public function getHtmlByCategoryId(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'category_id' => 'required|integer'
        ] );

        if ( $validator->fails() ) {
            return response()->json( ['error' => $validator->errors()->all()], 406 );
        }

        $selectedOptions = $request->input('option', []);

        // get category with options
        $category = Category::with('options_groups.options')->
                        where('id', $request->input('category_id'))->
                        first();

        // get companies from repo
        $companies = $this->companyRepository->getByCategory($request->input('category_id'), $selectedOptions);

        // set custom link for pagination
        $companies->setPath('');

        return view('category.companies', [
            'category' => $category,
            'companies' => $companies,
            'selectedOptions' => $selectedOptions
        ]);
    }

    public function getByDomain(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'site_id' => 'required|integer',
            'domain' => 'required|string'
        ] );

        if ( $validator->fails() ) {
            return response()->json( ['error' => $validator->errors()->all()], 406 );
        }

        // get company
        $data = Company::with('category')->where('site_id', $request->input('site_id'))->where('domain', $request->input('domain'))->first();

        return response()->json(
            $data
        );
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_id' => 'required|integer',
            'keyword' => 'required|string'
        ]);

        $result = [];

        // first check if such categories exist
        $categories = Category::select()->
                                with('city')->
                                where('name', 'like', '%' . $request->input('keyword') . '%')->
                                where('site_id', $request->input('site_id'))->
                                limit(5)->
                                get();
        //dd($categories);

        if ($categories) {
            foreach ($categories as $category) {
                $result[] = [
                    'id' => $category->id,
                    'type' => 'category',
                    'title' => $category->name,
                    'url' => $category->url,
                    'address' => $category->city->name
                ];
            }
        }

        // then check if such companies exist
        $companies = Company::select()->
                              with('category')->
                              where('name', 'like', '%' . $request->input('keyword') . '%')->
                              where('site_id', $request->input('site_id'))->
                              limit(10)->
                              get();
//dd($companies);
        if ($companies) {
            foreach ($companies as $company) {
                $address = $company->address;

                if (strlen($address) > 50) {
                    $address = mb_substr($address, 0, 50) . "...";
                }

                $result[] = [
                    'id' => $company->id,
                    'type' => 'company',
                    'title' => $company->name,
                    'category' => $company->category->name,
                    'url' => $company->url,
                    'address' => $address
                ];
            }
        }

        return response()->json($result, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
