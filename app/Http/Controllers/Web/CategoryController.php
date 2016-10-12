<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Category;
use App\Company;
use Input;
use DB;

class CategoryController extends Controller
{
    public function show(Request $request, $city, $domain)
    {
        $selectedOptions = $request->input('option', []);

        // get category with options
        $category = Category::with('options_groups.options')->where('domain', $domain)->where('site_id', $request->site->id)->first();

        // if there are selected companies, need different query
        if (!empty($selectedOptions)) {
            $companies = Company::whereHas('options', function ($query) use ($selectedOptions) {
                // if have options, get companies with them
                if (!empty($selectedOptions)) $query->whereIn('option_id', $selectedOptions);
            })->where('category_id', $category->id)->paginate(1);
        }
        else {
            $companies = Company::with('options')->where('category_id', $category->id)->paginate(20);
        }

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
