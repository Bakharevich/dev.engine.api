@extends('app')

@section('content')
    <script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

    <div class="container" style="margin: 0 auto;">
        <ol class="breadcrumb">
            <li><a href="http://{{ Request::get('site')->domain }}">Home</a></li>
            <li><a href="http://{{ Request::get('site')->domain }}/{{ Request::get('site')->city->domain }}/">{{ Request::get('site')->city->name }}</a></li>
            <li><a href="http://{{ Request::get('site')->domain }}/{{ Request::get('site')->city->domain }}/{{ $company->category->domain }}/">{{ $company->category->name }}</a></li>
            <li class="active">{{ $company->name }}</li>
        </ol>

        <h1>{{ $company->name }}</h1>

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