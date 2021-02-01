<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="/nca/theme/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

        <!-- Styles -->
        <link href="/nca/theme/css/sb-admin-2.min.css" rel="stylesheet">
        <link href="/nca/css/app.css" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito';
            }
        </style>
    </head>
    <body>
        <div id="app"></div>

        <script src="/nca/theme/vendor/jquery/jquery.min.js"></script>
        <script src="/nca/theme/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="/nca/theme/vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="/nca/theme/js/sb-admin-2.min.js"></script>
        <script src="/nca/js/app.js"></script>
    </body>
</html>
