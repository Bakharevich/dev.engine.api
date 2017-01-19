<div style="background: #FFF;">
    <div class="container hidden-xs site-header">
        <div class="col-sm-12 col-md-3 text-center-sm site-header-logo">
            <a href="http://{{ Request::get('site')->domain }}">
                <img src="{{ Request::get('site')->media_url }}logo/logo-main.png" />
            </a>
        </div>
        <div class="col-sm-7 col-md-5 site-header-search">
            <form class="form-horizontal">
                <div class="input-group">
                    <input type="text" id="site-search" class="form-control red-shadow" placeholder="{{ trans('common.search_field_value') }}" />
                    <span class="input-group-btn">
                        <button id="site-search-button" class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                </div>
            </form>
        </div>
        <div class="col-sm-5 col-md-4 text-right site-header-buttons">
            <a href="http://<?= Request::get('site')->domain ?>/companies/create" class="btn btn-chatoff">{{ trans('common.footer_add_business') }}</a>
            &nbsp;&nbsp;

            <div class="btn-group">
                <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="glyphicon glyphicon-map-marker"></span>
                    @if (!empty(Request::get('city')->id))
                        @foreach (Request::get('cities') as $city)
                            @if ($city->id == Request::get('city')->id)
                                {{ $city->name }}
                                @break
                            @endif
                        @endforeach
                    @endif
                </a>

                <ul class="dropdown-menu">
                    @foreach (Request::get('cities') as $city)
                        <li><a href="#">{{ $city->name }}</a></li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</div>