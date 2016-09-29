<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [];

    /* Relationships */
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function city()
    {
        return $this->hasOne(City::class);
    }

    public function companies()
    {
        return $this->hasMany('App\Company');
    }

    public function options()
    {
        return $this->belongsToMany('App\Option');
    }

    public function options_groups()
    {
        return $this->belongsToMany('App\OptionGroup');
    }

    /* Scopes */
    public function scopeOfSiteId($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }
}
