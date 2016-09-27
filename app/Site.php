<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [];

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }
}
