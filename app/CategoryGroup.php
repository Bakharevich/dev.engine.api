<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryGroup extends Model
{
    protected $table = 'categories_groups';
    protected $fillable = [
        'site_id', 'city_id', 'name1', 'name2', 'created_at', 'updated_at'
    ];

    public function categories()
    {
        return $this->hasMany('App\Category');
    }
}
