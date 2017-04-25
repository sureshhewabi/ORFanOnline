<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
@include('partials.head')
<title>@yield('title')</title>

<style type="text/css">

</style>
</head>
<body>
@include('layouts.header')
@yield('body')
@include('layouts.footer')
</body>
</html>
