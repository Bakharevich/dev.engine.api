@extends('app')

@section('content')
    <div class="container" style="background-color: #fff; padding-bottom: 30px;">
        <h1>{{ $news->title }}</h1>
        {!! $news->content  !!}
    </div>
@stop