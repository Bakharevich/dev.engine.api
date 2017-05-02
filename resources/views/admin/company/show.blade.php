@extends('layouts.admin')

@section('content')
<h1>{{ $company->name }}</h1>

<form action="/admin/companies/{{ $company->id }}" method="post" class="form-create-company validation">
    @include('errors.formerrors')

    <input type="hidden" name="_method" value="PUT" />
    <input type="hidden" name="category_id" value="{{ $company->category_id }}" />

    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label class="control-label" for="name">{{ trans('company.create-name') }}:</label>
        <input type="text" class="form-control required" name="name" id="name" value="{{ $company->name or old('name') }}" />
    </div>
    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
        <label class="control-label" for="address">{{ trans('company.create-address') }}:</label>
        <input type="text" class="form-control required" name="address" id="address" value="{{ $company->address or old('address') }}" />
    </div>
    <div class="form-group{{ $errors->has('tel') ? ' has-error' : '' }}">
        <label class="control-label" for="tel">{{ trans('company.create-tel') }}:</label>
        <input type="text" class="form-control" name="tel" id="tel" value="{{ $company->tel or old('tel') }}" />
    </div>
    <div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
        <label class="control-label" for="website">{{ trans('company.create-website') }}:</label>
        <input type="text" class="form-control" name="website" id="website" value="{{ $company->website or old('website') }}" />
    </div>
    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
        <label class="control-label" for="description">{{ trans('company.create-description') }}:</label>
        <textarea class="form-control" rows="4" name="description" id="description" required>{!! $company->description or old('description') !!}</textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ trans('common.send') }}</button>
    </div>
</form>
@stop

@section('scripts')
    <script>
        $(function(){
            $('#description').froalaEditor({
                height: 300
            });
        });
    </script>
@stop