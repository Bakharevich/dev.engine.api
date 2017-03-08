<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;

class LanguageController extends Controller
{
    /**
     * Display translations of requested keys
     *
     * @param Request $request
     * @param $lang
     * @param $keys
     */
    public function keys(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'lang' => 'required|string',
            'keys' => 'required'
        ] );

        if ( $validator->fails() ) {
            return response()->json( ['status' => 0, 'error' => $validator->errors()->all()], 406 );
        }

        $lang = $request->input('lang');
        $keys = $request->input('keys');

        // get keys
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = trans($key, [], '', $lang);
        }

        return [
            'status' => 1,
            'result' => $result
        ];
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
