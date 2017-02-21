<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Request;

class Menu {
    public static function get($type = '')
    {
        //return $type;
        //return self::getSimpleMenu();
    }

    public static function menu($type, $siteId, $cityId)
    {
        if ($type == 1) {
            $menu = \App\Category::select(['name', 'domain'])->
                        where('site_id', $siteId)->
                        where('city_id', $cityId)
                            ->get();
        }
        elseif ($type == 2) {
            $menu = \App\CategoryGroup::with('categories')
                ->where('site_id', $siteId)
                ->where('city_id', $cityId)
                ->get();
        }

        return $menu;
    }
}