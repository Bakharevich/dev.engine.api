@extends('app')

@section('content')
    <div class="container">
        <h1>{{ trans('errors.404-title') }}</h1>
        {!! trans('errors.404-description')  !!}
    </div>
@stop