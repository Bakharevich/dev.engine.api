<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptionGroup extends Model
{
    protected $table = 'options_groups';
    protected $fillable = ['site_id', 'name', 'icon', 'comment'];

    public function options()
    {
        return $this->hasMany('App\Option');
    }
}
