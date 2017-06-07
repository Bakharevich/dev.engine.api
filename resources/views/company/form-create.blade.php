

<form action="{{ url('/companies/create') }}" method="post" class="form-create-company validation">
    @include('errors.formerrors')

    {{ csrf_field() }}

    <h3 style="margin-bottom: 20px;">{{ trans('company.create-about-company') }}</h3>

    <div class="col-sm-12" style="padding-left: 0px;">
        <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
            <label class="control-label" for="category_id">{{ trans('company.create-category') }}:</label>
            <select class="form-control required" name="category_id" id="category_id" value="{{ old('category_id') }}">
                <option value="">// -- //</option>
                @foreach($categories as $index => $category)
                    <option disabled>[{{ $index }}]</option>

                    @foreach($category as $cat)
                        <option value="{{ $cat['id'] }}" @if (old('category_id') == $cat['id']) selected @endif>{{ $cat['name'] }}</option>
                    @endforeach
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-12" style="padding-left: 0px; padding-right: 0px;">
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label" for="name">{{ trans('company.create-name') }}:</label>
            <input type="text" class="form-control required" name="name" id="name" value="{{ old('name') }}" />
        </div>
    </div>
    <div>
        <div class="col-sm-6" style="padding-left: 0px;">
            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                <label class="control-label" for="address">{{ trans('company.create-address') }}:</label>
                <input type="text" class="form-control required" name="address" id="address" />
            </div>
        </div>
        <div class="col-sm-6" style="padding-left: 0px; padding-right: 0px;">
            <div class="form-group{{ $errors->has('tel') ? ' has-error' : '' }}">
                <label class="control-label" for="tel">{{ trans('company.create-tel') }}:</label>
                <input type="text" class="form-control" name="tel" id="tel" />
            </div>
        </div>
    </div>
    <div>
        <div class="col-sm-6" style="padding-left: 0px;">
            <div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
                <label class="control-label" for="website">{{ trans('company.create-website') }}:</label>
                <input type="text" class="form-control" name="website" id="website" />
            </div>
        </div>
        <div class="col-sm-6" style="padding-left: 0px; padding-right: 0px;">
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
        </div>
    </div>


    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}" style="margin-bottom: 40px;">
        <label class="control-label" for="description">{{ trans('company.create-description') }}:</label>
        <textarea class="form-control" rows="4" name="description" id="description"></textarea>
    </div>

    <h3 style="margin-bottom: 20px;">{{ trans('company.create-contact_person') }}</h3>

    <div>
        <div class="col-sm-6" style="padding-left: 0px;">
            <div class="form-group{{ $errors->has('contact_name') ? ' has-error' : '' }}">
                <label class="control-label" for="contact_name">{{ trans('company.create-contact_name') }}:</label>
                <input type="text" class="form-control required" name="contact_name" id="contact_name" />
            </div>
        </div>
        <div class="col-sm-6" style="padding-left: 0px; padding-right: 0px;">
            <div class="form-group{{ $errors->has('contact_surname') ? ' has-error' : '' }}">
                <label class="control-label" for="contact_surname">{{ trans('company.create-contact_surname') }}:</label>
                <input type="text" class="form-control" name="contact_surname" id="contact_surname" />
            </div>
        </div>
    </div>

    <div>
        <div class="col-sm-6" style="padding-left: 0px;">
            <div class="form-group{{ $errors->has('contact_email') ? ' has-error' : '' }}">
                <label class="control-label" for="contact_email">{{ trans('company.create-contact_email') }}:</label>
                <input type="text" class="form-control required" name="contact_email" id="contact_email" />
            </div>
        </div>

        <div class="col-sm-6" style="padding-left: 0px; padding-right: 0px;">
            <div class="form-group{{ $errors->has('contact_tel') ? ' has-error' : '' }}">
                <label class="control-label" for="contact_tel">{{ trans('company.create-contact_tel') }}:</label>
                <input type="text" class="form-control required" name="contact_tel" id="contact_tel" />
            </div>
        </div>


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