<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryCompany extends Model
{
    protected $table = 'category_company';
    protected $fillable = [
        'company_id', 'category_id', 'created_at', 'updated_at'
    ];
}
