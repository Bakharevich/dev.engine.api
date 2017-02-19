<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Page;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Mail;

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

    public function contact(Request $request)
    {

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'email|required',
                'comment' => 'required',
            ]);

            if ($validator->fails()){
                return Redirect::back()->withErrors($validator->errors())->withInput($request->all());
            }

            Mail::send('emails.contact', ['request' => $request->all(), 'site' => $request->site], function ($m) use ($request) {
                $m->from('ilya@bakharevich.by', $request->site->domain);
                $m->replyTo($request->input('email'), $request->input('name'));

                $m->to('ilya@bakharevich.by')->subject('Message from ' . $request->site->domain);
            });

            // set flash message
            $request->session()->flash('message', 1);

            return Redirect::back();
        }

        // get page
        $page = Page::where('section', 'contact')->where('site_id', $request->site->id)->first();

        return view('contact', [
            'page' => $page
        ]);
    }
}
