<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SOLID') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" />
</head>

<body>
    <div class="container">
        <img src="{{ asset('assets/images/backgrounds/header.svg') }}" alt="" class="img-verify-email-header">

        @yield('content')

        <img src="{{ asset('assets/images/backgrounds/footer.svg') }}" alt="" class="img-verify-email-footer">
    </div>
</body>
</html>
