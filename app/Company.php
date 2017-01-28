<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    protected $guarded = ['id'];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * Get the options associated with given company
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function options()
    {
        return $this->belongsToMany('App\Option')->withTimestamps();
    }

    public function photos()
    {
        return $this->hasMany('App\CompanyPhoto');
    }

    public function reviews()
    {
        return $this->hasMany('App\CompanyReview');
    }

    public function hours()
    {
        return $this->hasMany('App\CompanyHour');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_company', 'company_id', 'category_id');
    }
}
