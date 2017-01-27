<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'site_id', 'city_id', 'category_group_id', 'name', 'name_single', 'description_top', 'description_bottom', 'domain', 'icon',
        'url', 'image', 'meta_image', 'meta_keywords', 'meta_description', 'meta_title', 'created_at', 'updated_at'
    ];

    /* Relationships */
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function city()
    {
        return $this->hasOne(City::class);
    }

    public function companies_()
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

    public function companies()
    {
        return $this->belongsToMany('App\Company', 'category_company', 'category_id', 'company_id');
    }

    /* Scopes */
    public function scopeOfSiteId($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }
}
