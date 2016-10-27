@extends('app')

@section('content')
    <div style="background-color: #f5f5f5; font-size: 0.85em; border-bottom: 1px solid #e7e7e7;">
        <div class="container">
            <ol class="breadcrumb" style="margin-bottom: 0px;">
                <li><a href="http://{{ Request::get('site')->domain }}/">{{ trans('common.btn_home') }}</a></li>
                <li><a href="http://{{ Request::get('site')->domain }}/{{ Request::get('site')->city->domain }}">{{ Request::get('site')->city->name }}</a></li>
                <li><a href="{{ $company->category->url }}">{{ $company->category->name }}</a></li>
                <li class="active">{{ $company->name }}</li>
            </ol>

            <div class="col-md-8">
                <h1 style="margin-top: 0; ">{{ $company->name }}</h1>

                @if ($company->rating)
                    <div class="company-rating" style="margin-bottom: 20px;">
                        {!! Helper::companyRating($company->rating, 22) !!} &nbsp; {{ $company->amount_comments }} {{ trans('company.number_of_reviews') }}
                    </div>
                @endif

                @if ($company->description)
                    <p style="color: #222;">{!! str_limit(strip_tags($company->description), 250)  !!}</p>
                @endif
            </div>
            <div class="col-md-4" style="font-size: 1.1em; padding-top: 5px;">
                @if ($company->address)
                    <p><i class="fa fa-map-marker" aria-hidden="true"></i> {!! $company->address !!}</p>
                @endif

                @if ($company->tel)
                    <p><i class="fa fa-phone-square" aria-hidden="true"></i> {!! $company->tel !!}</p>
                @endif

                @if ($company->website)
                    <p>
                        <i class="fa fa-link" aria-hidden="true"></i>
                        <a href="{!! $company->website !!}" target="_blank" rel="nofollow">{!! $company->website !!}</a>
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div style="background: #FFF;">
        <div class="container" style="margin: 0 auto;">
            @if ($company->photos)
                <div class="col-md-12" style="margin-top: 20px; padding: 0px 7px 0px 7px;">
                    @foreach ($company->photos as $index => $photo)
                        <?php $isXs = ($index > 1) ? 'hidden-xs' : '' ?>

                        <div class="col-md-2 col-sm-2 col-xs-6 {{ $isXs }}" style="padding: 0px 7px 0px 7px; padding-bottom: 10px;">
                            <img src="{{ $photo->url }}" class="img-responsive img-rounded" />
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="col-md-8">
                @if ($company->description)
                    <h3>{{ trans('company.about_company') }} {{ $company->name }}</h3>
                    {!! $company->description !!}
                @endif

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
            <div class="col-md-4 text-left" style="margin-top: 30px;">
                @if ($company->latitude && $company->longitude)
                    <div id="map" class="img-thumbnail" style="width: 100%; height: 300px;"></div>
                @endif

                <h3>Hours</h3>

                @if ($company->hours)
                    <table class="text-left">
                    @foreach ($company->hours as $hour)
                        <tr>
                            <td style="padding-right: 30px;"><b>{{ ucfirst($hour->day) }}</b></td>
                            <td style="padding-right: 10px;">{{ $hour->open }} - {{ $hour->close }}</td>
                            <td>{{ Helper::isCompanyOpened($hour->day, $hour->open, $hour->close) }}</td>
                        </tr>
                    @endforeach
                    </table>
                @endif

                <!--
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
                -->
            </div>

            <div class="col-md-12">

            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

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