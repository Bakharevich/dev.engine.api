<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
//use App\Repositories\CompanyCategoryRepository as CompanyCategory;
use App\CompanyCategory;
use Validator;

class CompanyCategoryController extends Controller
{
    private $companyCategory;

    public function __construct(CompanyCategory $companyCategory)
    {
        $this->companyCategory = $companyCategory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ["index"];
    }

    /**
     * Display categories for specific domain
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllBySiteId(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'site_id' => 'required|integer'
        ] );

        if ( $validator->fails() ) {
            return response()->json( ['error' => $validator->errors()->all()], 406 );
        }

        $categories = CompanyCategory::with('country')->ofSiteId($request->input('site_id'))->get();

        return response()->json($categories);
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
