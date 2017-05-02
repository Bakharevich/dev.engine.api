@extends('layouts.admin')

@section('content')
<style>
    .photos .img {
        padding-bottom: 20px;
    }
</style>

<h1>{{ trans('admin.global.photos') }}</h1>

<form action="/admin/companies_photos/{{ $company->id }}" method="post" class="form-create-company upload-photos" enctype="multipart/form-data">
    @include('errors.formerrors')

    <input type="hidden" name="_method" value="POST" />
    <input type="hidden" name="company_id" value="{{ $company->id }}" />

    {{ csrf_field() }}

    <div class="form-group">
        <label>{{ trans('admin.global.photo') }}</label>
        <input type="file" name="images[]" multiple="multiple" />
        <p class="help-block">{{ trans('admin.companies.many-photos') }}</p>
    </div>

    <div class="form-group">
        <button class="btn btn-primary upload-image" type="submit">{{ trans('common.send') }}</button>
    </div>

    <div class="row photos sortable">
        @foreach ($photos as $photo)
            <div class="col-sm-2 img" data-id="{{ $photo->id }}">
                <img src="{{ $photo->url }}" class="img-responsive img-rounded" style="margin-bottom: 7px;" />
                <button type="button" class="btn btn-default btn-xs delete-photo" data-id="{{ $photo->id }}">
                    <span class="glyphicon glyphicon-remove"></span> Delete
                </button>
            </div>
        @endforeach
    </div>
</form>
@stop

@section('scripts')
    <script>
        $(function(){
            // submit upload
            $('.upload-photos').submit(function(e) {
                e.preventDefault();

                // vars
                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        //alert('success');
                        //console.log(data);
                        location.reload();
                    },
                    error: function (data) {
                        alert('Error during uploading photo');
                        console.log(data);
                    }
                });

            });

            // delete photo
            $(".delete-photo").click(function(e) {
                // get photo id
                var photoId = $(this).data('id');

                // send ajax request
                $.ajax({
                    url: '/api/companies_photos/' + photoId,
                    type: "DELETE",
                    data: {id: photoId},
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        //alert('success');
                        //console.log(data);
                        //location.reload();
                    },
                    error: function (data) {
                        //alert('error');
                        //console.log(data);
                    }
                });

                // remove element from dom
                $(this).parent().remove();
            });


            $( ".sortable" ).sortable({
                revert: true,
                tolerance: 'pointer',
                start: function(event, ui) {
                    console.log("Old position: " + ui.placeholder.index());
                },
                update: function(event, ui) {
                    //console.log("Update: " + ui.placeholder.index());
                },
                stop: function(event, ui) {
                    console.log("Stop: ");
                    var positions = [];

                    $(".photos DIV.img").each(function() {
                        positions.push($(this).data('id'));
                    });

                    console.log(positions);

                    $.ajax({
                        url: '/api/companies_photos/updatepositions',
                        type: "POST",
                        data: {positions: positions},
                        //cache: false,
                        //contentType: false,
                        //processData: false,
                        success: function (data) {
                            //alert('success');
                            //console.log(data);
                            //location.reload();
                        },
                        error: function (data) {
                            //alert('error');
                            //console.log(data);
                        }
                    });
                }
            });
            $( ".sortable" ).disableSelection();
        });
    </script>
@stop