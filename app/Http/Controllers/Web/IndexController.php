<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use Cache;
use App\Category;
use Route;


class IndexController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('site_id', $request->site->id)->get();

        return view('index', [
            'categories' => $categories
        ]);
    }
}
