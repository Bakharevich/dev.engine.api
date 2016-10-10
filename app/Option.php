<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['option_group_id', 'name'];
    /**
     * Get the companies associated with given option
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany('App\Company', "company_option", "company_id");
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category', "category_option", "category_id");
    }

    public function option_group()
    {
        return $this->hasOne('App\OptionGroup');
    }
}
