@extends('app')

@section('content')
    <div style="background: #FFF;">
        <div class="container text-center">
            <h1>{{ trans('errors.404-title') }}</h1>
            {!! trans('errors.404-description')  !!}
            <p>&nbsp;</p>
        </div>
    </div>
@stop