<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use DB;
use Mail;

class CompanyQuoteController extends Controller
{
    public function __construct()
    {
        $this->companyQuoteRepository = new \App\Repositories\CompanyQuoteRepository();
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
        $validator = Validator::make($request->all(), [
            'company_id'    => 'required|integer',
            'quote'         => 'required|string',
            'tel'           => 'required|string'
        ]);

        if ( $validator->fails() ) {
            return response()->json( ['status' => 0, 'error' => $validator->errors()->all()], 406 );
        }

        // add quote
        $quote = \App\Repositories\CompanyQuoteRepository::create($request);

        // get company
        $company = \App\Company::find($request['company_id']);

        // get site
        $site = \App\Site::find($company->site_id);

        // send email
        Mail::send('emails.notification-company-quote', ['request' => $request, 'site' => $site, 'company' => $company], function ($m) use ($request, $site) {
            $m->from('ilya@bakharevich.by', $site->domain);
            $m->cc('bond@chatoff.by', 'Natalia Bondarenko');
            $m->cc('nasilovskaya@chatoff.by', 'Anastasia Nasilovskaya');
            $m->to('ilya@bakharevich.by')->subject('New quote at ' . $site->domain);
        });

        return [
            'status' => 1,
            'result' => $quote
        ];
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
