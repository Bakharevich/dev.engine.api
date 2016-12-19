

<form action="{{ url('/companies/create') }}" method="post" class="form-create-company">
    @include('errors.formerrors')

    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
        <label class="control-label" for="category_id">{{ trans('company.create-category') }}:</label>
        <select class="form-control" name="category_id" id="category_id" value="{{ old('category_id') }}">
            <option value="">// -- //</option>
            @foreach($categories as $index => $category)
                <option disabled>[{{ $index }}]</option>

                @foreach($category as $cat)
                    <option value="{{ $cat['id'] }}" @if (old('category_id') == $cat['id']) selected @endif>{{ $cat['name'] }}</option>
                @endforeach
            @endforeach
        </select>
    </div>
    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <label class="control-label" for="name">{{ trans('company.create-name') }}:</label>
        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" />
    </div>
    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
        <label class="control-label" for="address">{{ trans('company.create-address') }}:</label>
        <input type="text" class="form-control" name="address" id="address" />
    </div>
    <div class="form-group{{ $errors->has('tel') ? ' has-error' : '' }}">
        <label class="control-label" for="tel">{{ trans('company.create-tel') }}:</label>
        <input type="text" class="form-control" name="tel" id="tel" />
    </div>
    <div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
        <label class="control-label" for="website">{{ trans('company.create-website') }}:</label>
        <input type="text" class="form-control" name="website" id="website" />
    </div>
    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
        <label class="control-label" for="description">{{ trans('company.create-description') }}:</label>
        <textarea class="form-control" rows="4" name="description" id="description" required></textarea>
    </div>
    <div class="form-group{{ $errors->has('price_range') ? ' has-error' : '' }}">
        <label class="control-label" for="price_range">{{ trans('company.create-price-range') }}:</label>
        <select class="form-control" name="price_range" id="price_range">
            <option value="0"></option>
            <option value="1" @if (old('price_range') == 1) selected @endif>$</option>
            <option value="2" @if (old('price_range') == 2) selected @endif>$$</option>
            <option value="3" @if (old('price_range') == 3) selected @endif>$$$</option>
            <option value="4" @if (old('price_range') == 4) selected @endif>$$$$</option>
        </select>
    </div>
    <!--
    <div class="form-group">
        <label class="control-label" for="photos">{{ trans('company.photos') }}:</label>
        <input type="file" class="" name="photos[]" />
        <input type="file" class="" name="photos[]" />
        <input type="file" class="" name="photos[]" />
    </div>
    -->
    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ trans('company.create-btn-create') }}</button>
    </div>
</form>

@section('scripts')
    <script>
        $(function(){

        });
    </script>
@stop