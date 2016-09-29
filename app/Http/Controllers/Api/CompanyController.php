<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;

class CompanyController extends Controller
{
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

        if ( $validator->fails() ) {
            return response()->json( ['error' => $validator->errors()->all()], 406 );
        }

        // set page values for paginator
        $request->page = $request->input('page', 1);

        // get companies for category
        $companies = Company::where('category_id', $request->input('category_id'))->paginate(2);

        return response()->json([
            $companies
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
