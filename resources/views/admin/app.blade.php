<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Bcorp Admin Dashboard - @yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="This is a BCorp Admin internal web dashboard application. It is used to display the necessary daily/weekly/monthly information to the C-level executives and their respective employees.">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('images/berjaya-icon.png') }}">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    {{--    <link rel="stylesheet" type="text/css" href="/static/css/tailwind/tailwind.css?1">--}}

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.2.1/dist/alpine.js" defer></script>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <style type="text/css">
        form.user-search{width: 689px; margin: 0 auto 30px;}
        form.user-search input{border: 1px solid #ccc; padding: 7px 10px; width: 100%;}
    </style>

</head>
@if(isset($userName))
<body>
@yield('adminContent')
</body>
@endif
</html>
