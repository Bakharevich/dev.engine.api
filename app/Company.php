<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [];

    public function category()
    {
        return $this->hasOne(CompanyCategory::class, 'id', 'category_id');
    }
}
