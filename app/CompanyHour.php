<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyHour extends Model
{
    protected $table = 'companies_hours';
    protected $fillable = [
        'company_id', 'day', 'open', 'close'
    ];
}
