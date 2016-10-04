<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class TestController extends Controller
{
    public function index()
    {
        DB::enableQueryLog();
        echo "<pre>";print_r(DB::getQueryLog()); echo "</pre>";
    }
}
