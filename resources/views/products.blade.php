@extends('layouts.app')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-wf-page="605538017156323a2f5ab124" data-wf-site="604d41d40c813292693d08e7">
    <head>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @section('title', 'Products')

        @section('headsection')
        <link href="/css/shakys.webflow.css" rel="stylesheet" type="text/css">
        <link href="/css/products.css" rel="stylesheet" type="text/css">
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
        @section('page_title', 'PRODUCTS')
        @section('content')
     
        <!-- THIS IS THE SECTION THAT HOLDS THE HTML FOR THE ADDING ICON AND THE ADDING FORM ACTION -->
        <div id="add_section_wrap" class="add_section_wrap">
            <!-- THIS IS WHERE ACTION RESULT MESSAGES WILL BE SHOWN -->
            <div id="action_result_message" class="action_result_message" hidden>
            </div>

            <!-- THIS IS THE ADD ICON -->
            <div id="add_icon_frame" class="add_icon_frame">
                <div class="add_icon">
                    <input type="button" class="add_input" value="+" onclick="addIconClick()">
                </div>
            </div>

            <!-- THIS IS THE SECTION THAT IS USED TO ENTER DATA TO BE CREATED -->
            <!-- IT WILL BE SHOWN WHEN THE USER CLICKS ON THE ADD ICON -->
            <div id="add_section_frame" class="section" style="display:none;">
                <div class="pic_frame">
                    <form id="product_image" action="/productimgupload" method="post" enctype="multipart/form-data" class="box_shadow">
                        @csrf
                    </form>
                </div>
                <div class="data_entry">
                    <div class="add_form_frame w-form">
                        <form id="product_form" class="add_frame">
                            <input id="code" type="text" class="text_field box_shadow w-input" maxlength="50" placeholder="Product Code" required="">
                            <input id="description" type="text" class="text_field box_shadow w-input" maxlength="150" placeholder="Product Description" required="">
                            <input id="days_to_count" type="number" class="text_field box_shadow w-input" maxlength="256" name="DaysToCount" data-name="DaysToCount" placeholder="Days To Count" required="">
                            <input id="measure_unit" type="text" class="text_field box_shadow w-input" maxlength="256" name="measure_unit" data-name="measure_unit" placeholder="Measure Unit" required="">
                            <select id="default_supplier" class="text_field box_shadow w-input" maxlength="256" name="default_supplier" data-name="default_supplier">
                                <option value="-1" selected="" placeholder="Default Supplier (Optional)">Default Supplier (Optional)</option>
                            </select>
                            <div class="add_buttons_frame">
                                <input type="button" value="Create Product" data-wait="Please wait..." class="edition_button accept_button box_shadow w-button" onclick="createButtonClick()">
                                <input type="button" value="Discard Product" data-wait="Please wait..." class="edition_button discard_button box_shadow w-button" onclick="discardButtonClick()">
                            </div>
                            <input id="image_to_upload" hidden>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- THIS IS THE LIST OF PRODUCTS IN THE DATABASE RECEIVED FROM THE SERVER -->
        <div id="products_list_wrap">    
            @if(count($products) > 0)
            @foreach($products as $key => $product)
            <div id="{{$product->id}}">
                <div id="action_result_message" class="action_result_message" hidden></div>
                <div class="section">
                    <div class="pic_frame">
                        <img src="{{$product->image_path}}" loading="lazy" sizes="(max-width: 479px) 80vw, 256px" srcset="{{$product->image_path}} 500w, {{$product->image_path}} 512w" alt="" class="picture box_shadow">
                    </div>
                    <div id="supplier_data_edit_frame" class="data_entry">
                        <div class="data_edit">
                            <div class="field_wrap">
                                <div class="field_label">Product Code</div>
                                <div id="code" class="data_field box_shadow">{{$product->internal_code}}</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Description</div>
                                <div id="description" class="data_field box_shadow">{{$product->internal_description}}</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Days To Count</div>
                                <div id="days_to_count" class="data_field box_shadow">{{$product->days_to_count}}</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Measure Unit</div>
                                <div id="measure_unit" class="data_field box_shadow">{{$product->measure_unit}}</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Default Supplier</div>
                                <div id="default_supplier" class="data_field box_shadow">{{$product->default_supplier_name}}</div>
                            </div>
                            <div class="data_entry_buttons">
                                <div>
                                    <input type="button" class="edit_button box_shadow w-button" value="Edit" onclick="editButtonClick('{{$product->id}}')">
                                </div>
                                <div class="bottom_button">
                                    <input type="button" class="supplier_product_button box_shadow w-button" value="Suppliers" onclick="suppliersButtonClick('{{$product->id}}')">
                                </div>
                                <div class="bottom_button">
                                    <input type="button" class="delete_button box_shadow w-button" value="Delete" onclick="deleteButtonClick('{{$product->id}}')">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div id="empty_list" class="empty_list">THERE ARE NOT PRODUCTS REGISTERED YET!</div>
            @endif
        </div>

        <!-- THIS IS THE HTML TO BE USED BY JS TO SHOW A CREATED OR UPDATED PRODUCT -->
        <div id="section_html" style="display:none;">
            <div id="action_result_message" class="action_result_message" hidden></div>
            <div class="section">
                <div class="pic_frame">
                    <img src="image-path" loading="lazy" sizes="(max-width: 479px) 80vw, 256px" srcset="image-path 500w, image-path 512w" alt="" class="picture box_shadow">
                </div>
                <div id="supplier_data_edit_frame" class="data_entry">
                    <div class="data_edit">
                        <div class="field_wrap">
                            <div class="field_label">Product Code</div>
                            <div id="code" class="data_field box_shadow">internal-code</div>
                        </div>
                        <div class="field_wrap">
                            <div class="field_label">Description</div>
                            <div id="description" class="data_field box_shadow">internal-description</div>
                        </div>
                        <div class="field_wrap">
                            <div class="field_label">Days To Count</div>
                            <div id="days_to_count" class="data_field box_shadow">days-to-count</div>
                        </div>
                        <div class="field_wrap">
                            <div class="field_label">Measure Unit</div>
                            <div id="measure_unit" class="data_field box_shadow">measure-unit</div>
                        </div>
                        <div class="field_wrap">
                            <div class="field_label">Default Supplier</div>
                            <div id="default_supplier" class="data_field box_shadow">default-supplier-name</div>
                        </div>
                        <div class="data_entry_buttons">
                            <div>
                                <input type="button" class="edit_button box_shadow w-button" value="Edit" onclick="editButtonClick('section_html')">
                            </div>
                            <div class="bottom_button">
                                <input type="button" class="supplier_product_button box_shadow w-button" value="Suppliers" onclick="suppliersButtonClick('section_html')">
                            </div>
                            <div class="bottom_button">
                                <input type="button" class="delete_button box_shadow w-button" value="Delete" onclick="deleteButtonClick('section_html')">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     
        <!-- THIS IS THE HTML TO BE SHOWN FROM JS TO EDIT A PRODUCT WHEN THE USER CLICKS EDIT -->
        <div id="add_section_html" class="edit_section_html" style="display:none;">
            <div id="action_result_message" class="action_result_message" hidden></div>
            <div class="section">
                <div class="pic_frame">
                    <form id="product-image" action="/productimgupload" method="post" enctype="multipart/form-data" class="box_shadow">
                        @csrf
                    </form>
                </div>
                <div class="data_entry">
                    <div class="add_form_frame w-form">
                        <form id="product_form" class="add_frame">
                            <div class="field_wrap">
                                <div class="field_label">Product Code</div>
                                <input id="code" value="text" type="text" class="text_field box_shadow w-input" maxlength="50" name="Code" placeholder="Product Code" required="">
                            </div>    
                            <div class="field_wrap">
                                <div class="field_label">Description</div>
                                <input id="description" type="text" class="text_field box_shadow w-input" maxlength="150" name="Description" placeholder="Product Description" required="">
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Days To Count</div>
                                <input id="days_to_count" type="text" class="text_field box_shadow w-input" maxlength="256" name="DaysToCount" placeholder="Days To Count" required="">
                            </div>
                            <div class="field_wrap">
                            <div class="field_label">Measure Unit</div>
                                <input id="measure_unit" type="text" class="text_field box_shadow w-input" maxlength="256" name="measure_unit" placeholder="Measure Unit" required="">
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Default Supplier</div>
                                <select id="default_supplier" class="text_field box_shadow w-input" maxlength="256" name="default_supplier" data-name="default_supplier">
                                    <option value="-1" selected="" placeholder="Default Supplier (Optional)">Default Supplier (Optional)</option>
                                </select>
                            </div>
                            <div class="add_buttons_frame">
                                <input type="button" value="Accept Changes" data-wait="Please wait..." class="accept_button box_shadow w-button" onclick="acceptEditChanges('product-id')">
                                <input type="button" value="Discard Changes" data-wait="Please wait..." class="discard_button box_shadow w-button" onclick="discardEditChanges('product-id')">
                            </div>
                            <input id="image_to_upload" hidden>
                        </form>
                    </div>
                </div>
            </div>
        </div>
     
        <!-- THIS IS THE HTML TO BE SHOWN FROM JS TO LINK A PRODUCT TO A SUPPLIER WHEN THE USER CLICKS EDIT -->
        <div id="link_product_section_html" class="edit_section_html" style="display:none;">
            <div id="action_result_message" class="action_result_message" hidden></div>
            <div class="section">
                <div class="pic_frame">
                    <form id="product-image" action="/productimgupload" method="post" enctype="multipart/form-data" class="box_shadow">
                        @csrf
                    </form>
                </div>
                <div class="data_entry">
                    <div class="add_form_frame w-form">
                        <form id="product_form" class="add_frame">
                            <div class="field_wrap">
                                <div class="field_label">Product Code</div>
                                <input id="code" value="text" type="text" class="text_field box_shadow w-input" disabled>
                            </div>    
                            <div class="field_wrap">
                                <div class="field_label">Description</div>
                                <input id="description" type="text" class="text_field box_shadow w-input" disabled>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Supplier</div>
                                <select id="default_supplier" class="text_field box_shadow w-input" maxlength="256" name="default_supplier" data-name="default_supplier">
                                    <option value="-1" selected="" placeholder="Default Supplier (Optional)">Default Supplier (Optional)</option>
                                </select>
                            </div>
                            <div class="add_buttons_frame">
                                <input type="button" value="Accept Changes" data-wait="Please wait..." class="accept_button box_shadow w-button" onclick="acceptProductChanges('product-id')">
                                <input type="button" value="Discard Changes" data-wait="Please wait..." class="discard_button box_shadow w-button" onclick="discardProductChanges('product-id')">
                            </div>
                            <input id="image_to_upload" hidden>
                        </form>
                    </div>
                </div>
            </div>
        </div>
     
        <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=604d41d40c813292693d08e7" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="/js/webflow.js" type="text/javascript"></script>
        <script src="/js/products.js" type="text/javascript"></script>
        <script src="/js/dropzone.js"></script>    
        <script src="/js/garcellLib.js"></script>
        <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
        @endsection
    </body>
</html>
