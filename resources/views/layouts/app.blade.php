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
        <link rel="stylesheet" href="{{ asset('/css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('/js/app.js') }}" defer></script>
        @yield('headsection')
    </head>
    
    <body style="position:relative;">
        <div class="page-header hide-this" {{(Auth::user() !== null && Auth::user()->user_type == 'admin') ? '':'hidden'}}>
            <a href="/" class="logo-frame">
                <img src="/images/logo.png" loading="lazy" sizes="(max-width: 767px) 100vw, 53vw" srcset="/images/logo.png 500w, /images/logo.png 512w" alt="" class="logo">
            </a>
            <div class="title-frame">
                <div class="text-block text_shadow">PURCHASES CONTROL SYSTEM</div>
            </div>
        </div>
        <div class="app-menu-wrap hide-this" {{(Auth::user() !== null && Auth::user()->user_type == 'admin') ? '':'hidden'}}>
            <div class="app-menu">
                <div class="app-menu-option">
                    <a href="/">Home</a>
                </div>
                <div class="app-menu-option">
                    <a href="/showpendingorderspanel">Orders</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/listproducts">Products</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/measureunits">Measure Units</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/users">Users</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/suppliers">Suppliers</a>
                </div>    
                <!--div class="app-menu-option">
                    <a href="/productslocations">In-Store Product Locations</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/suppliersproductlocation">Product Locations At Suppliers</a>
                </div -->    
            </div>
        </div>
        <a class="app-mobile-menu" onclick="appMobileMenu()" {{(Auth::user() !== null && Auth::user()->user_type == 'admin') ? '':'hidden'}}>
            <img src="/images/mobile.png">
        </a>
        <div class="app-mobile-menu-options-wrap">
            <div id="app-mobile-menu-options" class="app-mobile-menu-options shadowRight" style="display:none;">
                <div class="app-menu-option">
                    <a href="/">Home</a>
                </div>
                <div class="app-menu-option">
                    <a href="/showpendingorderspanel">Orders</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/listproducts">Products</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/measureunits">Measure Units</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/users">Users</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/suppliers">Suppliers</a>
                </div>    
                <!--div class="app-menu-option">
                    <a href="/productslocations">In-Store Product Locations</a>
                </div>    
                <div class="app-menu-option">
                    <a href="/suppliersproductlocation">Product Locations At Suppliers</a>
                </div -->    
            </div>
        </div>

        <div class="page_title_frame hide-this" {{(Auth::user() !== null && Auth::user()->user_type == 'admin') ? '':'hidden'}}>
            <div class="page_title text_shadow box_shadow">@yield('page_title')</div>
        </div>

        @if(isset($unauthorized_user))
        <div id="unauthorized_action" class="unauthorized_action"><span>Unauthorized action for this user!</span></div>
        @endif
        <div class="collapse navbar-collapse authenticated_user" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav" style="text-shadow: 2px 2px 4px black;position:absolute;top:0;right:5px;">
                <!-- Authentication Links -->
                @guest
                    <div id="loginDiv" style="display: inline; text-decoration:underline;">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    </div>
                @else
                    <li class="nav-item dropdown">
                        <div style="padding-bottom: 10px;" id="navbarDropdown" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </div>
                    </li>    
                    <li class="nav-item dropdown" style="text-decoration:underline;">
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <!--a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a-->
                            <a class="dropdown-item" href="{{ route('login') }}">
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
        <!--div {{(Auth::user() !== null && Auth::user()->user_type == 'admin') ? 'hidden':''}}>
        </div-->
 
        <!-- Scripts -->
        <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=604d41d40c813292693d08e7" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="/js/common.js" type="text/javascript"></script>

        @yield('content')
    </body>
</html>
