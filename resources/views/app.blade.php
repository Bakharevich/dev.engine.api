<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    @include('partials.meta')
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="http://<?= Request::get('site')->domain ?>/bower_components/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="http://<?= Request::get('site')->domain ?>/bower_components/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="http://<?= Request::get('site')->domain ?>/bower_components/jquery-ui/themes/base/jquery-ui.min.css" />

    <link rel="stylesheet" href="http://<?= Request::get('site')->domain ?>/css/custom_resolutions.css" />
    <link rel="stylesheet" href="http://<?= Request::get('site')->domain ?>/css/global.css" />
    <link rel="stylesheet" href="http://<?= Request::get('site')->domain ?>/css/footer.css" />
    <link rel="stylesheet" href="http://<?= Request::get('site')->domain ?>/css/menu.css" />
    <link rel="stylesheet" href="http://<?= Request::get('site')->domain ?>/css/header-inner.css" />
    <link rel="stylesheet" href="http://<?= Request::get('site')->domain ?>/css/index.css" />
    <link rel="stylesheet" href="http://<?= Request::get('site')->domain ?>/css/category.css" />
    <link rel="stylesheet" href="http://<?= Request::get('site')->domain ?>/css/company.css" />

    <meta name="google-site-verification" content="aFNFfQ9ZfnEtfYIV2_Q1FGjANTOr08KgMxB8AjNfOng" />
    <meta name="msvalidate.01" content="0154515FE32C8F8EF4F3F250CFB1AF24" />
    <meta name="site_id" content="{{ Request::get('site')->id }}" />
</head>
<body>
    @if (Route::currentRouteName() != "index")
        @include('partials.header-inner')
        @include('partials.menu')
    @else
        @include('partials.header-main')
    @endif

    @yield('content')

    @include('partials.footer')

    <script src="http://<?= Request::get('site')->domain ?>/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="http://<?= Request::get('site')->domain ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="http://<?= Request::get('site')->domain ?>/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <script src="http://<?= Request::get('site')->domain ?>/js/main.js"></script>
    <script src="http://<?= Request::get('site')->domain ?>/js/ajax.js"></script>

    @yield('scripts')

    {!! Request::get('site')->html_code !!}

    <script>
        // autocomplete search
        $(document).ready(function() {
            $( "#site-search" ).autocomplete({
                source: function( request, response ) {
                    var site_id = $("META[name='site_id']").attr('content');

                    $.ajax( {
                        url: "/api/companies/search",
                        dataType: "json",
                        minLength: 2,
                        search: function(){$(this).addClass('ui-autocomplete-loading');},
                        open: function(){$(this).removeClass('ui-autocomplete-loading');},
                        data: {
                            keyword: request.term,
                            site_id: site_id
                        },
                        success: function( data ) {
                            response($.map(data, function(item) {
                                return {
                                    id: item.id,
                                    label: item.title,
                                    type: item.type,
                                    url: item.url
                                }
                            }));
                        }
                    } );
                },
                minLength: 1,
                select: function( event, ui ) {
                    //console.log( "Selected: " + ui.item.type + " aka " + ui.item.url );
                    window.location.href = ui.item.url;
                }
            } );
        });

        // global form validation
        if (typeof formName === 'undefined') formName = 'FORM';

        $(formName).submit(function(e){
            e.preventDefault();

            var isSubmit = true;

            $(formName + " .required").each(function(index, val){
                var res = $(val).val();

                if (!res) {
                    isSubmit = false;
                    $(this).parent().addClass('has-error');
                }
                else {
                    $(this).parent().removeClass('has-error');
                }
            });

            if (isSubmit) this.submit();
        });
    </script>
</body>
</html>