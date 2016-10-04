<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryGroup extends Model
{
    protected $table = 'categories_groups';

    public function categories()
    {
        return $this->hasMany('App\Category');
    }
}
