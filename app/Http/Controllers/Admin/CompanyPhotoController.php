<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\CompanyPhotoRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepository;

class CompanyPhotoController extends Controller
{
    public function edit(Request $request, $companyId)
    {
        $company = CompanyRepository::find($companyId);
        $photos = CompanyPhotoRepository::getByCompanyId($company->id);

        return view('admin.company.photos', [
            'company' => $company,
            'photos' => $photos
        ]);
    }

    public function create(Request $request, $companyId)
    {

    }
}
