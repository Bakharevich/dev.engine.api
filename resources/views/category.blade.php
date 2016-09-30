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
                    <b>{{ $options_group->name }}</b><br/>

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

            @if (count($companies) > 0)
                <div class="row">
                    @foreach ($companies as $company)
                        <a href="http://{{ $company->domain }}.{{ \Request::get('site')->domain }}/">
                            <div class="col-sm-4">
                                <img src="{{ $company->main_photo_url }}" class="img-responsive" />
                            </div>
                            <div class="col-sm-8">


                                        {{ $company->name }}

                                </a>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{ $companies->appends(['option' => $selectedOptions])->links() }}
            @else
                <p>
                    Нет компаний в данной категории.
                </p>
            @endif

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
        $(function(){
            $(':checkbox').change(function(){
                var ser = $("#form_options").serialize();

                document.location = '/{{ Request::get('site')->city->domain }}/{{ $category->domain }}/?' + ser;
            })
        });
    </script>

    <script>
        ymaps.ready(init);


        var latitude = 53.90;
        var longitude = 27.57;

        function init () {
            var myMap = new ymaps.Map("map", {
                        center: [latitude, longitude],
                        zoom: 10
                    }, {
                        searchControlProvider: 'yandex#search'
                    });
        }
    </script>
@stop