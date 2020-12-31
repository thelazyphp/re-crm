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
        <div class="container">
            <div class="alert alert-danger d-flex mt-3 align-items-center" role="alert">
                <i class="fas mr-3 fa-2x fa-exclamation-triangle"></i>
                <p class="mb-0">Приложение недоступно! <a href="mailto:denis.chaika@hotmail.com" class="alert-link">Обратитесь к специалисту</a>.</p>
            </div>
        </div>
    </body>
</html>
