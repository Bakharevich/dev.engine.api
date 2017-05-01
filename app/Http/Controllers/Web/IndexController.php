<?php

namespace App\Http\Controllers\Web;

use App\CategoryGroup;
use App\Http\Controllers\Controller;

use App\News;
use App\User;
use App\UserCompany;
use Illuminate\Http\Request;
use App\Http\Requests;
use Cache;
use Auth;
use App\Category;
use Route;
use App\Meta;


class IndexController extends Controller
{
    public function index(Request $request)
    {
        // get categories groups
        $categoriesGroups = CategoryGroup::where('site_id', $request->site->id)->where('city_id', $request->site->city_id)->get();

        // get categories
        $categories = Category::where('site_id', $request->site->id)->where('city_id', $request->site->city_id)->get();

        // get meta
        $meta = Meta::where('site_id', $request->site->id)->where('url', $_SERVER['REQUEST_URI'])->first();

        // get news
        $news = News::where('site_id', $request->site->id)->orderBy('created_at', 'DESC')->take(8)->get();

        // get random category
        $countCategories = Category::where('site_id', $request->site->id)->where('city_id', $request->site->city_id)->count();
        $rand = rand(1, $countCategories - 5);

        $companies = '';
        if (Auth::check()) {
            // check if user has any companies
            $companies = UserCompany::where('user_id', Auth::id())->first();
        }

        $categoriesRandom = Category::where('site_id', $request->site->id)->
            where('city_id', $request->site->city_id)->
            //where('length(name)')
            skip($rand)->
            take(4)->
            get();

        // arr of colours
        $colours = [
            '#ff8600',
            '#ff1749',
            '#00c963',
            '#ffc100',
            '#ba1f01'
        ];

        return view('index', [
            'categories'        => $categories,
            'categoriesRandom'  => $categoriesRandom,
            'categoriesGroups'  => $categoriesGroups,
            'meta'              => $meta,
            'news'              => $news,
            'colours'           => $colours,
            'companies'         => $companies
        ]);
    }
}
