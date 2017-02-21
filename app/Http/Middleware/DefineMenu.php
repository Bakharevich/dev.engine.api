<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use App\Category;
use App\CategoryGroup;

class DefineMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // get site setting to understand what kind of menu should be
        $menuType = $request->site->menu_type;

        // if type == 1 (easy), just get all categories
        if ($menuType == 1) {
            $menu = Category::select(['name', 'domain'])->
                where('site_id', $request->site->id)->
                where('city_id', $request->city->id)
                ->get();
        }
        else if ($menuType == 2) {
            $menu = CategoryGroup::with('categories')
                ->where('site_id', $request->site->id)
                ->where('city_id', $request->city->id)
                ->get();
        }

        //dd($menu);

        $request->merge(compact('menu'));

        return $response;
    }
}
