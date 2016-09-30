@extends('app')

@section('content')
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
        <div class="col-md-10">
            @if ($category->description_top && Request::get('page') == 1)
                <p>{{ $category->description_top }}</p>
            @endif

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

                {{ $companies->appends(['option' => $selectedOptions])->links() }}
            @else
                <p>
                    Нет компаний в данной категории.
                </p>
            @endif

            @if ($category->description_top && Request::get('page') == 1)
                <p>{{ $category->description_bottom }}</p>
            @endif
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
@stop