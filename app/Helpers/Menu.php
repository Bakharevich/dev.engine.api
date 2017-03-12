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

    public static function formatSubcategories($categories, $params)
    {
        // define columns if there is no param
        if (empty($params['columns'])) {
            if (count($categories) <= 10) {
                $columns = 1;
            } elseif (count($categories) > 10 && count($categories) < 20) {
                $columns = 2;
            } elseif (count($categories) >= 20 && count($categories) < 40) {
                $columns = 3;
            } elseif (count($categories) >= 40) {
                $columns = 4;
            }
        }
        else {
            $columns = $params['columns'];
        }

        // define class for number of columns
        $class = [
            1 => 'col-sm-12',
            2 => 'col-sm-6',
            3 => 'col-sm-4',
            4 => 'col-sm-3',
        ];

        // init
        $html       = '<ul class="' . $params['ulMainClass'] . '"><div class="row custom-dropdown-' . $class[$columns] . '">';
        $i          = 0;
        $menu       = [];

        // sort in arrays
        foreach ($categories as $category) {
            $menu[$i][] = [
                'name'      => $category->name,
                'url'       => $category->url,
                'icon'      => !empty($category->icon) ? $category->icon : ''
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
            $html .= "<ul class=\"list-unstyled {$class[$columns]}\">";

            foreach ($col as $cat) {
                // if icon param is enabled
                $icon = '';

                if (!empty($params['icon'])) {
                    if (!empty($cat['icon'])) {
                        $icon = "<span class='index-modal-menu-icon {$cat['icon']}' style='width: 17px;'></span>";
                    }
                    else {
                        $icon = "<i class='fa fa-chevron-circle-right'></i>";
                    }
                }

                $html .= "<li>{$icon} <a href=\"{$cat['url']}\">{$cat['name']}</a></li>";
            }

            $html .= "</ul>";
        }

        $html .= "</div></ul>";

        return $html;
    }
}