@extends('app')

@section('content')
    <div class="container" style="margin: 0 auto;">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active">{{ $city->name }}</li>
        </ol>

        <h1>{{ $city->name }}</h1>
    </div>
@stop