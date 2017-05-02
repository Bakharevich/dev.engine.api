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
    <link rel="stylesheet" href="http://<?= Request::get('site')->domain ?>/css/admin/simple-sidebar.css" />


    <!-- Include Editor style. -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1/css/froala_style.min.css" rel="stylesheet" type="text/css" />


    <meta name="site_id" content="{{ Request::get('site')->id }}" />
    <meta name="site_locale" content="{{ Request::get('site')->locale }}" />
</head>
<body>

<style>
    BODY {
        padding-top: 70px;
    }
    H1 {
        margin-top: 0px;
        padding-top: 0px;
        margin-bottom: 40px;
    }
</style>

<!-- Sidebar -->
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <a href="#">
                Start Bootstrap
            </a>
        </li>
        <li>
            <a href="#">Dashboard</a>
        </li>
        <li>
            <a href="#">Shortcuts</a>
        </li>
        <li>
            <a href="#">Overview</a>
        </li>
        <li>
            <a href="#">Events</a>
        </li>
        <li>
            <a href="#">About</a>
        </li>
        <li>
            <a href="#">Services</a>
        </li>
        <li>
            <a href="#">Contact</a>
        </li>
    </ul>
</div>
<!-- /#sidebar-wrapper -->


<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://{{ Request::get('site')->domain }}">{!! Request::get('site')->name  !!}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">{{ trans('admin.global.test') }}</a></li>
                <li><a href="/">{{ trans('admin.global.mainpage') }}</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="container">
    <div class="col-sm-2">
        <a href="/admin/companies">{{ trans('admin.companies') }}</a>
    </div>
    <div class="col-sm-10">
        @yield('content')
    </div>
</div>

<script src="http://<?= Request::get('site')->domain ?>/bower_components/jquery/dist/jquery.min.js"></script>
<script src="http://<?= Request::get('site')->domain ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="http://<?= Request::get('site')->domain ?>/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Include Editor JS files. -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.5.1//js/froala_editor.pkgd.min.js"></script>


@yield('scripts')

<script>

</script>
</body>
</html>