<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\News;

class NewsController extends Controller
{
    public function show(Request $request, $slug)
    {
        $news = News::where('site_id', $request->site->id)->where('domain', $slug)->first();

        if (!$news) {
            abort(404, 'Page does not exist');
        }

        // generate meta
        $meta = [
            'title' => $news->title,
            'description' => $news->meta_description,
            'keywords' => $news->meta_keywords

        ];

        return view('news.show', [
            'news' => $news,
            'meta' => $meta
        ]);
    }
}
