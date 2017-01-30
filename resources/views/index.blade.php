@extends('app')

@section('content')
    <style>
        .index-bg {

            background: linear-gradient(
                to bottom,
                rgba(0, 0, 0, 0),
                rgba(0, 0, 0, 0.4)
        ), url({{ Request::get('site')->media_url }}/backgrounds/index.jpg);

            background-size: cover;
            background-position: left top !important;
            /*-webkit-filter: blur(5px);  -moz-filter: blur(5px);  -o-filter: blur(5px);  -ms-filter: blur(5px);  filter: blur(5px);*/
            padding: 30px 0px 225px 0px;
            margin-bottom: 30px;
        }
        .index-bg {


        }
        body {
            /*padding-top: 50px;*/
            background: #f0f1f2;
        }
        .btn-white {
            background: none;
            color: #FFF;
            border: 2px solid #FFF;
            font-weight: bold;
        }
        .no-border {
            border: 0px !important;
        }
        I {
            -webkit-transition: color 0.8s ease;
            -moz-transition: color 0.8s ease;
            -o-transition: color 0.8s ease;
            transition: color 0.8s ease;
        }
    </style>

    <div style="padding-bottom: 10px;">
        <div class="index-bg text-center">
            <div class="container" style="max-width: 900px; padding-bottom: 100px;">
                <div class="col-xs-6 text-left">
                    <!-- Single button -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-white no-border dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-map-marker" aria-hidden="true"></i> &nbsp;
                            @foreach (Request::get('cities') as $city)
                                @if ($city->id == Request::get('site')->city_id)
                                    {{ $city->name }}
                                    @break
                                @endif
                            @endforeach
                        </button>
                        <ul class="dropdown-menu">
                            @foreach (Request::get('cities') as $city)
                                <li><a href="#">{{ $city->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xs-6 text-right">
                    @if (!Auth::check())
                        <a href="/login" class="btn btn-default btn-white no-border">{{ trans('common.login') }}</a>
                        <a href="/register" class="btn btn-default btn-white">{{ trans('common.registration') }}</a>
                    @else
                        <a href="/logout">{{ trans('common.logout') }}</a>
                    @endif
                </div>
            </div>

            <h1 style="color: #FFF; font-size: 4em; margin-bottom: 35px; text-shadow: 1px 1px #000;">{{ Request::get('site')->name }}</h1>

            <div style="max-width: 900px; margin: 0 auto; padding: 0px 30px 0px 30px; margin-bottom: 20px;">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" placeholder="{{ trans('common.search_field_value') }}" id="site-search" autofocus>

                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </span>
                </div>
            </div>

            <div style="padding: 0px 15px 0px 15px;">
                @foreach($categoriesRandom as $cat)
                    @if ($cat->icon)
                        <i class="{{ $cat->icon }}" aria-hidden="true" style="color: #FFF; margin-right: 2px;"></i>
                    @else
                        <i class="fa fa-chevron-circle-right" aria-hidden="true" style="color: #FFF; margin-right: 2px;"></i>
                    @endif

                    <a href="{{ $cat->url }}" style="color: #FFF;font-weight: bold;">{{ $cat->name }}</a>
                    &nbsp;&nbsp;&nbsp;
                @endforeach
            </div>
        </div>

        <div class="container" style="margin: 0 auto; padding-top: 20px;">
            @if (count($categories) > 0)
                <div class="row" style="margin-bottom: 20px;">
                    @foreach ($categories as $category)
                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 text-center" style="margin-bottom: 25px; ">
                            <a href="{{ $category->url }}" class="index-link-category" style="line-height: 1.2em; color: #1a65a5;" data-colour="<?php echo \App\Helpers\Helper::getRandom($colours) ?>">
                                <div style="border: 1px solid #CCC; background: #FFF; border-radius: 5px; box-shadow: 0 1px 3px rgba(48, 53, 64, .3);">
                                    <div style="margin-bottom: 10px; height: 110px; border-bottom: 1px solid #e4e4e4; padding: 20px 0px 20px 0px; margin: 0 auto;" class="text-center">
                                        @if (!empty($category->icon))
                                            <i class="{{ $category->icon }}" aria-hidden="true" style="font-size: 64px; color: #8a8f9a;"></i>
                                        @else
                                            <i class="fa fa-chevron-circle-right" aria-hidden="true" style="font-size: 64px; color: #8a8f9a;"></i>
                                        @endif
                                    </div>
                                    <div style="height: 65px; padding: 0px 10px 0px 10px; font-weight: bold; padding-top: 10px;">
                                        {{ $category->name }}
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                {{ trans('index.index-no-categories') }}
            @endif

            @if (count($news) > 0)
                <h2>{{ trans('index.index-news') }}</h2>

                <div class="row index-news">
                    @foreach ($news as $post)
                    <div class="col-md-3">
                        <div class="index-news-block">
                            <a href="{{ $post->url }}">
                                @if ($post->photo)
                                <img src="{{ $post->photo }}" class="img-responsive" />
                                @endif

                                <div class="index-news-block-description">
                                    <p class="title"><b>{{ $post->title }}</b></p>

                                    <p>{{ str_limit($post->description, 100) }}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- /News -->
            @endif

            <div class="text-center" style="font-size: 0.85em; line-height: 1.3em; color: #666;">
                {!! \App\Repositories\PageRepository::getContent(Request::get('site')->id, 'main-about')  !!}
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function(){
            var returnColour = '';

            $(".index-link-category").hover(function(){
                var colour = $(this).attr('data-colour');
                returnColour = $(this).find('I').css('color');

                $(this).find('I').css('color', colour);
            }, function() {
                $(this).find('I').css('color', returnColour);
            });
        })
    </script>
@stop