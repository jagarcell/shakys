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
     
        <!-- THIS IS THE SECTION THAT HOLDS THE HTML FOR THE ADDING ICON AND THE ADDING ACTION FORM -->
        <div id="add_section_wrap" class="add_section_wrap">
            <!-- THIS IS WHERE ACTION RESULT MESSAGES WILL BE SHOWN -->
            <div id="action_result_message" class="action_result_message" hidden>
            </div>

            <!-- THIS IS THE ADD ICON -->
            <div id="add_icon_frame" class="add_icon_frame">
                <div class="all_products_search_frame">
                    <input id="product_search_text" type="text" class="all_products_search_bar" placeholder="Enter your search here">
                    <div class="search_product_icon">
                        <img src="/images/MagnifierBk.png">
                        <input type="button" class="all_products_search_button" onclick="productSearchClick()">
                    </div>
                </div>
                <div class="add_icon" onclick="addIconClick(this)">
                    <a>+</a>
                </div>
                <!--div class="add_icon">
                    <input type="button" class="add_input" value="+" onclick="addIconClick(this)">
                </div-->
            </div>

            <!-- THIS IS THE SECTION THAT IS USED TO ENTER DATA TO BE CREATED -->
            <!-- IT WILL BE SHOWN WHEN THE USER CLICKS ON THE ADD ICON -->
            <div id="add_section_frame_wrap">
                <div id="add_section_frame" class="add_section" style="display:none;">
                    <div class="pic_frame">
                        <form id="product_image" action="/productimgupload" method="post" enctype="multipart/form-data" class="box_shadow">
                            @csrf
                        </form>
                    </div>
                    <div class="data_entry">
                        <div class="add_form_frame w-form">
                            <form class="product_form add_frame">
                                <div class="product_add_frame">
                                    <div class="add_frame_section">
                                        <div class="field_wrap">
                                            <input type="text" class="code text_field box_shadow w-input" maxlength="50" placeholder="Product Code">
                                        </div>
                                        <div class="field_wrap">
                                            <input type="text" class="description text_field box_shadow w-input" maxlength="150" placeholder="Product Description" required="">
                                        </div>
                                        <div class="field_wrap">
                                            <input type="number" class="days_to_count text_field box_shadow w-input" min="0" placeholder="Days To Count" required="">
                                        </div>
                                    </div>
                                    <div class="add_frame_section">
                                        <div class="field_wrap" style="display:flex;">
                                            <div class="measure_unit_input">
                                                <input type="text" class="measure_unit text_field box_shadow w-input" placeholder="Default Measure Unit" disabled>
                                            </div>
                                            <div class="default_measure_unit_button_frame">
                                                <input type="button" value="Units" class="edition_button accept_button box_shadow w-button default_measure_unit_button" onclick="measuresButtonClick(-1, this)">
                                            </div>
                                        </div>
                                        <div class="field_wrap">
                                            <select class="default_supplier text_field box_shadow w-input">
                                                <option value="-1" selected="" placeholder="Default Supplier (Optional)">Default Supplier (Optional)</option>
                                            </select>
                                        </div>
                                        <div class="field_wrap">
                                            <select class="plan_type_select text_field box_shadow w-input" onchange="planTypeChanged('add_section_frame')" title="TYPE 1 = PRODUCT MUST BE COUNTED&#013TYPE 2 = PRODUCT WON'T BE COUNTED">
                                                <option value="-1" selected="" disabled>Select a plan type ...</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="field_wrap" style="display:flex;">
                                    <div class="add_buttons_frame">
                                        <input type="button" value="Create" data-wait="Please wait..." class="edition_button accept_button  box_shadow w-button" onclick="createButtonClick(this)">
                                        <input type="button" value="Discard" data-wait="Please wait..." class="edition_button discard_button box_shadow w-button" onclick="discardButtonClick()">
                                    </div>
                                </div>

                                <input class="image_to_upload" hidden>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- THIS IS THE LIST OF PRODUCTS IN THE DATABASE RECEIVED FROM THE SERVER -->
        <div id="products_list_wrap">
            @if(count($products) > 0)
            @foreach($products as $key => $product)
            <div id="{{$product->id}}" class="product_edition">
                <div class="product_section_wrap">
                    <div class="section">
                        <div class="pic_frame">
                            <img src="{{$product->image_path}}" loading="lazy" sizes="(max-width: 479px) 80vw, 256px" srcset="{{$product->image_path}} 500w, {{$product->image_path}} 512w" alt="" class="picture box_shadow">
                        </div>
                        <div id="supplier_data_edit_frame" class="data_entry">
                            <div class="data_edit">
                                <div class="field_wrap">
                                    <div class="field_label">Product Code</div>
                                    <div class="code data_field box_shadow">{{$product->internal_code}}</div>
                                </div>
                                <div class="field_wrap">
                                    <div class="field_label">Description</div>
                                    <div class="description data_field box_shadow">{{$product->internal_description}}</div>
                                </div>
                                <div class="field_wrap">
                                    <div class="field_label">Days To Count</div>
                                    <div class="days_to_count data_field box_shadow">{{$product->days_to_count}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section_right">
                        <div id="supplier_data_edit_frame" class="data_entry">
                            <div class="data_edit">
                                <div class="field_wrap">
                                    <div class="field_label">Default Measure Unit</div>
                                    <div class="measure_unit data_field box_shadow">{{$product->default_measure_unit}}</div>
                                </div>
                                <div class="field_wrap">
                                    <div class="field_label">Default Supplier</div>
                                    <div class="default_supplier data_field box_shadow">{{$product->default_supplier_name}}</div>
                                </div>
                                <div class="field_wrap">
                                    <div class="field_label">Planification Type</div>
                                    <div class="default_supplier data_field box_shadow">{{$product->plan_type}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section_buttons">
                        <div class="data_entry_buttons">
                            <div class="bottom_button">
                                <input type="button" class="edit_button box_shadow w-button" value="Edit" onclick="editButtonClick('{{$product->id}}', this)">
                            </div>
                            <div class="data_entry_buttons_separator"></div>
                            <div class="bottom_button">
                                <input type="button" class="supplier_product_button box_shadow w-button" value="Suppliers" onclick="suppliersButtonClick('{{$product->id}}', this)">
                            </div>
                            <div class="data_entry_buttons_separator"></div>
                            <div class="bottom_button">
                                <input type="button" class="delete_button box_shadow w-button" value="Delete" onclick="deleteButtonClick('{{$product->id}}', this)">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="action_result_message" class="action_result_message" hidden></div>
            </div>
            @endforeach
            @else
            <!--div id="empty_list" class="empty_list">THERE ARE NO PRODUCTS TO SHOW!</div -->
            @endif
        </div>

        <!-- THIS IS THE HTML TO BE USED BY JS TO SHOW A CREATED OR UPDATED PRODUCT -->
        <div id="section_html" style="display:none;">
            <div class="product_section_wrap">
                <div class="section">
                    <div class="pic_frame">
                        <img src="image-path" loading="lazy" sizes="(max-width: 479px) 80vw, 256px" srcset="image-path 500w, image-path 512w" alt="" class="picture box_shadow">
                    </div>
                    <div id="supplier_data_edit_frame" class="data_entry">
                        <div class="data_edit">
                            <div class="field_wrap">
                                <div class="field_label">Product Code</div>
                                <div class="code data_field box_shadow">internal-code</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Description</div>
                                <div class="description data_field box_shadow">internal-description</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Days To Count</div>
                                <div class="days_to_count data_field box_shadow">days-to-count</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section_right">
                    <div id="supplier_data_edit_frame" class="data_entry">
                        <div class="data_edit">
                            <div class="field_wrap">
                                <div class="field_label">Default Measure Unit</div>
                                <div class="measure_unit data_field box_shadow">measure-unit</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Default Supplier</div>
                                <div class="default_supplier data_field box_shadow">default-supplier-name</div>
                            </div>
                            <div class="field_wrap">
                                <div class="field_label">Planification Type</div>
                                <div class="default_supplier data_field box_shadow">plan-type</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section_buttons">
                    <div class="data_entry_buttons">
                        <div class="bottom_button">
                            <input type="button" class="edit_button box_shadow w-button" value="Edit" onclick="editButtonClick('section_html', this)">
                        </div>
                        <div class="data_entry_buttons_separator"></div>
                        <div class="bottom_button">
                            <input type="button" class="supplier_product_button box_shadow w-button" value="Suppliers" onclick="suppliersButtonClick('section_html', this)">
                        </div>
                        <div class="data_entry_buttons_separator"></div>
                        <div class="bottom_button">
                            <input type="button" class="delete_button box_shadow w-button" value="Delete" onclick="deleteButtonClick('section_html', this)">
                        </div>
                    </div>
                </div>

                <div id="action_result_message" class="action_result_message" hidden></div>
            </div>
        </div>
     
        <!-- THIS IS THE HTML TO BE SHOWN FROM JS TO EDIT A PRODUCT WHEN THE USER CLICKS EDIT -->
        <div id="add_section_html" class="edit_section_html" style="display:none;">
            <div class="add_section">
                <div class="pic_frame">
                    <form id="product-image" action="/productimgupload" method="post" enctype="multipart/form-data" class="box_shadow">
                        @csrf
                    </form>
                </div>
                <div class="data_entry">
                    <div class="add_form_frame w-form">
                        <form class="product_form add_frame">
                            <div class="product_add_frame">
                                <div class="add_frame_section">
                                    <div class="field_wrap">
                                        <div class="field_label">Product Code</div>
                                        <input type="text" class="code text_field box_shadow w-input" maxlength="50" placeholder="Product Code">
                                    </div>
                                    <div class="field_wrap">
                                        <div class="field_label">Description</div>
                                        <input type="text" class="description text_field box_shadow w-input" maxlength="150" placeholder="Product Description" required="">
                                    </div>
                                    <div class="field_wrap">
                                        <div class="field_label">Days To Count</div>
                                        <input type="number" class="days_to_count text_field box_shadow w-input" min="0" placeholder="Days To Count" required="">
                                    </div>
                                    <input class="image_to_upload" hidden>
                                </div>
                                <div class="add_frame_section">
                                    <div class="field_wrap" style="display:flex;">
                                        <div class="measure_unit_input">
                                            <div class="field_label">Default Measure Unit</div>
                                            <input type="text" class="measure_unit text_field box_shadow w-input" placeholder="Default Measure Unit" disabled>
                                        </div>
                                        <div class="default_measure_unit_button_frame">
                                            <input type="button" value="Units" class="edition_button accept_button box_shadow w-button default_measure_unit_button" onclick="measuresButtonClick(-1, this)">
                                        </div>
                                    </div>
                                     <div class="field_wrap">
                                        <div class="field_label">Default Supplier</div>
                                        <select class="default_supplier text_field box_shadow w-input">
                                            <option value="-1" selected="" placeholder="Default Supplier (Optional)">Default Supplier (Optional)</option>
                                        </select>
                                    </div>
                                    <div class="field_wrap">
                                        <div class="field_label">Planification Type</div>
                                        <select class="plan_type_select text_field box_shadow w-input" onchange="planTypeChanged('product-id')" title="TYPE 1 = PRODUCT MUST BE COUNTED&#013TYPE 2 = PRODUCT WON'T BE COUNTED">
                                            <option value="-1" selected="" disabled>Select a plan type ...</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="field_wrap">
                                <div class="add_buttons_frame">
                                    <input type="button" value="Accept" data-wait="Please wait..." class="edition_button accept_button box_shadow w-button" onclick="acceptEditChanges('product-id', this)">
                                    <input type="button" value="Discard" data-wait="Please wait..." class="edition_button discard_button box_shadow w-button" onclick="discardEditChanges('product-id', this)">
                                </div>
                            </div> 
                       </form>
                    </div>
                </div>
            </div>

            <div id="action_result_message" class="action_result_message" hidden></div>
        </div>
     
        <!-- THIS IS THE HTML TO BE SHOWN FROM JS TO LINK A PRODUCT TO A SUPPLIER WHEN THE USER CLICKS SUPPLIERS -->
        <div id="link_product_section_html" class="edit_section_html" style="display:none;">
            <div class="add_section">
                <div class="pic_frame">
                    <img src="image-path" loading="lazy" sizes="(max-width: 479px) 80vw, 256px" srcset="image-path 500w, image-path 512w" alt="" class="picture box_shadow">
                </div>
                <div class="data_entry">
                    <div class="add_form_frame w-form">
                        <form class="product_form add_frame">
                            <div class="product_add_frame">
                                <div class="add_frame_section">
                                    <div class="field_wrap">
                                        <div class="field_label">Product Code</div>
                                        <input value="product-code" type="text" class="code text_field box_shadow w-input" disabled>
                                    </div>    
                                    <div class="field_wrap">
                                        <div class="field_label">Description</div>
                                        <input value="product-description" type="text" class="description text_field box_shadow w-input" disabled>
                                    </div>
                                    <div class="field_wrap">
                                        <div class="field_label">Supplier</div>
                                        <select id="supplier_product_select" class="text_field box_shadow w-input" onchange="supplierProductSelectChange(this, 'product-id')">
                                            <option value="-1" selected disabled style="font-style:italic;">Select a supplier ...</option>
                                        </select>
                                    </div>
                                    <input class="image_to_upload" hidden>
                                </div>
                                <div class="add_frame_section">
                                    <div class="field_wrap">
                                        <div class="field_label">Supplier's Product Code</div>
                                        <input id="supplier_product_code" value="" type="text" class="text_field box_shadow w-input" disabled>
                                    </div>
                                    <div class="field_wrap">
                                        <div class="field_label">Supplier's Product Description</div>
                                        <input id="supplier_product_description" value="" type="text" class="text_field box_shadow w-input" disabled>
                                    </div>
                                    <div class="field_wrap">
                                        <div class="field_label">Supplier's Product Location Stop</div>
                                        <select id="supplier_product_location_stop" value="" type="text" class="text_field box_shadow w-input" disabled>
                                            <option value="-1" disabled selected>Select the Location Stop ...</option>
                                            <option value="1">Stop - A</option>
                                            <option value="2">Stop - B</option>
                                            <option value="3">Stop - C</option>
                                            <option value="4">Stop - D</option>
                                            <option value="5">Stop - E</option>
                                            <option value="6">Stop - F</option>
                                            <option value="7">Stop - G</option>
                                            <option value="8">Stop - H</option>
                                            <option value="9">Stop - I</option>
                                            <option value="10">Stop - J</option>
                                            <option value="11">Stop - K</option>
                                            <option value="12">Stop - L</option>
                                            <option value="13">Stop - M</option>
                                            <option value="14">Stop - N</option>
                                            <option value="15">Stop - O</option>
                                            <option value="16">Stop - P</option>
                                            <option value="17">Stop - Q</option>
                                            <option value="18">Stop - R</option>
                                            <option value="19">Stop - S</option>
                                            <option value="20">Stop - T</option>
                                            <option value="21">Stop - U</option>
                                            <option value="22">Stop - V</option>
                                            <option value="23">Stop - W</option>
                                            <option value="24">Stop - X</option>
                                            <option value="25">Stop - Y</option>
                                            <option value="26">Stop - Z</option>
                                        </select>
                                            
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="add_buttons_frame">
                            <input type="button" value="Accept" data-wait="Please wait..." class="edition_button accept_button box_shadow w-button" onclick="acceptSupplierProductChanges('product-id', this)">
                            <input type="button" value="Discard" data-wait="Please wait..." class="edition_button discard_button box_shadow w-button" onclick="discardSupplierProductChanges('product-id')">
                        </div>

                    </div>
                    <div id="action_result_message" class="action_result_message" hidden></div>
                </div>
            </div>

            <div id="action_result_message" class="action_result_message" hidden></div>
        </div>

        <!-- DIALOG TO LINK THE MEASURE UNITS TO A PRODUCT -->
        <div id="unit_link_dialog_frame" class="unit_link_dialog_frame" style="display:none;">
        </div>

        <!-- THIS IS THE HTML FOR THE PRODUCT-UNITS LINK DIALOG -->
        <!-- THIS WILL BE USED FROM JS TO CREATE DIALOGS DYNAMICALLY -->
        <div id="unit_link_dialog_frame_html" hidden>
            <div class="unit_link_dialog shadowRight">
                <div class="dialog_close_bar">
                    <div class="units_close_bar_text">Select units for</div>
                    <a class="unit_link_dialog_close_icon" onclick="discardUnitChanges()">X</a>
                </div>
                <div class="unit_link_dialog_body">
                    <div class="unit_link_product_description_frame">
                        <input value="product-description" class="unit_link_product_description" disabled>
                    </div>
                    <div id="unit_link_checkboxes" class="unit_link_checkboxes">
                        <div class="unit_link_name_frame">
                            <label for="unit_0" class="unit_link_checkbox_label"><input class="new_unit_input" placeholder="Enter a unit" style="display:none;"></label>
                            <div class="unit_add_icon">
                                <a class="unit_add_link" onclick="addUnitClick()">Add</a>
                                <a class="unit_create_link" style="display:none" onclick="createUnitClick(this)">+</a>
                            </div>
                        </div>
                        <div class="unit_links">
                        </div>
                    </div>
                    <div class="default_measure_select_frame">
                        <div class="default_unit_label">Default unit</div>
                        <select class="default_measure_select">
                            <option value="-1" selected disabled>Select a default unit</option>
                        </select>
                        <input type="text" class="selected_index" hidden>
                    </div>
                    <div class="unit_link_accept_button">
                        <input type="button" value="Accept" class="accept_button box_shadow w-button" onclick="acceptUnitChanges('product-id')">
                    </div>
                </div>    
            </div>
        </div>

        <!-- THIS ELEMENT IS RESERVED TO HOLD THE TEMPORARILY ACCEPTED MEASURE UNITS -->
        <div id="unit_link_dialog_frame_html_saved" hidden>
        </div>

        <!-- THIS IS THE HTML TO ADD A NEW MEASURE UNIT TO THE LIST -->
        <div id="unit_link_checkbox_frame_html" class="unit_link_checkbox_frame_html" hidden>
            <div id="unit_link_checkbox_frame_measureunit-id" class="unit_link_checkbox_frame" removed='false' measure_unit_id='measureunit-id'>
                <label for="unit_measureunit-id" class="unit_link_checkbox_label">
                    <input type="checkbox" id="unit_measureunit-id" measure_id="measureunit-id" text="unit-description" class="unit_link_checkbox" onchange="measureUnitChange('unit_measureunit-id')" /> unit-description
                </label>
                <div class="unit_delete_icon">
                    <a onclick="removeUnitClick('measureunit-id')">-</a>
                </div>
            </div>
        </div>


        <!-- DIALOG FOR THE ACCEPT/CANCEL LINK REMOVAL -->
        <div id="accept_cancel_unit_link_dialog_frame" class="unit_link_dialog_frame" style="display:none;">
        </div>

        <!-- THIS IS THE HTML FOR THE ACCEPT/CANCEL LINK REMOVAL -->
        <!-- THIS WILL BE USED FROM JS TO CREATE DIALOGS DYNAMICALLY -->
        <div id="accept_cancel_unit_link_dialog_frame_html" hidden>
            <div class="accept_cancel_unit_link_dialog shadowRight">
                <div class="accept_cancel_dialog_close_bar">
                    <div class="accept_cancel_units_close_bar_text">This unit is linked to this and/or other products</div>
                    <a class="accept_cancel_unit_link_dialog_close_icon" onclick="acceptCancelUnitLinkDialogClose()">X</a>
                </div>
                <div class="accept_cancel_unit_link_dialog_body">
                    <div class="unit_link_product_description_frame">
                        <input value="unit-description" class="unit_link_product_description" disabled>
                    </div>
                    <div>If you remove this unit all the links to the products will be removed</div>
                    <div class="unit_link_accept_button">
                        <input type="button" value="Remove Anyway" class="accept_button box_shadow w-button" onclick="acceptUnitRemoval('unit-id')">
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
