@extends('app')

@section('content')
    <div style="background-color: #f5f5f5; font-size: 0.85em; border-bottom: 1px solid #e7e7e7;">
        <div class="container">
            <ol class="breadcrumb" style="margin-bottom: 0px;">
                <li><a href="/">{{ trans('common.btn_home') }}</a></li>
                <li><a href="/{{ Request::get('city')->domain }}">{{ Request::get('city')->name }}</a></li>
                <li class="active">{{ $category->name }}</li>
            </ol>

            <div style="padding: 0px 14px;">
                <h1 style="margin-top: 0;">{{ $category->meta_title }}</h1>

                @if ($category->description_top && (Request::get('page') == 1 || Request::get('page') === null) && !$selectedOptions)
                    <p style="color: #222;">{{ $category->description_top }}</p>
                @endif
            </div>

            <div style="padding: 0px 14px; margin-bottom: 13px;">
                <form action="" method="GET" id="form_options">
                    <div class="btn-group btn-group-sm" role="group" aria-label="..." style="margin-right: 5px;">
                        @for($i=1;$i<5;$i++)
                            <button type="button" class="btn btn-default one-click">
                                @for($c=0; $c<$i; $c++){{ Request('site')->currency }}@endfor
                            </button>
                        @endfor
                    </div>

                    <button class="btn btn-default btn-sm one-click" style="margin-right: 5px;" onclick="alert('{{ trans('common.alert-not-done') }}');"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ trans('category.option-open-now') }}</button>
                    <!--
                    <button class="btn btn-default btn-sm one-click" style="margin-right: 7px;"><i class="fa fa-wifi" aria-hidden="true"></i> {{ trans('category.option-wifi') }}</button>
                    -->

                    @if ($category->options_groups)
                        @foreach ($category->options_groups as $options_group)
                            <div class="btn-group btn-group-sm" style="margin-right: 5px;">
                                <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="{{ $options_group->icon }}" aria-hidden="true"></i> {{ $options_group->name }} <span class="caret"></span>
                                </button>

                                <ul class="dropdown-menu">
                                @foreach ($options_group->options as $option)
                                    <li style="padding: 3px 12px;">

                                            <label style="font-weight: normal;">
                                                @if (!empty($selectedOptions[$option['id']]))
                                                    <input name="option[{{ $option->id }}]" value="{{ $option->id }}" type="checkbox" checked> {{ $option->name }}
                                                @else
                                                    <input name="option[{{ $option->id }}]" value="{{ $option->id }}" type="checkbox"> {{ $option->name }}
                                                @endif
                                            </label>

                                    </li>
                                @endforeach
                                </ul>
                            </div>
                        @endforeach
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div style="background-color: #FFF; padding-top: 20px; padding-bottom: 40px;">
        <div class="container" style="margin: 0 auto;">
            <div class="col-md-9">
                <!--
                <h1 style="margin-top: 0;">{{ $category->name }}</h1>
                -->

                <!--
                @if ($category->description_top && (Request::get('page') == 1 || Request::get('page') === null) && !$selectedOptions)
                    <p style="font-size: 0.9em; color: #222;">{{ $category->description_top }}</p>
                @endif
                -->

                <div id="companies_list">
                    @include('category.companies')
                </div>


                @if ($category->description_bottom && (Request::get('page') == 1 || Request::get('page') === null && !$selectedOptions))
                    <div style="color: #444; font-size: 0.85em;">
                        {!! $category->description_bottom  !!}
                    </div>
                @endif
            </div>
            <div class="col-md-3">
                <div class="img-thumbnail" id="map" style="width: 100%; height: 300px;"></div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
            <!-- TEMPORARY SOLUTION -->

            <script>
                var latitude = {{ Request::get('site')->city->latitude }};
                var longitude = {{ Request::get('site')->city->longitude }};
                var category_id = '{{ $category->id }}';
                var page = '{{ Request::get('page', 1) }}';
            </script>

            @if (Request::get('site')->locale == 'ru' || Request::get('site')->locale == 'en')
                <script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

                <script>
                    $(document).ready(function() {
                        // when Yandex DOM is ready, do the job
                        ymaps.ready(createMap);
                        ymaps.ready(addObjectsToMap);

                        function createMap() {
                            window.myMap = new ymaps.Map("map", {
                                center: [latitude, longitude],
                                zoom: 11
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
                                console.log( "Request failed: " + textStatus );
                            });
                        }

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
                                console.log("Request failed: " + textStatus );
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

                        $("BUTTON.one-click").click(function(e){
                            e.preventDefault();
                            $(this).toggleClass('active');
                        });
                    });
                </script>
            @elseif (Request::get('site')->locale == 'en11111111')
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAA7OAks4bUzGKLf0_hlci_B0NbW23WGpY&callback=initMap"
                        async defer></script>

                <script>
                var map;
                function initMap() {
                    map = new google.maps.Map(document.getElementById('map'), {
                        center: {lat: latitude, lng: longitude},
                        zoom: 8
                    });
                }
                </script>
    @endif

@stop