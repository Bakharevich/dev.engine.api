<!DOCTYPE html>
<html lang="{{ Request::get('site')->locale }}" xmlns="http://www.w3.org/1999/html">
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
    <meta name="site_locale" content="{{ Request::get('site')->locale }}" />
</head>
<body>

<style>
    BODY {
        padding-top: 0px !important;
    }
    .index-bg {
        background: linear-gradient(
                to bottom,
                rgba(30, 0, 0, 0.4),
                rgba(30, 0, 0, 0.4)
        ), url({{ Request::get('site')->media_url }}/backgrounds/index.jpg);
        background-size: cover;
        background-position: left top !important;
        padding: 30px 0px 225px 0px;
        margin-bottom: 30px;
    }
    .index-bg {
        padding-bottom: 0px !important;
        margin-bottom: 0px !important;
    }
    FOOTER.site-footer {
        color: #CCC;
        border: 0px;
    }
    FOOTER.site-footer A {
        color: #e8e8e8 !important;
    }
</style>

<div class="index-bg">
    @yield('content')

    @include('partials.footer')
</div>


<script src="http://<?= Request::get('site')->domain ?>/bower_components/jquery/dist/jquery.min.js"></script>
<script src="http://<?= Request::get('site')->domain ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="http://<?= Request::get('site')->domain ?>/bower_components/jquery-ui/jquery-ui.min.js"></script>
<script src="http://<?= Request::get('site')->domain ?>/js/main.js"></script>
<script src="http://<?= Request::get('site')->domain ?>/js/functions.js"></script>
<script src="http://<?= Request::get('site')->domain ?>/js/ajax.js"></script>

@yield('scripts')

<script>
    $(function() {
        console.log($(window).height());
        $(".index-bg").height($(window).height());
    })
</script>
</body>
</html>