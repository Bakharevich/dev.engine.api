<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\News;
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

        // get news
        $news = News::where('site_id', $request->site->id)->orderBy('created_at', 'DESC')->take(8)->get();

        return view('index', [
            'categories' => $categories,
            'meta' => $meta,
            'news' => $news
        ]);
    }
}
