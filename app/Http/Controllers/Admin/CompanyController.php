<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Http\Requests\CompanyCreate;
use App\Repositories\CompanyRepository;
use App\User;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->userRepository = new \App\Repositories\UserRepository();
        $this->companyRepository = new \App\Repositories\CompanyRepository();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = $this->userRepository->getCompanies(Auth::id());

        return view('admin.company.index', ['companies' => $companies]);
    }

    public function edit(Request $request, $id)
    {
        $company = CompanyRepository::find($id);
        //dd($company);

        return view('admin.company.show', ['company' => $company]);
    }

    public function update(CompanyCreate $request, $id)
    {
        $params = $request->only([
            'name', 'address', 'tel', 'website', 'description'
        ]);

        $company = CompanyRepository::update($id, $params);

        return redirect('/admin/companies/');
    }
}
