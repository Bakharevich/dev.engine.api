<?php

namespace App\Http\Controllers\Api;

use App\Repositories\CompanyPhotoRepository;
use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Intervention\Image\ImageManagerStatic as Image;

class CompanyPhotoController extends Controller
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
            'title' => '',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_id' => 'required|int'
        ]);

        // get company info
        $company = \App\Repositories\CompanyRepository::find($request['company_id']);

        // get site info
        $site = \App\Site::find($company['site_id']);

        $params = $request->only(['images', 'company_id', 'site_id']);

        if ($validator->passes()) {
            if ($request->hasFile('images')) {
                $files = $request->file('images');

                foreach ($files as $file) {
                    $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                    //echo $site->media_path . 'companies/'; exit();
                    $file->move($site->media_path . 'companies/', $filename);

                    // resize and save photos
                    Image::make($site->media_path . 'companies/' . $filename)
                        ->resize(800, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })
                        ->save($site->media_path . 'companies/500/' . $filename, 75)
                        ->fit(400, null, function ($constraint) {
                        })
                        ->save($site->media_path . 'companies/500/' . $filename, 70);

                    // add to DB
                    \App\Repositories\CompanyPhotoRepository::create([
                        'company_id' => $request['company_id'],
                        'filename' => $filename
                    ]);
                }
            }

            return response()->json(['success'=>'done']);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
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
        // remove photo from DB
        CompanyPhotoRepository::destroy($id);
    }
}
