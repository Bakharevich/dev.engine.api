@extends('app')

@section('content')
    <div class="container" style="background-color: #fff; padding-bottom: 30px;">
        <h1>{{ $news->title }}</h1>
        @if ($news->description)
        <p><b>{{ $news->description }}</b></p>
        @endif
        {!! $news->content  !!}
    </div>
@stop