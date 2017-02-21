<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Category;
use App\Company;
use App\City;
use Input;
use DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->companyRepository = new \App\Repositories\CompanyRepository();
    }

    public function show(Request $request, $cityDomain, $domain)
    {
        $selectedOptions = $request->input('option', []);

        // get category with options
        $category = Category::with('options_groups.options')->
            where('domain', $domain)->
            where('city_id', $request->city->id)->
            first();
        if (!$category) {
            throw new HttpException(404);
        }

        // get companies from repo
        $companies = $this->companyRepository->getByCategory($category->id, $selectedOptions);

        // generate meta
        $meta = [
            'title' => $category->meta_title,
            'description' => $category->meta_description,
            'keywords' => $category->meta_keywords,
            'image' => $category->meta_image
        ];

        return view('category', [
            'category' => $category,
            'companies' => $companies,
            'selectedOptions' => $selectedOptions,
            'meta' => $meta
        ]);
    }
}
