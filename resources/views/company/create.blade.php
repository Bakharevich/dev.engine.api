@extends('app')

@section('content')
    <div class="container">
        <h1>{{ trans('company.create-heading') }}</h1>

        <div class="jumbotron" style="padding-top: 30px; padding-bottom: 30px;">
            @include('company.form-create')
        </div>
    </div>
@stop

@section('scripts')

@stop