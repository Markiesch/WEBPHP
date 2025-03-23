<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="uk-theme-zinc">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Bazaar')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite('node_modules/franken-ui/dist/js/icon.iife.js')
</head>
<body class="font-sans antialiased">
@yield('content')
</body>
</html>
