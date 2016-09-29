<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Category;
use App\Company;


class CompanyController extends Controller
{
    public function show(Request $request, $companyDomain)
    {
        $company = Company::where('domain', $companyDomain)->where('site_id', $request->get('site')->id)->first();

        return view('company', [
            'company' => $company
        ]);
    }
}
