@extends('app')

@section('content')
    <script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

    <div class="container" style="margin: 0 auto;">
        <!--
        <ol class="breadcrumb">
            <li><a href="http://{{ Request::get('site')->domain }}">Home</a></li>
            <li><a href="http://{{ Request::get('site')->domain }}/{{ Request::get('site')->city->domain }}/">{{ Request::get('site')->city->name }}</a></li>
            <li><a href="http://{{ Request::get('site')->domain }}/{{ Request::get('site')->city->domain }}/{{ $company->category->domain }}/">{{ $company->category->name }}</a></li>
            <li class="active">{{ $company->name }}</li>
        </ol>
        -->

        <div class="row">
            <div class="col-md-6">
                <h1 style="margin-top: 0px;">{{ $company->name }}</h1>
                <i class="fa fa-star" aria-hidden="true" style="font-size: 1.5em; color: #73CF42;"></i>
                <i class="fa fa-star" aria-hidden="true" style="font-size: 1.5em;"></i>
                <i class="fa fa-star" aria-hidden="true" style="font-size: 1.5em;"></i>
                <i class="fa fa-star" aria-hidden="true" style="font-size: 1.5em;"></i>
            </div>
            <div class="col-md-6 text-right" style="margin-top: 30px;">
                <button type="button" class="btn btn-danger" style="margin-right: 10px;">
                    <i class="fa fa-star" aria-hidden="true" style="color: #FFF; font-size: 1.4em;"></i> Оставить отзыв
                </button>

                <div class="btn-group btn-group-sm" role="group" aria-label="...">

                    <button type="button" class="btn btn-default">
                        <i class="fa fa-share" aria-hidden="true"></i> Поделиться
                    </button>
                    <button type="button" class="btn btn-default">
                        <i class="fa fa-bookmark" aria-hidden="true"></i> В избранное
                    </button>
                </div>
            </div>
        </div>


        @if ($company->options)
            <div>
            @foreach ($company->options as $option)
                <span class="label label-default">{{ $option->name }}</span>
            @endforeach
            </div>
            <br/>
        @endif

        @if ($company->description)
            <p>{{ $company->description }}</p>
            <br/>
        @endif

        @if ($company->latitude && $company->longitude)
            <div id="map" style="width: 100%; height: 300px;"></div>
        @endif
    </div>
@stop

@section('scripts')
<script>
    ymaps.ready(init);


    var latitude = {{ $company->latitude }};
    var longitude = {{ $company->longitude }};


    function init () {
        var myMap = new ymaps.Map("map", {
                    center: [latitude, longitude],
                    zoom: 16
                }, {
                    searchControlProvider: 'yandex#search'
                }),

        myGeoObject = new ymaps.GeoObject({
            geometry: {
                type: "Point",
                coordinates: [latitude, longitude]
            },
            // Свойства.
            properties: {

            }
        }, {
            preset: 'islands#icon'
        });

        myMap.geoObjects.add(myGeoObject);
    }
</script>
@stop