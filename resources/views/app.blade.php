<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <title>Web.engide.dev</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.min.css" />

    <link rel="stylesheet" href="/css/menu.css" />
</head>
<body>
    @include('partials.header')

    @if (Route::currentRouteName() != "index")
        @include('partials.menu')
    @else

    @endif

    @yield('content')

    <script src="/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    @yield('scripts')
</body>
</html>