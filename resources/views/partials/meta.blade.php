@if (!empty($meta['title']))
    <title>{{ $meta['title'] }}</title>
    <meta name="twitter:title" content="{{ $meta['title'] }}">
    <meta itemprop="name"      content="{{ $meta['title'] }}">
    <meta property="og:title"  content="{{ $meta['title'] }}"/>
@else
    <title>{{ Request::get('site')->name }}</title>
@endif

@if (!empty($meta['keywords']))
    <meta name="keywords" content="{{ $meta['keywords'] }}" />
@else
    <meta name="keywords" content="" />
@endif

@if (!empty($meta['description']))
    <meta name="description"         content="{{ $meta['description'] }}" />
    <meta property="og:description"  content="{{ $meta['description'] }}" />
    <meta itemprop="description"     content="{{ $meta['description'] }}"/>
    <meta name="twitter:description" content="{{ $meta['description'] }}"/>
@else
    <meta name="description" content="" />
@endif

@if (!empty($meta['image']))
    <meta itemprop="image"     content="{{ $meta['image'] }}"/>
    <meta name="twitter:image" content="{{ $meta['image'] }}"/>
    <meta property="og:image"  content="{{ $meta['image'] }}"/>
@else

@endif