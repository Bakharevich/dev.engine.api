<?php
namespace App\Repositories;

use App\Category;
use App\CategoryGroup;

class CategoryRepository {
    public function allForSite($siteId)
    {
        $groups = CategoryGroup::with('categories')->where('site_id', $siteId)->get();

        $arr = [];

        if ($groups) {
            foreach ($groups as $group) {
                // form full name
                $name = $group->name1;
                if (!empty($group->name2)) $name .= ' ' . $group->name2;

                foreach ($group->categories as $category) {
                    $arr[$name][] = [
                        'id' => $category->id,
                        'name' => $category->name
                    ];
                }

            }
        }

        return $arr;
    }
}