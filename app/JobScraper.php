<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobScraper extends Model
{
    protected $table = 'jobs_scrapers';
    protected $fillable = [
        'site_id', 'scraper', 'category_id', 'city_id', 'url', 'pages', 'amount_executed'
    ];
}
