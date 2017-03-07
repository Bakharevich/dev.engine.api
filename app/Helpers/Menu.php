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

    public static function formatSubcategories($categories)
    {
        // define columns
        if (count($categories) <= 10) {
            $columns = 1;
            $colClass = 'col-sm-12';
            $width = '';
        }
        elseif (count($categories) > 10 && count($categories) < 20) {
            $columns = 2;
            $colClass = 'col-sm-6';
        }
        elseif (count($categories) >= 20 && count($categories) < 40) {
            $columns = 3;
            $colClass = 'col-sm-4';
        }
        elseif (count($categories) >= 40) {
            $columns = 4;
            $colClass = 'col-sm-3';
        }

        // init
        $html       = '<ul class="dropdown-menu subcategories"><div class="row custom-dropdown-' . $colClass . '">';
        $i          = 0;
        $menu       = [];

        // sort in arrays
        foreach ($categories as $category) {
            $menu[$i][] = [
                'name'      => $category->name,
                'url'       => $category->url
            ];

            // iterate
            $i++;

            // free iterator if it equals to number of columns for menu
            if ($i == $columns) {
                $i = 0;
            }
        }

        // generate html
        foreach ($menu as $col) {
            $html .= "<ul class=\"list-unstyled {$colClass}\">";

            foreach ($col as $cat) {
                $html .= "<li><a href=\"{$cat['url']}\">{$cat['name']}</a></li>";
            }

            $html .= "</ul>";
        }

        $html .= "</div></ul>";

        return $html;
    }
}