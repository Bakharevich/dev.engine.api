<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Category;
use App\Company;

class CategoryController extends Controller
{
    public function show(Request $request, $city, $domain)
    {
        $page = $request->input('page', 1);

        $category = Category::where('domain', $domain)->where('site_id', $request->site->id)->first();

        $companies = Company::where('category_id', $category->id)->paginate(1);

        
        return view('category', [
            'category' => $category,
            'companies' => $companies,
            'page' => $page
        ]);
    }
}
