@extends('app')

@section('content')
    <div style="background-color: #fafafa; font-size: 0.85em;">
        <div class="container">
            <ol class="breadcrumb" style="margin-bottom: 0px; background: #fafafa;">
                <li><a href="http://{{ Request::get('site')->domain }}/">{{ trans('common.btn_home') }}</a></li>
                <li><a href="http://{{ Request::get('site')->domain }}/{{ Request::get('city')->domain }}">{{ Request::get('city')->name }}</a></li>
                <li><a href="{{ $company->category->url }}">{{ $company->category->name }}</a></li>
                <li class="active">{{ $company->name }}</li>
            </ol>
        </div>
    </div>

    <div style="background-color: #f5f5f5; font-size: 0.85em; padding-top: 15px; border-bottom: 1px solid #e7e7e7; border-top: 1px solid #eeeeee;">
        <div class="container">
            <div class="col-md-1 hidden-sm company-icon">
                @if ($company->main_photo_url)
                    <img src="{{ $company->main_photo_url }}" class="img-responsive img-rounded hidden-xs hidden-sm" />
                @else
                    <div class="hidden-xs hidden-sm text-center no-image">
                        @if ($company->category->icon)
                            <i class="{{ $company->category->icon }}" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
                        @endif
                    </div>
                @endif
            </div>
            <div class="col-sm-8 col-md-7">
                <h1 style="margin-top: 0; ">{{ $company->name }}</h1>

                <div>
                    @if ($company->rating)
                        <div class="company-rating" style="margin-bottom: 20px; float: left;">
                            {!! Helper::companyRating($company->rating, 22) !!} &nbsp;
                        </div>
                    @endif

                    @if ($company->amount_comments)
                        <div style="padding-top: 8px; float: left;">
                            {{ $company->amount_comments }} {{ trans('company.number_of_reviews') }}
                        </div>
                    @endif
                </div>

                <div class="clearfix"></div>

                @if ($company->description)
                    <p style="color: #222;">{!! str_limit(strip_tags($company->description), 250)  !!}</p>
                @endif
            </div>
            <div class="col-sm-4 col-md-4 company-info" style="font-size: 1.1em; padding-top: 5px;">
                @if ($company->address)
                    <p><i class="fa fa-map-marker" aria-hidden="true"></i> {!! $company->address !!}</p>
                @endif

                @if ($company->tel)
                    <p><i class="fa fa-phone-square" aria-hidden="true"></i> {!! $company->tel !!}</p>
                @endif

                @if ($company->website)
                    <p>
                        <i class="fa fa-link" aria-hidden="true"></i>
                        <a href="{!! $company->website !!}" target="_blank" rel="nofollow" class="company-www">{!! $company->website !!}</a>
                    </p>
                @endif

                @if ($company->hours)
                    <p>
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                        <a href="javascript://" data-toggle="modal" data-target="#company_hours" style="border-bottom: 1px dotted #333; color: #333;">
                            {{ trans('company.hours_today') }} {{ \App\Helpers\Helper::todayWorkingTime($company->hours) }}
                        </a>
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div style="background: #FFF; padding-top: 25px;">
        <div class="container">
            <div class="hidden-md hidden-lg col-xs-12">
                <div class="img-thumbnail yandex-map" style="width: 100%; height: 200px; margin-bottom: 20px;"></div>
            </div>

            @if(Session::has('message'))
                <div class="alert alert-success alert-dismissible text-center" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p><strong>{{ trans('company.company-review-success') }}</strong></p>
                </div>
            @endif

            <!-- PHOTOS -->
            @if (count($company->photos) > 1)
                <div class="company-photos">
                    @foreach ($company->photos as $index => $photo)
                        <?php $isXs = ($index > 1) ? 'hidden-xs' : '' ?>
                        <?php $visibleXs = ($index == 1) ? 'visible-xs' : '' ?>

                        <div class="col-md-2 col-sm-2 col-xs-6 {{ $isXs }}">
                            <a href="<?php echo \App\Helpers\Helper::fullPhotoPath($photo->url) ?>" target="_blank">
                                <img src="{{ $photo->url }}" class="img-responsive img-rounded" />
                            </a>

                            <div class="more {{ $visibleXs }}">
                                <a href="<?= $company->url ?>photos" class="more">
                                    <i class="fa fa-chevron-circle-right" aria-hidden="true"></i><br/>
                                    {{ trans('company.photos-all') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="clearfix"></div>
            @endif

            <div class="col-md-8" style="padding-bottom: 30px;">
                <?php $banner = \App\Repositories\BannerRepository::banner(3, Request::get('site')->id); ?>
                @if ($banner)
                <!-- Central banner -->
                    <div style="margin-bottom: 15px;">
                        <?= $banner ?>
                    </div>
                @endif

                @if ($company->description)
                    <h3 style="margin-top: 0px;">{{ trans('company.about_company') }} {{ $company->name }}</h3>
                    <div style="margin-bottom: 30px;">
                        {!! $company->description !!}
                    </div>
                @endif

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

                    <a href="<?= $company->url ?>reviews" class="btn btn-primary">{{ trans('company.reviews-all') }}</a>
                @endif
            </div>
            <div class="col-md-4 text-left" style="margin-top: 0px;">
                @if ($company->latitude && $company->longitude)
                    <div id="map" class="img-thumbnail yandex-map hidden-sm hidden-xs" style="width: 100%; height: 300px;"></div>
                @endif

                <?php $banner = \App\Repositories\BannerRepository::banner(4, Request::get('site')->id); ?>
                @if ($banner)
                    <!-- Central banner -->
                        <div style="margin-top: 25px;">
                            <?= $banner ?>
                        </div>
                @endif
            </div>

            <div class="col-md-12">

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="company_hours" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 0px solid #e5e5e5;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('company.hours') }} {{ $company->name }}</h4>
                </div>
                <div class="modal-body">
                    @if ($company->hours)
                        <table class="table table-bordered">
                            @foreach ($company->hours as $hour)
                                <tr>
                                    <td><b>{{ ucfirst($hour->day) }}</b></td>
                                    <td>{{ $hour->open }} - {{ $hour->close }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
                <div class="modal-footer" style="border-top: 0px;">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of modal -->
@stop

@section('scripts')
    @if ($company->latitude && $company->longitude)
        <script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

        <script>
            ymaps.ready(init);

            var latitude = {{ $company->latitude }};
            var longitude = {{ $company->longitude }};


            function init () {
                var container = $(".yandex-map");

                $.each(container, function (index, value) {
                    //console.log(index);
                    var myMap = new ymaps.Map(container[index], {
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
                });
            }
        </script>
    @endif
@stop