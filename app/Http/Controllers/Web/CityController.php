<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\City;

class CityController extends Controller
{
    public function show(Request $request, $domain)
    {
        $city = City::where('domain', $domain)->first();

        return view('city', [
            'city' => $city
        ]);
    }
}
