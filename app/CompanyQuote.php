<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyQuote extends Model
{
    protected $table = 'companies_quotes';
    protected $fillable = ['company_id', 'tel', 'email', 'quote', 'state'];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
