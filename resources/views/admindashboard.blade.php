<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-wf-page="605538017156323a2f5ab124" data-wf-site="604d41d40c813292693d08e7">
    <head>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- OUR STYLES -->
        <link href="/css/shakys.webflow.css" rel="stylesheet" type="text/css">
        <link href="/css/vue.css" rel="stylesheet" type="text/css">
        <link href="/css/webflow.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('/css/app.css') }}">


        <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
        <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon">
        <link href="images/webclip.png" rel="apple-touch-icon">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <meta name="format-detection" content="telephone=no">

        <!-- HERE THE VENDORS' SCRIPTS -->
        <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=604d41d40c813292693d08e7" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <!--script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script-->
    </head>
    <body class="antialiased bodyClass">
        <div id="app">
            <pageheader appname="PURCHASES CONTROL SYSTEM" pagetitle="ADMIN PANEL"></pageheader>
            <adminpagecontent></adminpagecontent>
        </div>

        <!-- HERE OUR SCRIPTS -->
        <script src="{{ mix('js/admindashboardapp.js') }}" type="text/javascript"></script>
        <script src="/js/admindashboard.js" type="text/javascript"></script>

    </body>
</html>
