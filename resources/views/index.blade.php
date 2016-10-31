@extends('app')

@section('content')
    <style>
        body {
            padding-top: 50px;
            background: #f0f1f2;
        }
        A {
            /*color: #00928c;*/
        }
        .index-link-category:hover {
            text-decoration: none;
        }
        .navbar-default {
            background: #f53118;

        }
        .navbar-default A {
            color: #FFF !important;
        }
        .blur {
            -webkit-filter: blur(10px); filter: blur(10px);
        }
        .index-bg {
            background: linear-gradient(
                to bottom,
                rgba(0, 0, 0, 0),
                rgba(0, 0, 0, 0.6)
        ), url({{ Request::get('site')->media_url }}/backgrounds/index.jpg);

            background-size: cover;
            background-position: left top !important;
            /*-webkit-filter: blur(5px);  -moz-filter: blur(5px);  -o-filter: blur(5px);  -ms-filter: blur(5px);  filter: blur(5px);*/
            padding: 195px 0px 225px 0px;
            margin-bottom: 30px;
        }
        .index-bg {


        }
    </style>

    <div style="padding-bottom: 10px;">
        <div class="index-bg text-center">
            <h1 style="color: #FFF; font-size: 4em; margin-bottom: 35px; text-shadow: 1px 1px #000;">{{ Request::get('site')->headline }}</h1>

            <div style="max-width: 800px; margin: 0 auto; padding: 0px 30px 0px 30px;">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" placeholder="{{ trans('common.search_field_value') }}" autofocus>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </span>
                </div>
            </div>
        </div>

        <div class="container" style="margin: 0 auto; padding-top: 20px;">
            @if (count($categories) > 0)
                <div class="row" style="margin-bottom: 20px;">
                    @foreach ($categories as $category)
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 text-center" style="margin-bottom: 25px; ">
                            <a href="{{ $category->url }}" class="index-link-category">
                                <div style="border: 1px solid #CCC; padding: 35px 0px 35px 0px; background: #FFF; border-radius: 5px; box-shadow: 4px; box-shadow: 0 1px 3px rgba(48, 53, 64, .3);">
                                    <div>
                                        <i class="{{ $category->icon }}" aria-hidden="true" style="font-size: 64px;"></i>
                                    </div>

                                    {{ $category->name }}
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                Нет категорий.
            @endif

            <div class="text-center" style="font-size: 0.85em; line-height: 1.3em; color: #666;">
                <p>Yelpster.net is the easiest way to find local businesses in any city. We take a Web 2.0 approach to our site, where members run the show as far as sharing, reviewing and communicating is concerned.</p>
                <p>We truly believe that quality of customer service can definitely increase or decrease companies rating. Our mission is help people to find right place and force companies to become better.</p>
            </div>
        </div>
    </div>
@stop