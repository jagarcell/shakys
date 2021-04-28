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

        @if(isset($unauthorized_user))
        <div id="unauthorized_action" class="unauthorized_action"><span>Unauthorized action for this user!</span></div>
        @endif
        <div class="collapse navbar-collapse authenticated_user" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav">
                <!-- Authentication Links -->
                @guest
                    <div id="loginDiv" style="display: inline;">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    </div>
                @else
                    <li class="nav-item dropdown">
                        <div style="text-decoration:none; padding-bottom: 10px;" id="navbarDropdown" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </div>
                    </li>    
                    <li class="nav-item dropdown">
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>

        @yield('content')
    </body>
</html>
