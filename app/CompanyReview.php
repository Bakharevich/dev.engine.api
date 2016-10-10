<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyReview extends Model
{
    protected $table = 'companies_reviews';
    protected $fillable = [
        'user_id', 'company_id', 'name', 'review', 'rating'
    ];
}
