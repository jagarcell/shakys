@extends('layouts.app')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-wf-page="605538017156323a2f5ab124" data-wf-site="604d41d40c813292693d08e7">
    <head>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @section('title', 'Supplier Product Locations')

        @section('headsection')
        <link href="/css/shakys.webflow.css" rel="stylesheet" type="text/css">
        <link href="/css/suppliersproductlocations.css" rel="stylesheet" type="text/css">
        <link href="/css/dropzone.css" rel="stylesheet" type="text/css">
        <link href="/css/webflow.css" rel="stylesheet" type="text/css">
        <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
        <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
        <link href="/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
        <link href="/images/webclip.png" rel="apple-touch-icon">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}
        </style>

      <meta name="format-detection" content="telephone=no">
      @endsection
    </head>
    <body class="antialiased bodyClass">
        @section('page_title', 'SUPPLIER PRODUCT LOCATIONS')
        @section('content')

        <!-- THIS IS THE SECTION USED TO ADD A NEW PRODUCT LOCATION-->
        <div id="add_product_location_section" class="product_location_add_frame">
            <div id="action_result_message" class="action_result_message" hidden></div>
            <div id="add_icon_frame" class="add_icon_frame">
                <input type="button" class="add_input" value="+" onclick="addLocationClick(this)">
            </div>
            <div id="add_section_frame" class="product_location_section" hidden>
                <form action="/instoreimgupload" id="product_location_add_pic" class="product_location_pic_frame box_shadow" method="post" enctype="multipart/form-data">
                    @csrf
                </form>
                <div class="product_location_data_entry">
                    <div class="add_location_form_frame w-form">
                        <form id="add_form" class="add_location">
                            <select id="supplier_id" class="product_location_text_field box_shadow w-input">
                                <option value="-1" disabled selected>Select a supplier</option>
                                @foreach($suppliers as $key => $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                @endforeach
                            </select>
                            <input id="location_name" type="text" class="product_location_text_field box_shadow w-input" maxlength="256" name="name" data-name="Name" placeholder="Name" required="">
                            <div class="product_location_add_buttons_frame">
                                <input type="button" value="Create Location" data-wait="Please wait..." class="accept_button box_shadow w-button" onclick="createLocationClick(this)">
                                <input type="button" value="Discard Location" data-wait="Please wait..." class="discard_button box_shadow w-button" onclick="discardLocationClick(this)">
                            </div>
                            <input id="image_to_upload" name="image_path" hidden>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- LIST OF LOCATIONS RECEIVED FROM THE SERVER -->
        <div id="locations_list_wrap">
            @if(count($locations) > 0)
            @foreach($locations as $key => $location)
            <div id="{{$location->id}}" class="product_location_section_wrap">
                <div id="action_result_message" class="action_result_message" hidden></div>
                <div class="product_location_section">
                    <div class="product_location_pic_frame box_shadow">
                        <img src="{{$location->image_path}}" loading="lazy" sizes="(max-width: 128px) 92vw, 128px" srcset="{{$location->image_path}} 128w, {{$location->image_path}} 128w" alt="" class="prodcut_location_pic box_shadow">
                    </div>
                    <div id="supplier_data_edit_frame" class="product_location_data_entry">
                        <div class="product_location_data_edit">
                            <div class="field_wrap">
                                <div class="field_label">Supplier</div>
                                <div class="location_data_field box_shadow">{{$location->supplier_name}}</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Location Name</div>
                                <div class="location_data_field box_shadow">{{$location->name}}</div>
                            </div>
                            <div class="product_location_data_entry_buttons buttons_frame_height">
                                <input type="button" class="edit_button w-button box_shadow" value="Edit" onclick="editButtonClick('{{$location->id}}')">
                                <input type="button" class="delete_button w-button box_shadow" value="Delete" onclick="deleteButtonClick('{{$location->id}}')">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @else
                <div id="empty_list" class="empty_list">THERE ARE NO SUPPLIER'S LOCATIONS REGISTERED!</div>
            @endif    
        </div>

        <!-- THIS IS THE HTML USED TO SHOW A LOCATION SECTION WHEN A NEW LOCATION IS
        CREATED OR AN EXISTING ONE IS EDITED AND UPDATED. THIS WILL BE USED FROM JS-->
        <div id="location_html" hidden>
            <div id="location-id" class="product_location_section_wrap">
                <div id="action_result_message" class="action_result_message" hidden></div>
                    <div class="product_location_section">
                        <div class="product_location_pic_frame box_shadow">
                            <img src="location-image-path" loading="lazy" sizes="(max-width: 479px) 92vw, 256px" srcset="location-image-path 500w, location-image-path 512w" alt="" class="prodcut_location_pic"></div>
                        <div id="supplier_data_edit_frame" class="product_location_data_entry">
                        <div class="product_location_data_edit">
                            <div class="field_wrap">
                                <div class="field_label">Supplier</div>
                                <div class="location_data_field box_shadow">location-supplier-name</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Location Name</div>
                                <div class="location_data_field box_shadow">location-name</div>
                            </div>
                            <div class="product_location_data_entry_buttons">
                                <input type="button" class="edit_button box_shadow w-button" value="Edit" onclick="editButtonClick('location-id')">
                                <input type="button" class="delete_button box_shadow w-button" value="Delete" onclick="deleteButtonClick('location-id')">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <!-- THIS HTML IS USED TO SHOW A LOCATION EDIT SECTION WHEN THE USER CLICKS THE EDIT BUTTON-->
        <div id="location_edit_html" hidden>
            <div id="location-id" class="product_location_section_wrap">
                <div id="action_result_message" class="action_result_message" align-top="true" hidden></div>
                <div id="add_section_frame" class="product_location_section">
                    <form action="/instoreimgupload" id="product_location_add_pic_location-id" class="product_location_pic_frame box_shadow" method="post" enctype="multipart/form-data">
                        @csrf
                    </form>
                    <div class="product_location_data_entry">
                        <div class="add_location_form_frame w-form">
                            <form id="add_form" class="add_location">
                                <div class="field_wrap">
                                    <div class="field_label">Supplier</div>
                                    <select id="supplier_select" class="product_location_text_field box_shadow w-input">
                                        <option value="-1" disabled selected>Select a supplier</option>
                                    </select>
                                </div>
                                <div class="field_wrap">
                                    <div class="field_label">Location Name</div>
                                    <input id="location_name" type="text" class="product_location_text_field box_shadow w-input" maxlength="256" name="name" data-name="Name" placeholder="Name" required="">
                                </div>
                                <div class="product_location_add_buttons_frame">
                                    <input type="button" value="Accept Changes" data-wait="Please wait..." class="accept_button box_shadow w-button" onclick="acceptLocationChangesClick('location-id')">
                                    <input type="button" value="Discard Changes" data-wait="Please wait..." class="discard_button box_shadow w-button" onclick="discardLocationChangesClick('location-id')">
                                </div>
                                <input id="image_to_upload" name="image_path" hidden>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=604d41d40c813292693d08e7" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="/js/webflow.js" type="text/javascript"></script>
        <script src="/js/suppliersproductlocations.js" type="text/javascript"></script>
        <script src="/js/dropzone.js"></script>    
        <script src="/js/garcellLib.js" type="text/javascript"></script>
        <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
        @endsection    
    </body>
</html>
