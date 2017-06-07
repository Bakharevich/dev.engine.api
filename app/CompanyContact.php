<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyContact extends Model
{
    protected $table = 'companies_contacts';
    protected $fillable = ['company_id', 'name', 'surname', 'position', 'tel', 'email'];
}
