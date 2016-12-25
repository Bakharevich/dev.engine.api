@extends('app')

@section('content')
    <style>
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
        body {
            padding-top: 50px;
            background: #f0f1f2;
        }
    </style>

    <div style="padding-bottom: 10px;">
        <div class="index-bg text-center">
            <h1 style="color: #FFF; font-size: 4em; margin-bottom: 35px; text-shadow: 1px 1px #000;">{{ Request::get('site')->headline }}</h1>

            <div style="max-width: 800px; margin: 0 auto; padding: 0px 30px 0px 30px;">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" placeholder="{{ trans('common.search_field_value') }}" id="site-search" autofocus>

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