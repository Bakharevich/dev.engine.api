@extends('app')

@section('content')
    <style>
        body {
            padding-top: 50px;
            background: #f0f1f2;
        }
    </style>

    <div class="text-center" style="margin-bottom: 30px; padding: 195px 0px 225px 0px; background-image: url({{ Request::get('site')->background }}); background-size: cover; background-position: left top !important;">
        <h1 style="color: #FFF; font-size: 4em; margin-bottom: 35px; text-shadow: 1px 1px #000;">Все компании в Москве</h1>

        <div style="max-width: 800px; margin: 0 auto; padding: 0px 30px 0px 30px;">
            <div class="input-group input-group-lg">
                <input type="text" class="form-control" placeholder="Например, рестораны" autofocus>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </span>
            </div>
        </div>
    </div>

    <div class="container" style="margin: 0 auto;">
        @if (count($categories) > 1)
            <div class="row">
                @foreach ($categories as $category)
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 text-center">
                        <a href="/{{ Request::get('site')->city->domain }}/{{ $category->domain }}/">
                            <div style="border: 1px solid #CCC; padding: 35px 0px 35px 0px; background: #FFF; ">
                                <div>
                                    <i class="fa {{ $category->icon }}" aria-hidden="true" style="font-size: 64px;"></i>
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
    </div>
@stop