<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-wf-page="605538017156323a2f5ab124" data-wf-site="604d41d40c813292693d08e7">
    <head>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="/css/shakys.webflow.css" rel="stylesheet" type="text/css">
        <link href="/css/dropzone.css" rel="stylesheet" type="text/css">
        <link href="/css/webflow.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('/css/app.css') }}">

        <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
        <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
        <link href="/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
        <link href="/images/webclip.png" rel="apple-touch-icon">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    </head>
    <body class="antialiased bodyClass">
        <div id="measureunits-app">
            <pageheader appname="PURCHASES CONTROL SYSTEM" pagetitle="MEASURE UNITS"></pageheader>
            <measureunitadd
                :show="this.new"
                v-on:discard-button-click="discardButtonClick" 
                v-on:create-button-click="createButtonClick" 
                v-on:add-icon-click="addIconClick" 
                v-on:search-click="searchClick"></measureunitadd>
            <reportresult 
                :report="this.reportAction" 
                :resultmessage="this.resultMessage" 
                :iserrormessage="this.isErrorMessage"
                :messagetimeout="this.timeOut" 
                v-on:ok-click="messageOk"></reportresult>
            <measureunitedit 
                v-for="(measureunit, index) in measureunits" 
                :key="measureunit.id" 
                :measureunit="measureunit"
                :verifyBeforeDelete="verifyBeforeDelete"
                :index="index"
                v-on:in-use="inUse"
                v-on:deleted="deleted"
                v-on:accept-edit="acceptEdit"></measureunitedit>
        </div>

        <!-- HERE OUR SCRIPTS -->
        <script src="{{ mix('js/measureunitsapp.js') }}" type="text/javascript"></script>

        <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=604d41d40c813292693d08e7" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="/js/webflow.js" type="text/javascript"></script>
        <!--script src="/js/measureunits.js" type="text/javascript"></script-->
        <script src="/js/dropzone.js"></script>    
        <script src="/js/garcellLib.js"></script>
        <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->

    </body>
</html>
