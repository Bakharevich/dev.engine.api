@extends('app')

@section('content')
    <div style="background-color: #f5f5f5; font-size: 0.85em; border-bottom: 1px solid #e7e7e7;">
        <div class="container">
            <ol class="breadcrumb" style="margin-bottom: 0px;">
                <li><a href="http://{{ Request::get('site')->domain }}/">{{ trans('common.btn_home') }}</a></li>
                <li><a href="http://{{ Request::get('site')->domain }}/{{ Request::get('site')->city->domain }}">{{ Request::get('site')->city->name }}</a></li>
                <li><a href="{{ $company->category->url }}">{{ $company->category->name }}</a></li>
                <li><a href="{{ $company->url }}">{{ $company->name }}</a></li>
                <li class="active">{{ trans('company.photos') }} {{ $company->name }}</li>
            </ol>

            <div class="col-md-12">
                <h1 style="margin-top: 0; ">{{ trans('company.photos') }} {{ $company->name }}</h1>
            </div>
        </div>
    </div>

    <div style="background: #FFF; padding-top: 25px;">
        <div class="container" style="margin: 0 auto;">
            <div class="col-sm-8">
                <div class="row">
                    @foreach ($company->photos as $index => $photo)
                        <div class="col-md-3 col-sm-3 col-xs-4" style="padding: 0px 7px 0px 7px; padding-bottom: 10px;">
                            <img src="{{ $photo->url }}" class="img-responsive img-rounded" />
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-sm-4 text-left" style="">
                <div class="text-center" style="background: #CCC; padding-top: 50px; width: 100%; height: 200px; border-radius: 6px;">Ads Banner</div>
            </div>
        </div>
    </div>
@stop

@section('scripts')

@stop