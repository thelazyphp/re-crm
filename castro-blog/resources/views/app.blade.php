<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="/castro-blog/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/castro-blog/vendor/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="/castro-blog/css/fontastic.css">
        <link rel="stylesheet" href="/castro-blog/vendor/@fancyapps/fancybox/jquery.fancybox.min.css">
        <link rel="stylesheet" href="/castro-blog/css/style.green.css" id="theme-stylesheet">
        <link rel="stylesheet" href="/castro-blog/css/app.css">

        <style>
            body {
                font-family: 'Nunito' !important;
            }
        </style>

        <script src="/castro-blog/vendor/jquery/jquery.min.js"></script>
        <script src="/castro-blog/vendor/popper.js/umd/popper.min.js"> </script>
        <script src="/castro-blog/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="/castro-blog/vendor/jquery.cookie/jquery.cookie.js"> </script>
        <script src="/castro-blog/vendor/@fancyapps/fancybox/jquery.fancybox.min.js"></script>
        <script src="/castro-blog/js/front.js"></script>
        <script defer src="/castro-blog/js/entry-client.js"></script>
    </head>
    <body>
        {!! ssr('js/entry-server.js')
            ->fallback('<div id="app"></div>')
            ->render() !!}
    </body>
</html>
