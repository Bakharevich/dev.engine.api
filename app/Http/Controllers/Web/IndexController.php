<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use Cache;
use App\Category;
use Route;
use App\Meta;


class IndexController extends Controller
{
    public function index(Request $request)
    {
        // get categories
        $categories = Category::where('site_id', $request->site->id)->get();

        // get meta
        $meta = Meta::where('site_id', $request->site->id)->where('url', $_SERVER['REQUEST_URI'])->first();

        return view('index', [
            'categories' => $categories,
            'meta' => $meta
        ]);
    }
}
