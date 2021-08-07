@extends('layouts.app')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-wf-page="605538017156323a2f5ab124" data-wf-site="604d41d40c813292693d08e7">
    <head>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @section('title', 'Suppliers')

        @section('headsection')
        <link href="/css/shakys.webflow.css" rel="stylesheet" type="text/css">
        <link href="/css/suppliers.css" rel="stylesheet" type="text/css">
        <link href="/css/dropzone.css" rel="stylesheet" type="text/css">
        <link href="/css/webflow.css" rel="stylesheet" type="text/css">
        <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
        <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
        <link href="/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
        <link href="/images/webclip.png" rel="apple-touch-icon">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
      @endsection
    </head>
    <body class="antialiased bodyClass">
        @section('page_title', 'SUPPLIERS')
        @section('content')
        <!-- HTML TO SHOW THE SUPPLIERS ADD FORM --> 
        <div id="add_section_div" class="add_section_div">

            <!-- THIS IS THE ADD ICON -->
            <div id="add_icon_frame" class="add_icon_frame">
                <div class="all_products_search_frame">
                    <input id="supplier_search_text" type="text" class="all_products_search_bar" placeholder="Enter your search here">
                        <div class="search_product_icon">
                            <img src="/images/MagnifierBk.png">
                            <input type="button" class="all_products_search_button" onclick="supplierSearchClick()">
                        </div>
                    </div>
                    <div class="supplier_add_icon">
                        <input type="button" class="add_input" value="+" onclick="supplierAddClick()">
                    </div>
                </div>
            </div>

            <!--div id="supplier_add_icon" class="supplier_add_icon">
                <input type="button" class="supplier_add_input" value="+" onclick="supplierAddClick()">
            </div-->
            <div id="action_result_message" class="action_result_message" hidden>action_result_message</div>
            <div id="supplier_add_section" style="display:none">
                <div class="supplier_add_section">
                    <div class="pic_wrap">
                        <form id="supplier_image" action="/supplierimgupload" method="post" enctype="multipart/form-data" class="supplier_pic_frame box_shadow">
                            @csrf
                        </form>
                    </div>
                    <div class="supplier_data_entry">
                        <div class="supplier_data_form_block w-form">
                            <form id="supplier_data_entry_form_add" class="supplier_data_form">
                                @csrf
                                <input id="supplier_code_entry" type="text" class="supplier_entry w-input" maxlength="256" name="code" data-name="Code" placeholder="Code" id="supplier_code" required="">
                                <input id="supplier_email_entry" type="email" class="supplier_entry w-input" maxlength="256" name="email" data-name="Email" placeholder="Email" id="supplier_email">
                                <div id="supplier_email_taken" class="supplier_email_taken" style="display:none;">This email has been taken!</div>
                                <input id="supplier_name_entry" type="text" class="supplier_entry w-input" maxlength="256" name="name" data-name="Name" placeholder="Name" id="supplier_name" required="">
                                <input id="supplier_address_entry" type="text" class="supplier_entry w-input" maxlength="256" name="supplier_address" data-name="supplier_address" placeholder="Address" id="supplier_address">
                                <input id="supplier_phone_entry" type="tel" class="supplier_entry w-input" maxlength="256" name="supplier_phone" data-name="supplier_phone" placeholder="Phone" id="supplier_phone">
                                <select id="supplier_pickup_entry" type="text" class="supplier_entry w-input" maxlength="256" name="supplier_pickup_delivery" data-name="supplier_pickup_delivery" placeholder="Pickup" id="supplier_pickup_delivery">
                                    <option value="pickup" selected>Pickup</option>
                                    <option value="delivery">Delivery</option>
                                </select>
                                <input id="supplier_image_to_upload" hidden>
                                <div class="supplier_data_form_buttons">
                                    <input type="button" value="Create" data-wait="Please wait..." class="edition_button accept_button box_shadow w-button" onclick='newSupplier(this)'>
                                    <input type="button" value="Discard" data-wait="Please wait..." class="edition_button discard_button discard_button box_shadow w-button" onclick='discardNewSupplier()'>
                                </div>
                                <div id="supplier_add_error" class="supplier_add_error" hidden>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HTML TO BE ADDED FOR THE EDIT ACTION -->
        <div id="supplier_add_section_html" hidden>
            <div class="section_sub_wrap">
                <div class="supplier_section">
                    <form id="supplier-image" action="/supplierimgupload" method="post" enctype="multipart/form-data" class="supplier_pic_frame box_shadow">
                        @csrf
                    </form>
                    <div class="supplier_data_entry">
                        <div class="supplier_data_form_block w-form">
                            <form id="supplier_data_entry_form" class="supplier_data_form">
                                @csrf
                                <div class="field_wrap">
                                    <div class="field_label">Email</div>
                                    <input id="edit_supplier_email_entry" type="email" class="supplier_entry w-input" maxlength="256" name="email" data-name="Email" placeholder="Email" id="supplier_email" required="">
                                </div>
                                <div id="supplier_email_taken" class="supplier_email_taken" style="display:none;">This email has been taken!</div>
                                <div class="field_wrap">
                                    <div class="field_label">Name</div>
                                    <input id="edit_supplier_name_entry" type="text" class="supplier_entry w-input" maxlength="256" name="name" data-name="Name" placeholder="Name" id="supplier_name" required="">
                                </div>
                                <div class="field_wrap">
                                    <div class="field_label">Address</div>
                                    <input id="edit_supplier_address_entry" type="text" class="supplier_entry w-input" maxlength="256" name="supplier_address" data-name="supplier_address" placeholder="Address" id="supplier_address">
                                </div>
                                <div class="field_wrap">
                                    <div class="field_label">Phone</div>
                                    <input id="edit_supplier_phone_entry" type="tel" class="supplier_entry w-input" maxlength="256" name="supplier_phone" data-name="supplier_phone" placeholder="Phone" id="supplier_phone">
                                </div>
                                <div class="field_wrap">
                                    <div class="field_label">Shipping</div>
                                    <select id="edit_supplier_pickup_entry" type="text" class="supplier_entry w-input" maxlength="256" name="supplier_pickup_delivery" data-name="supplier_pickup_delivery" placeholder="Pickup" id="supplier_pickup_delivery">
                                        <option value="pickup" selected>Pickup</option>
                                        <option value="delivery">Delivery</option>
                                    </select>
                                </div>
                                <input id="edit_supplier_image_to_upload" hidden>
                                <div class="supplier_data_form_buttons">
                                    <input type="button" value="Accept" data-wait="Please wait..." class="edition_button accept_button box_shadow w-button" onclick='acceptChanges(this)'>
                                    <input type="button" value="Discard" data-wait="Please wait..." class="edition_button discard_button discard_button box_shadow w-button" onclick='discardChanges(this)'>
                                </div>
                                <div id="supplier_add_error" class="supplier_add_error" style="display:none">
                                        Test Error
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="action_result_message" class="action_result_message" hidden>action_result_message</div>
            </div>
        </div>

        <!-- THIS IS THE LIST OF REGISTERED SUPPLIERS -->
        <div id="edit_sections_div">
            @if(isset($suppliers) && count($suppliers) > 0)
            @foreach($suppliers as $key => $supplier)
            <div id="{{$supplier->id}}" class="supplier_section_wrap">
                <div id="action_result_message" class="action_result_message" hidden>action_result_message</div>
                <div class="supplier_section">
                    <div class="supplier_pic_frame box_shadow">
                        <img src="{{$supplier->image_path}}" loading="lazy" sizes="(max-width: 479px) 92vw, 256" srcset="{{$supplier->image_path}} 256w, {{$supplier->image_path}} 256w" alt="" class="supplier_pic">
                    </div>
                    <div id="supplier_data_edit_frame" class="supplier_data_entry">
                        <div class="supplier_data_edit">
                            <div class="field_wrap">
                                <div class="field_label">Code</div>
                                <div id="supplier_email" class="supplier_data_field box_shadow">{{$supplier->code}}</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Email</div>
                                <div id="supplier_email" class="supplier_data_field box_shadow">{{$supplier->email}}</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Name</div>
                                <div id="supplier_name" class="supplier_data_field box_shadow">{{$supplier->name}}</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Address</div>
                                <div id="supplier_address" class="supplier_data_field box_shadow">{{$supplier->address}}</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Phone</div>
                                <div id="supplier_phone" class="supplier_data_field box_shadow">{{$supplier->phone}}</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Shipping</div>
                                <div id="supplier_pickup" class="supplier_data_field box_shadow">{{$supplier->pickup}}</div>
                            </div>
                            <div class="supplier_data_entry_buttons">
                                <a class="add_user_button edit_button box_shadow edit w-button" onclick="editClick(this)">Edit</a>
                                <a class="add_user_button delete_button box_shadow delete edit w-button" onclick="deleteClick(this)">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div id="empty_list" class="empty_list">THERE ARE NOT SUPPLIERS TO SHOW!</div>
            @endif
        </div>

        <!-- HTML ADDED FROM JAVASCRIPT WHEN A NEW SUPPLIER IS CREATED OR UPDATED-->
        <div id="supplier_edit_section" hidden>
            <div id="supplier-id" class="supplier_section_wrap">
                <div id="action_result_message" class="action_result_message" hidden>action_result_message</div>
                <div class="supplier_section">
                    <div class="supplier_pic_frame box_shadow">
                        <img src="supplier-image-path" loading="lazy" sizes="(max-width: 479px) 92vw, 256" srcset="supplier-image-path 256w, supplier-image-path 256w" alt="" class="supplier_pic">
                    </div>
                    <div id="supplier_data_edit_frame" class="supplier_data_entry">
                        <div class="supplier_data_edit">
                            <div class="field_wrap">
                                <div class="field_label">Code</div>
                                <div id="supplier_code" class="supplier_data_field box_shadow">supplier-code</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Email</div>
                                <div id="supplier_email" class="supplier_data_field box_shadow">supplier-email</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Name</div>
                                <div id="supplier_name" class="supplier_data_field box_shadow">supplier-name</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Address</div>
                                <div id="supplier_address" class="supplier_data_field box_shadow">supplier-address</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Phone</div>
                                <div id="supplier_phone" class="supplier_data_field box_shadow">supplier-phone</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Shipping</div>
                                <div id="supplier_pickup" class="supplier_data_field box_shadow">supplier-pickup</div>
                            </div>
                            <div class="supplier_data_entry_buttons">
                                <a class="add_user_button edit_button box_shadow edit w-button" onclick="editClick(this)">Edit</a>
                                <a class="add_user_button delete_button box_shadow delete edit w-button" onclick="deleteClick(this)">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=604d41d40c813292693d08e7" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="/js/webflow.js" type="text/javascript"></script>
        <script src="/js/suppliers.js" type="text/javascript"></script>
        <script src="/js/dropzone.js"></script>    
        <script src="/js/garcellLib.js" type="text/javascript"></script>
        <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
        @endsection    
    </body>
</html>
