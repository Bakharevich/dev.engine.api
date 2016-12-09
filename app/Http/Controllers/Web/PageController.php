<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Page;

class PageController extends Controller
{
    public function show(Request $request, $slug)
    {
        $page = Page::where('section', $slug)->where('site_id', $request->site->id)->first();

        if (!$page) {
            abort(404, 'Unauthorized action.');
        }

        // generate meta
        $meta = [
            'title' => $page->title
        ];

        return view('page', [
            'page' => $page,
            'meta' => $meta
        ]);
    }
}
