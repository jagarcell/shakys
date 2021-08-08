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
        @section('page_title', 'MEASURE UNITS')
        @section('content')
        <!-- THIS IS THE SECTION THAT HOLDS THE HTML FOR THE ADDING ICON AND THE ADDING ACTION FORM -->
        <div id="add_section_wrap" class="add_section_wrap">
            <div class="all_products_search_frame">
                <input id="supplier_search_text" type="text" class="all_products_search_bar" placeholder="Enter your search here">
                    <div class="search_product_icon">
                        <img src="/images/MagnifierBk.png">
                        <input type="button" class="all_products_search_button" onclick="measureUnitSearchClick()">
                    </div>
                </div>
                <div class="supplier_add_icon">
                    <input type="button" class="add_input" value="+" onclick="addIconClick()">
                </div>
            </div>

            <!-- THIS IS THE ADD ICON -->
            <!--div id="add_icon_frame" class="add_icon_frame">
                <div class="add_icon">
                    <input type="button" class="add_input" value="+" onclick="addIconClick()">
                </div>
            </div-->

            <!-- THIS IS THE SECTION THAT IS USED TO ENTER DATA TO BE CREATED -->
            <!-- IT WILL BE SHOWN WHEN THE USER CLICKS ON THE ADD ICON -->
            <div class="section_wrap">
                <div id="add_section_frame" class="measure_unit_section" style="display:none;">
                    <div class="data_entry">
                        <div class="add_form_frame w-form">
                            <form class="product_form add_unit_frame">
                                <input id="unit_description" type="text" class="code text_field box_shadow w-input" maxlength="255" placeholder="Unit Description" required="">
                                <div class="add_buttons_frame">
                                    <input type="button" value="Create Unit" data-wait="Please wait..." class="edition_button accept_button box_shadow w-button" onclick="createButtonClick()">
                                    <input type="button" value="Discard Unit" data-wait="Please wait..." class="edition_button discard_button box_shadow w-button" onclick="discardButtonClick()">
                                </div>
                                <input class="image_to_upload" hidden>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- THIS IS WHERE ACTION RESULT MESSAGES WILL BE SHOWN -->
                <div id="action_result_message" class="action_result_message" hidden>
                </div>
            </div>
        </div>
    
        <!-- THIS IS THE LIST OF PRODUCTS IN THE DATABASE RECEIVED FROM THE SERVER -->
        <div id="products_list_wrap">    
            @if(count($measureunits) > 0)
            @foreach($measureunits as $key => $measureunit)
            <div id="{{$measureunit->id}}" class="measure_unit_edition">
                <div class="section_wrap">
                    <div class="measure_unit_section">
                        <div id="supplier_data_edit_frame" class="data_entry">
                            <div class="unit_data_edit">
                                <div class="field_wrap">
                                    <div class="field_label">Unit Description</div>
                                    <div class="description data_field box_shadow">{{$measureunit->unit_description}}</div>
                                </div>
                                <div class="data_entry_buttons">
                                    <div class="bottom_button">
                                        <input type="button" class="edit_button box_shadow w-button" value="Edit" onclick="editButtonClick('{{$measureunit->id}}')">
                                    </div>
                                    <div class="bottom_button">
                                        <input type="button" class="delete_button box_shadow w-button" value="Delete" onclick="deleteButtonClick('{{$measureunit->id}}')">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="action_result_message" class="action_result_message" hidden></div>
                </div>
            </div>
            @endforeach
            @else
            <div id="empty_list" class="empty_list">THERE ARE NOT PRODUCTS REGISTERED YET!</div>
            @endif
        </div>

        <!-- THIS IS THE HTML TO BE USED BY JS TO SHOW A CREATED OR UPDATED PRODUCT -->
        <div id="section_html" style="display:none;">
            <div class="section_wrap">
                <div class="measure_unit_section">
                    <div id="supplier_data_edit_frame" class="data_entry">
                    <div class="unit_data_edit">
                            <div class="field_wrap">
                                <div class="field_label">Unit Description</div>
                                <div class="description data_field box_shadow">unit-description</div>
                            </div>
                            <div class="data_entry_buttons">
                                <div>
                                    <input type="button" class="edit_button box_shadow w-button" value="Edit" onclick="editButtonClick('unit-id')">
                                </div>
                                <div class="bottom_button">
                                    <input type="button" class="delete_button box_shadow w-button" value="Delete" onclick="deleteButtonClick('unit-id')">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="action_result_message" class="action_result_message" hidden></div>
            </div>
        </div>
     
        <!-- THIS IS THE HTML TO BE SHOWN FROM JS TO EDIT A PRODUCT WHEN THE USER CLICKS EDIT -->
        <div id="add_section_html" class="edit_section_html" style="display:none;">
            <div class="section_wrap">
                <div class="measure_unit_section">
                    <div class="data_entry">
                        <div class="add_form_frame w-form">
                            <form class="product_form add_frame">
                                <div class="field_wrap">
                                    <div class="field_label">Unit Description</div>
                                    <input type="text" class="code text_field box_shadow w-input unit_description_input" maxlength="50" name="Code" placeholder="Unit Description" required="" value="unit-description">
                                </div>
                                <div class="add_buttons_frame">
                                    <input type="button" value="Accept Changes" data-wait="Please wait..." class="accept_button box_shadow w-button" onclick="acceptEditChanges('unit-id')">
                                    <input type="button" value="Discard Changes" data-wait="Please wait..." class="discard_button box_shadow w-button" onclick="discardEditChanges('unit-id')">
                                </div>
                                <input class="image_to_upload" hidden>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="action_result_message" class="action_result_message" hidden></div>
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
                    <div class="accept_cancel_units_close_bar_text">This unit is linked to one or more products</div>
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
        <script src="/js/measureunits.js" type="text/javascript"></script>
        <script src="/js/dropzone.js"></script>    
        <script src="/js/garcellLib.js"></script>
        <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->

        @endsection
    </body>
</html>
