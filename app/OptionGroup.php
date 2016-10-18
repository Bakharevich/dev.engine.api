<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptionGroup extends Model
{
    protected $table = 'options_groups';
    protected $fillable = ['name', 'icon', 'comment'];

    public function options()
    {
        return $this->hasMany('App\Option');
    }
}
