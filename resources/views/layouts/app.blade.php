<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <meta id="token" name="token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @yield('statics-css')
</head>
<body>
<div class="site_wrapper">
    @yield('menu')
    <div class="clearfix"></div>
    @yield('content')
    <div class="clearfix"></div>
    @yield('footer')
    <div class="clearfix"></div>
    <a href="#" class="scrollup pink-3"></a>
</div>
@yield('statics-js')
</body>
</html>