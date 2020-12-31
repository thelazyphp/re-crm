<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="theme-color" content="#3b5998">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset(env('APP_PATH').'/css/app.css') }}" rel="stylesheet">
    </head>

    <body>
        <noscript>
            <strong>We're sorry but application doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
        </noscript>

        <div id="app"></div>

        <!-- Scripts -->
        <script src="{{ asset(env('APP_PATH').'/js/main.js') }}"></script>
    </body>
</html>
