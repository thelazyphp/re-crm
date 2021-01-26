<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="/castro-blog/theme/admin/vendor/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="/castro-blog/theme/admin/css/sb-admin-2.min.css">
        <link rel="stylesheet" href="/castro-blog/css/admin/app.css">

        <style>
            body {
                font-family: 'Nunito' !important;
            }
        </style>
    </head>
    <body>
        <div id="app"></div>

        <script src="/castro-blog/theme/admin/vendor/jquery/jquery.min.js"></script>
        <script src="/castro-blog/theme/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="/castro-blog/theme/admin/vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="/castro-blog/theme/admin/js/sb-admin-2.min.js"></script>
        <script defer src="/castro-blog/js/admin/app.js"></script>
    </body>
</html>
