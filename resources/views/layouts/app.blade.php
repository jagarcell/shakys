<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        @yield('headsection')
    </head>
    
    <body>
        <div class="page-header">
            <a href="/" class="logo-frame"><img src="images/Shakys.png" loading="lazy" sizes="(max-width: 767px) 100vw, 53vw" srcset="images/Shakys-p-500.png 500w, images/Shakys.png 512w" alt="" class="logo">
            </a>
            <div class="title-frame">
            <div class="text-block">PURCHASES CONTROL SYSTEM</div>
            </div>
        </div>
        <div class="page_title_frame">
            <div class="page_title">@yield('page_title')</div>
        </div>

        @yield('content')
    </body>
</html>
