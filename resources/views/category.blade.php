@extends('app')

@section('content')
    <script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

    <div class="container" style="margin: 0 auto;">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/{{ Request::get('site')->city->domain }}/">{{ Request::get('site')->city->name }}</a></li>
            <li class="active">{{ $category->name }}</li>
        </ol>

        <div class="col-md-2">
            @if ($category->options_groups)
                <form action="" method="GET" id="form_options">
                @foreach ($category->options_groups as $options_group)
                    <b>{{ $options_group->name }}</b>
                        <br/>

                    @foreach ($options_group->options as $option)
                    <div class="checkbox">
                        <label>
                            @if (!empty($selectedOptions[$option['id']]))
                                <input name="option[{{ $option->id }}]" value="{{ $option->id }}" type="checkbox" checked> {{ $option->name }}
                            @else
                                <input name="option[{{ $option->id }}]" value="{{ $option->id }}" type="checkbox"> {{ $option->name }}
                            @endif
                        </label>
                    </div>
                    @endforeach
                @endforeach
                </form>
            @endif

        </div>
        <div class="col-md-7">
            <h1 style="margin-top: 0;">{{ $category->name }}</h1>

            @if ($category->description_top && (Request::get('page') == 1 || Request::get('page') === null) && !$selectedOptions)
                <p>{{ $category->description_top }}</p>
            @endif

            <div id="companies_list">
                @include('category.companies')
            </div>

            @if ($category->description_top && (Request::get('page') == 1 || Request::get('page') === null && !$selectedOptions))
                <p>{{ $category->description_bottom }}</p>
            @endif
        </div>
        <div class="col-md-3">
            <div id="map" style="width: 100%; height: 300px;"></div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            // when Yandex DOM is ready, do the job
            ymaps.ready(createMap);
            ymaps.ready(addObjectsToMap);

            var latitude = 53.90;
            var longitude = 27.57;
            var category_id = '{{ $category->id }}';
            var page = '{{ Request::get('page', 1) }}';

            function createMap() {
                window.myMap = new ymaps.Map("map", {
                    center: [latitude, longitude],
                    zoom: 10
                }, {
                    searchControlProvider: 'yandex#search'
                });
            }

            function addObjectsToMap () {
                // create collection
                var myCollection = new ymaps.GeoObjectCollection(null, {
                    preset: 'islands#blueIcon'
                });

                // get options
                var ser = $("#form_options").serialize();

                // remove objects from map
                window.myMap.geoObjects.removeAll();

                // get companies for map
                var request = $.ajax({
                    url: "/api/companies/getAllByCategoryId?" + ser,
                    method: "GET",
                    data: {
                        category_id : category_id,
                        page: page
                    },
                    dataType: "json"
                });
                //alert(ser);

                request.done(function( data ) {
                    $.each(data.data, function (key, value) {
                        //console.log(value.name);
                        myPlacemark = new ymaps.Placemark([value.latitude, value.longitude], {
                            hintContent: value.name,
                            balloonContent: value.name
                        });

                        //window.myMap.geoObjects.add(myPlacemark);
                        myCollection.add(myPlacemark);
                    });


                    window.myMap.geoObjects.add(myCollection);

                    // myCollection.getLength();
                    //window.myMap.setBounds(myCollection.getBounds());
                });

                request.fail(function( jqXHR, textStatus ) {
                    alert( "Request failed: " + textStatus );
                });
            }

            // EVENTS
            $(':checkbox').change(function(){
                //alert($(this).html());
                $("#companies_list").prepend('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
                var ser = $("#form_options").serialize();

                //document.location = '/{{ Request::get('site')->city->domain }}/{{ $category->domain }}/?' + ser;
                // add geoobjects
                addObjectsToMap();

                // refresh companies list
                getCompanies();
            });

            function getCompanies()
            {
                // get options
                var ser = $("#form_options").serialize();

                var request = $.ajax({
                    url: "/api/companies/getHtmlByCategoryId?" + ser,
                    method: "GET",
                    data: {
                        category_id : category_id,
                        page: page
                    },
                    dataType: "html"
                });
                //alert(ser);

                request.done(function( data ) {
                    //alert(data);
                    $("#companies_list").html(data);

                    // myCollection.getLength();
                    //window.myMap.setBounds(myCollection.getBounds());
                });

                request.fail(function( jqXHR, textStatus ) {
                    alert( "Request failed: " + textStatus );
                });
            }
        });

    </script>
@stop