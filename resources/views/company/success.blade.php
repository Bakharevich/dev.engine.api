@extends('app')

@section('content')
    <div  style="background: #FFF;">
        <div class="container text-center">
            <h1>{{ trans('company.create-success-heading') }}</h1>

            {!! trans('company.create-success-description') !!}

            <p>&nbsp;</p>
        </div>
    </div>
@stop

@section('scripts')

@stop