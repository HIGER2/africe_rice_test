<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
        <title>AfricRice</title>
        @vite(['resources/scss/app.scss','resources/js/app.js'])

    </head>
    <body id="app">


        @yield('content')
    </body>
</html>
