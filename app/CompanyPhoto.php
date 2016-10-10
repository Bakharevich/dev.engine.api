<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyPhoto extends Model
{
    protected $table = 'companies_photos';
    protected $fillable = ['company_id', 'filename', 'pos', 'url'];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
