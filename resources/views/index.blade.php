@extends('app')

@section('content')
    <style>
        .index-bg {

            background: linear-gradient(
                to bottom,
                rgba(30, 0, 0, 0.4),
                rgba(30, 0, 0, 0.4)
            ), url({{ Request::get('site')->media_url }}/backgrounds/index.jpg);
            background-size: cover;
            background-position: left top !important;
            /*-webkit-filter: blur(5px);  -moz-filter: blur(5px);  -o-filter: blur(5px);  -ms-filter: blur(5px);  filter: blur(5px);*/
            padding: 30px 0px 225px 0px;
            margin-bottom: 30px;
        }
        .index-bg {


        }
        body {
            /*padding-top: 50px;*/
            background: #f0f1f2;
        }
        .btn-white {
            background: none;
            color: #FFF;
            border: 2px solid #FFF;
            font-weight: bold;
        }
        .no-border {
            border: 0px !important;
        }
        I {
            -webkit-transition: color 0.8s ease;
            -moz-transition: color 0.8s ease;
            -o-transition: color 0.8s ease;
            transition: color 0.8s ease;
        }
    </style>

    <div style="padding-bottom: 10px;">
        <div class="index-bg text-center">
            <div class="container" style="max-width: 900px; padding-bottom: 100px;">
                <div class="col-xs-6 text-left">
                    <!-- Single button -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-white no-border dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-map-marker" aria-hidden="true"></i> &nbsp;
                            @foreach (Request::get('cities') as $city)
                                @if ($city->id == Request::get('site')->city_id)
                                    {{ $city->name }}
                                    @break
                                @endif
                            @endforeach
                        </button>
                        <ul class="dropdown-menu">
                            @foreach (Request::get('cities') as $city)
                                <li><a href="#">{{ $city->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xs-6 text-right">
                    @if (!Auth::check())
                        <a href="/login" class="btn btn-default btn-white no-border">{{ trans('common.login') }}</a>
                        <a href="/register" class="btn btn-default btn-white">{{ trans('common.registration') }}</a>
                    @else
                        @if ($companies)
                            <a href="/admin" class="btn btn-default btn-white" style="border: 2px solid #ff6666;">{{ trans('common.admin') }}</a>
                        @endif

                        <a href="/logout" class="btn btn-default btn-white">{{ trans('common.logout') }}</a>
                    @endif
                </div>
            </div>

            <h1 class="index-page-title">{{ Request::get('site')->name }}</h1>

            <div style="max-width: 900px; margin: 0 auto; padding: 0px 30px 0px 30px; margin-bottom: 20px;">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control" placeholder="{{ trans('common.search_field_value') }}" id="site-search" autofocus>

                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </span>
                </div>
            </div>

            <div style="padding: 0px 15px 0px 15px;">
                @foreach($categoriesRandom as $cat)
                    @if ($cat->icon)
                        <i class="{{ $cat->icon }}" aria-hidden="true" style="color: #FFF; margin-right: 2px;"></i>
                    @else
                        <i class="fa fa-chevron-circle-right" aria-hidden="true" style="color: #FFF; margin-right: 2px;"></i>
                    @endif

                    <a href="{{ $cat->url }}" style="color: #FFF;font-weight: bold;">{{ $cat->name }}</a>
                    &nbsp;&nbsp;&nbsp;
                @endforeach
            </div>
        </div>

        <div class="container text-center">

            <h2 class="index-subtitle margin-bottom-30">{{ trans('index.categories-title') }}</h2>

            @if (count($categoriesGroups) > 0)
                <div class="row margin-bottom-20">
                @foreach ($categoriesGroups as $group)
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 text-center margin-bottom-30" >
                        <a href="#" class="index-link-category" data-colour="<?php echo \App\Helpers\Helper::getRandom($colours) ?>" data-id="{{ $group->id }}">
                            <div class="index-link-box">
                                <div class="index-link-box-top">
                                    @if (!empty($group->icon))
                                        <i class="{{ $group->icon }}" aria-hidden="true" style="font-size: 64px; color: #8a8f9a;"></i>
                                    @else
                                        <i class="fa fa-chevron-circle-right" aria-hidden="true" style="font-size: 64px; color: #8a8f9a;"></i>
                                    @endif
                                </div>
                                <div class="index-link-box-bottom">
                                    {{ $group->name1 }}
                                    @if ($group->name2)
                                        {{ $group->name2 }}
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                </div>
            @else
                {{ trans('index.index-no-categories') }}
            @endif

            @if (count($news) > 0)
                <h2>{{ trans('index.index-news') }}</h2>

                <div class="row index-news">
                    @foreach ($news as $post)
                        <div class="col-md-3">
                            <div class="index-news-block">
                                <a href="{{ $post->url }}">
                                    @if ($post->photo)
                                        <img src="{{ $post->photo }}" class="img-responsive" />
                                    @endif

                                    <div class="index-news-block-description">
                                        <p class="title"><b>{{ $post->title }}</b></p>

                                        <p>{{ str_limit($post->description, 100) }}</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- /News -->
            @endif

            <div class="text-center" style="font-size: 0.85em; line-height: 1.3em; color: #666;">
                {!! \App\Repositories\PageRepository::getContent(Request::get('site')->id, 'main-about')  !!}
            </div>
        </div>
    </div>

    <!-- Modal for categories -->
    <div class="modal fade index-modal-categories" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="float: left; margin-right: 7px;"></h4> <div class="status" style="padding-top: 3px;"></div>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal for categories -->
@stop

@section('scripts')
    <script>
        $(document).ready(function(){
            var returnColour = '';

            // change colour for category on hover
            $(".index-link-category").hover(function(){
                var colour = $(this).attr('data-colour');
                returnColour = $(this).find('I').css('color');

                $(this).find('I').css('color', colour);
            }, function() {
                $(this).find('I').css('color', returnColour);
            });

            // open modal
            $("A.index-link-category").click(function(e) {
                e.preventDefault();

                // init vars
                var name    = $(this).find('DIV.index-link-box-bottom').html();
                var id      = $(this).data('id');
                var params  = {
                    category_group_id: id,
                    format: 'html'
                };
                var modal   = $(".index-modal-categories");

                // clear before values
                modal.find('.modal-body').html('');

                // success function
                function success(data) {
                    modal.find('DIV.modal-body').html(data.result);
                    modal.find('DIV.modal-body UL').show();
                }

                function error(data) {
                    console.log(data);
                }

                // error function

                // get categories for group
                getCategoriesByCategoryGroup(params, success, error, modal);

                // set in modal
                modal.find('H4.modal-title').html(name);

                // show modal
                modal.modal('show');
            })
        })
    </script>
@stop