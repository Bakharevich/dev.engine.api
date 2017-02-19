@extends('app')

@section('content')
    <div style="background: #FFF; padding-bottom: 40px;">
        <div class="container">
            <div class="col-xs-12">
                @if (Session::has('message'))
                    <div class="alert alert-success alert-dismissible text-center" role="alert" style="margin-top: 40px;">
                        <h2>{{ trans('contact.thank-you') }}</h2>
                        <p>{{ trans('contact.message-sent') }}</p>
                    </div>
                @endif



                @if (!Session::get('message'))
                    <h1>{{ $page->title }}</h1>

                    <p>{!! $page->content  !!}</p>

                    <div class="col-sm-8">
                        <form method="post" action="/contact">
                            @include('errors.formerrors')

                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{ trans('contact.name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{ trans('contact.name') }}" value="{{ Request::old('name') }}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{ trans('contact.email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="{{ trans('contact.email') }}" value="{{ Request::old('email') }}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">{{ trans('contact.comment') }}</label>
                                <textarea class="form-control" name="comment" rows="3">{{ Request::old('comment') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ trans('contact.submit') }}</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop