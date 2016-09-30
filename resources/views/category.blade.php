@extends('app')

@section('content')
    <div class="container" style="margin: 0 auto;">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/{{ Request::get('site')->city->domain }}/">{{ Request::get('site')->city->name }}</a></li>
            <li class="active">{{ $category->name }}</li>
        </ol>

        @if ($category->description_top && Request::get('page') == 1)
            <p>{{ $category->description_top }}</p>
        @endif

        @foreach ($category->options_groups as $options_group)
            {{ $options_group->name }}<br/><br/>

            @foreach ($options_group->options as $option)
                {{ $option->name }}<br/>
            @endforeach
        @endforeach

        @if (count($companies) > 0)
            <div class="row">
                @foreach ($companies as $company)
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 text-center">
                        <a href="http://{{ $company->domain }}.{{ \Request::get('site')->domain }}/">
                            <div style="border: 1px solid #CCC; height: 175px;">
                            {{ $company->name }}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{ $companies->links() }}
        @else
            <p>
                Нет компаний в данной категории.
            </p>
        @endif

        @if ($category->description_top && Request::get('page') == 1)
            <p>{{ $category->description_bottom }}</p>
        @endif
    </div>
@stop