<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', Admin::locale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ Admin::name() }}</title>

        <!-- SB Admin 2 -->
        <link href="{{ asset('vendor/admin/sb-admin-2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('vendor/admin/sb-admin-2/css/sb-admin-2.min.css') }}" rel="stylesheet">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('vendor/admin/css/app.css') }}" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito';
            }
        </style>

        <script>
            window.config = @json($config);
        </script>
    </head>
    <body>
        <div id="app"></div>

        <!-- SB Admin 2 -->
        <script src="{{ asset('vendor/admin/sb-admin-2/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/admin/sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
        <script src="{{ asset('vendor/admin/sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/admin/sb-admin-2/js/sb-admin-2.min.js') }}"></script>

        <script src="{{ asset('vendor/admin/js/app.js') }}"></script>
    </body>
</html>
