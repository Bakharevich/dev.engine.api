<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    protected $fillable = [
        'ip', 'port', 'is_enabled', 'amount_used', 'created_at', 'updated_at'
    ];
}
