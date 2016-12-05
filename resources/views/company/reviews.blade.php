@extends('app')

@section('content')
    <div style="background-color: #f5f5f5; font-size: 0.85em; border-bottom: 1px solid #e7e7e7;">
        <div class="container">
            <ol class="breadcrumb" style="margin-bottom: 0px;">
                <li><a href="http://{{ Request::get('site')->domain }}/">{{ trans('common.btn_home') }}</a></li>
                <li><a href="http://{{ Request::get('site')->domain }}/{{ Request::get('site')->city->domain }}">{{ Request::get('site')->city->name }}</a></li>
                <li><a href="{{ $company->category->url }}">{{ $company->category->name }}</a></li>
                <li><a href="{{ $company->url }}">{{ $company->name }}</a></li>
                <li class="active">{{ trans('company.reviews') }} {{ $company->name }}</li>
            </ol>

            <div class="col-md-12">
                <h1 style="margin-top: 0; ">{{ trans('company.reviews') }} {{ $company->name }}</h1>
            </div>
        </div>
    </div>

    <div style="background: #FFF; padding-top: 25px;">
        <div class="container" style="margin: 0 auto;">
            <div class="col-md-8">

                @include('company.form-post-review')

                @if ($company->reviews)
                    <h3>{{ trans('company.latest_reviews') }}</h3>
                    @foreach ($company->reviews as $review)
                        <div>
                            <p><b>{{ $review->name }}</b> <small>({{ $review->created_at }})</small></p>
                            <p>{!! Helper::companyRating($review->rating, 14) !!}</p>
                            <p>{!! $review->review  !!}</p>
                            <hr />
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="col-md-4 text-left" style="">
                <div class="text-center" style="background: #CCC; padding-top: 50px; width: 100%; height: 200px; border-radius: 6px;">Ads Banner</div>
            </div>
        </div>
    </div>
@stop

@section('scripts')

@stop