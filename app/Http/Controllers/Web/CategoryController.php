<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Category;
use App\Company;
use Input;

class CategoryController extends Controller
{
    public function show(Request $request, $city, $domain)
    {
        $selectedOptions = $request->input('option', []);
        
        // get category with options
        $category = Category::with('options_groups.options')->where('domain', $domain)->where('site_id', $request->site->id)->first();

        // get company
        $companies = Company::whereHas('options', function($query) use ($selectedOptions) {
            // if have options, get companies with them
            if (!empty($selectedOptions)) $query->whereIn('option_id', $selectedOptions);
        })->where('category_id', $category->id)->paginate(1);

        // get options


        return view('category', [
            'category' => $category,
            'companies' => $companies,
            'selectedOptions' => $selectedOptions
        ]);
    }
}
