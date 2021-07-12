@extends('layouts.app')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-wf-page="605538017156323a2f5ab124" data-wf-site="604d41d40c813292693d08e7">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @section('title', 'Orders')

        <link href="/css/webflow.css" rel="stylesheet" type="text/css">
        <link href="/css/pendingorders.css" rel="stylesheet" type="text/css">
        <link href="/css/shakys.webflow.css" rel="stylesheet" type="text/css">
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
    </head>
    <body class="antialiased bodyClass">
        @section('page_title', 'ORDERS')
        @section('content')
        <input id="tab_id" value="{{$tabid}}" hidden></input>
        <div class="pending_content" hidden>
            <!-- COUNTED PRODUCT ADD TO ORDER BUTTON -->
            <div id="add_to_order_button" class="add_to_order_button" style="display:none;">
                <input type="button" value="Order" class="shadowRight" style="background-color: #3898ec;width:100%;" onclick="addToOrderClick('add_to_order_check', 'counted_')">
            </div>

            <!-- ALL PRODUCTS ADD TO ORDER BUTTON -->
            <div id="all_products_add_to_order_button" class="add_to_order_button" style="display:none;">
                <input type="button" value="Order" class="shadowRight" style="background-color: #3898ec;width:100%;" onclick="addToOrderClick('all_products_add_to_order_check', 'all_')">
            </div>

            <!-- ACTION TABS -->
            <div data-duration-in="300" data-duration-out="100" class="w-tabs">
                <!-- TABS MENU -->
                <div class="w-tab-menu">
                    <a id="tab_1" data-w-tab="Tab 1" class="w-inline-block w-tab-link w--current" onclick="tabClick(this)">
                        <div>Due To Count</div>
                    </a>
                    <a id="tab_2" data-w-tab="Tab 2" class="w-inline-block w-tab-link" onclick="tabClick(this)">
                        <div>Requests</div>
                    </a>
                    <a id="tab_3" data-w-tab="Tab 3" class="w-inline-block w-tab-link" onclick="tabClick(this)">
                        <div>All The Products</div>
                    </a>
                    <a id="tab_4" data-w-tab="Tab 4" class="w-inline-block w-tab-link" onclick="tabClick(this)">
                        <div>Orders For Approval</div>
                    </a>
                    <a id="tab_5" data-w-tab="Tab 5" class="w-inline-block w-tab-link" onclick="tabClick(this)">
                        <div>Submitted Orders</div>
                    </a>
                </div>

                <!-- TABS CONTENT -->
                <div class="w-tab-content">
                    <!-- PENDING TO COUNT PRODUCTS -->
                    <div data-w-tab="Tab 1" class="w-tab-pane w--tab-active">
                        @if(count($products) > 0)
                        @foreach($products as $key => $product)
                        <!-- HERE A PRODUCT IS SHOWN WITH A RED/BLACK BACKGROUND -->
                        <a onclick="productClick('pending_{{$product->id}}')">
                            <div id="pending_{{$product->id}}" class="ui_section product {{round($key / 2) * 2 != $key ? 'bbg':'rbg'}} shadowRight">
                                <div class="po_to_count_section">
                                    <div class="po_pic_frame">
                                        <img src="{{$product->image_path}}" loading="lazy" alt="" class="product_pic">
                                    </div>
                                    <div class="po_description">
                                        <div class="product_description_text">
                                            <text class="counted_product_description">{{$product->internal_description}}</text>
                                        </div>
                                    </div>
                                    <div class="po_due_date">
                                        <div>Due on</div>
                                        <div>{{$product->due_date}}</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                        @else
                        <div class="empty_tab_text">THERE ARE NO PRODUCTS DUE TO COUNT</div>
                        @endif

                    </div>

                    <!-- COUNTED PRODUCTS (REQUESTS) -->
                    <div data-w-tab="Tab 2" class="w-tab-pane">
                        @if(count($countedproducts) > 0)
                        @foreach($countedproducts as $key => $countedProduct)
                        <!-- HERE A PRODUCT IS SHOWN WITH A RED/BLACK BACKGROUND -->
                        <div id="counted_{{$countedProduct->id}}" productId="{{$countedProduct->id}}" class="ui_section product {{round($key / 2) * 2 != $key ? 'bbg':'rbg'}} shadowRight">
                            <div class="po_to_count_section">
                                <div class="po_pic_frame">
                                    <img src="{{$countedProduct->image_path}}" loading="lazy" alt="" class="product_pic">
                                </div>
                                <div class="po_description">
                                    <div class="product_description_text">
                                        <text class="counted_product_description">{{$countedProduct->internal_description}}</text>
                                    </div>
                                </div>
                            </div>

                            <div id="order_data" class="order_data">
                                <div class="order_data_field_wrap">
                                    <div class="order_data_field">
                                        <label id="supplier_select_label" class="order_data_field_label">Supplier</label>
                                        <select id="product_supplier_select" productId="{{$countedProduct->id}}" class="order_data_field_select" onchange="supplierSelChange(this)">
                                            <option value="-1" selected disabled>Select a supplier</option>
                                            @foreach($suppliers as $key => $supplier)
                                            <option value="{{$supplier->id}}" pickup="{{$supplier->pickup}}" last_pickup_guy="{{$supplier->last_pickup_id}}" {{$supplier->id == $countedProduct->default_supplier_id ? 'selected':''}}>{{$supplier->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="order_data_field_wrap">
                                    <div id="pickup" class="order_data_field">
                                        <label id="pickup_user_label" class="order_data_field_label">Type</label>
                                        <select id="order_pickup_select" class="order_data_field_select" onchange="orderPickupSelectChange(this)">
                                            <option value="pickup" {{$countedProduct->pickup == 'pickup' ? 'selected':''}}>Pickup</option>
                                            <option value="delivery" {{$countedProduct->pickup == 'delivery' ? 'selected':''}}>Delivery</option>
                                        </select>
                                    </div>
                                    <div id="order_pickup_guy_wrap" class="order_data_field">
                                        <label id="counted_pickup_user_select" class="order_data_field_label">Pickup Guy</label>
                                        <select id="order_pickup_guy_select" class="order_data_field_select" onchange="orderPickupGuySelectChange(this)" {{$countedProduct->pickup == 'delivery' ? 'disabled':''}}>
                                            <option value="-1" selected disabled>Select one</option>
                                            @foreach($pickupusers as $key => $pickupuser)
                                            <option value="{{$pickupuser->id}}" {{$pickupuser->id == $countedProduct->last_pickup_id ? 'selected':''}}>
                                                {{$pickupuser->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="order_data_field_wrap">
                                    <div class="order_data_field">
                                        <label class="order_data_field_label">Price</label>
                                        <input value="{{$countedProduct->supplier_price}}" class="order_price_field" disabled>
                                    </div>
                                    <div class="order_data_field">
                                        <label class="order_data_field_label">Request</label>
                                        <select id="order_qty_sel" class="order_qty_select order_data_field_select" qty_to_order={{$countedProduct->qty_to_order}}>
                                            <option value="0">0</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="order_data_field_wrap order_unit">
                                    <div class="order_data_field add_to_order_label_wrap">
                                        <label class="order_data_field_label add_to_order_label">Add to order</label>
                                        <input type="checkbox" id="order_check" class="add_to_order_check">
                                    </div>
                                    <div class="order_data_field">
                                        <label class="order_data_field_label">Unit</label>
                                        <select class="order_data_field_select order_unit_sel" original_unit_id="{{$countedProduct->measure_unit_id}}">
                                            <option value="-1" disable>Select A Unit ...</option>
                                            @foreach($countedProduct->measure_units as $key => $measureUnit)
                                            <option value="{{$measureUnit->id}}" {{$measureUnit->id == $countedProduct->measure_unit_id ? 'selected': ''}}>
                                                {{$measureUnit->unit_description}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="empty_tab_text">THERE ARE NO COUNTED PRODUCTS</div>
                        @endif
                    </div>

                    <!-- ALL THE PRODUCTS -->
                    <div data-w-tab="Tab 3" class="w-tab-pane">
                        @if(count($allproducts) > 0)
                        @foreach($allproducts as $key => $allProduct)
                        <!-- HERE A PRODUCT IS SHOWN WITH A RED/BLACK BACKGROUND -->
                        <div id="all_{{$allProduct->id}}" productId="{{$allProduct->id}}" class="ui_section product {{round($key / 2) * 2 != $key ? 'bbg':'rbg'}} shadowRight">
                            <div class="po_to_count_section">
                                <div class="po_pic_frame">
                                    <img src="{{$allProduct->image_path}}" loading="lazy" alt="" class="product_pic">
                                </div>
                                <div class="po_description">
                                    <div class="product_description_text">
                                        <text class="all_product_description">{{$allProduct->internal_description}}</text>
                                    </div>
                                </div>
                            </div>
                            <div id="order_data" class="order_data">
                                <div class="order_data_field_wrap">
                                    <div class="order_data_field">
                                        <label id="supplier_select_label" class="order_data_field_label">Supplier</label>
                                        <select id="product_supplier_select" productId="{{$allProduct->id}}" class="order_data_field_select" onchange="supplierSelChange(this)">
                                            <option value="-1" selected disabled>Select a supplier</option>
                                            @foreach($suppliers as $key => $supplier)
                                            <option value="{{$supplier->id}}" pickup="{{$supplier->pickup}}" last_pickup_guy="{{$supplier->last_pickup_id}}" {{$supplier->id == $allProduct->supplier_id ? 'selected':''}}>{{$supplier->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="order_data_field_wrap">
                                    <div id="pickup" class="order_data_field">
                                        <label id="pickup_user_label" class="order_data_field_label">Type</label>
                                        <select id="order_pickup_select" class="order_data_field_select" onchange="orderPickupSelectChange(this)">
                                            <option value="pickup" {{$allProduct->pickup == 'pickup' ? 'selected':''}}>Pickup</option>
                                            <option value="delivery" {{$allProduct->pickup == 'delivery' ? 'selected':''}}>Delivery</option>
                                        </select>
                                    </div>
                                    <div id="order_pickup_guy_wrap" class="order_data_field" >
                                        <label id="all_pickup_user_select" class="order_data_field_label">Pickup Guy</label>
                                        <select id="order_pickup_guy_select" class="order_data_field_select"  onchange="orderPickupGuySelectChange(this)" {{$allProduct->pickup == 'delivery' ? 'disabled':''}}>
                                            <option value="-1" selected disabled>Select one</option>
                                            @foreach($pickupusers as $key => $pickupuser)
                                            <option value="{{$pickupuser->id}}" {{$pickupuser->id == $allProduct->last_pickup_id ? 'selected':''}}>
                                                {{$pickupuser->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="order_data_field_wrap">
                                    <div class="order_data_field">
                                        <label class="order_data_field_label">Price</label>
                                        <input value="{{$allProduct->supplier_price}}" class="order_price_field" disabled>
                                    </div>
                                    <div class="order_data_field">
                                        <label class="order_data_field_label">Request</label>
                                        <select id="order_qty_sel" class="order_qty_select order_data_field_select" qty_to_order={{$allProduct->qty_to_order}}>
                                            <option value="0">0</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="order_data_field_wrap order_unit">
                                    <div class="order_data_field add_to_order_label_wrap">
                                        <label class="order_data_field_label add_to_order_label">Add to order</label>
                                        <input type="checkbox" id="order_check" class="all_products_add_to_order_check">
                                    </div>
                                    <div class="order_data_field">
                                        <label class="order_data_field_label">Unit</label>
                                        <select class="order_data_field_select order_unit_sel">
                                            <option value="-1" disable>Select A Unit ...</option>
                                            @foreach($allProduct->measure_units as $key => $measureUnit)
                                            <option value="{{$measureUnit->id}}" {{$measureUnit->id == $allProduct->measure_unit_id ? 'selected': ''}}>
                                                {{$measureUnit->unit_description}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="empty_tab_text">THERE ARE NOT PRODUCTS AT ALL</div>
                        @endif
                    </div>

                    <!-- ORDERS FOR APPROVAL TAB -->
                    <div data-w-tab="Tab 4" class="w-tab-pane">
                        @if(count($orders) > 0)
                        @foreach($orders as $key => $order)
                        <div id="approval_{{$order->id}}" class="order_section shadowRight">
                            <div class="order_segment">
                                <label style="width:100%;">Order #{{$order->id}}</label>
                            </div>
                            <div class="order_segment">
                                <div class="order_supplier_select_wrap">
                                    <label>Supplier</label>
                                    <select id="order_supplier_select" class="order_supplier_display_select supplier_select" onchange="orderSupplierSelectChange(this, 'approval_{{$order->id}}')">
                                        @foreach($suppliers as $key => $supplier)
                                        <option value="{{$supplier->id}}" {{$supplier->id == $order->supplier_id ? 'selected':''}}>
                                        {{$supplier->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="order_date_wrap">
                                    <label>Order Date</label>
                                    <input value="{{$order->date}}" class="order_display_date" disabled></input>
                                </div>
                            </div>

                            <div class="order_segment">
                                <div class="order_pickup_display_wrap">
                                    <label>Order Type</label>
                                    <select id="order_type_select" class="order_type_display_select" onchange="orderTypeSelectChange(this, 'approval_{{$order->id}}')">
                                        <option value="pickup" {{$order->pickup == 'pickup' ? 'selected':''}}>
                                                Pickup
                                            </option>
                                        <option value="delivery" {{$order->pickup == 'delivery' ? 'selected':''}}>
                                            Delivery
                                        </option>
                                    </select>
                                </div>
                                <div id="order_pickup_display_wrap" class="order_pickup_display_wrap" {{$order->pickup == 'delivery' ? ' style=display:none;':''}}>
                                    <label>Pickup User</label>
                                    <select id="order_pickup_user_select" class="order_pickup_user_display_select">
                                        <option id="-1" disabled selected>Select one</option>
                                        @foreach($pickupusers as $key => $pickupuser)
                                        <option value="{{$pickupuser->id}}" {{$pickupuser->id == $order->pickup_guy_id ? 'selected':''}}>
                                            {{$pickupuser->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="order_display_lines">
                                @foreach($order->order_lines as $key => $orderLine)
                                <div id="{{$orderLine->id}}" class="order_line_section approval_order_line">
                                    <div class="order_line_wrap">
                                        <div class="order_line">
                                            <div class="product_code_wrap">
                                                <div class="product_field_component">
                                                    <label>Product</label>
                                                    <input class="product_int_code" value="{{$orderLine->internal_code}}" disabled></input>
                                                </div>
                                                <div class="product_field_component">
                                                    <label>Supplier Code</label>
                                                    <input class="product_int_code" value="{{$orderLine->supplier_code}}" disabled></input>
                                                </div>
                                            </div>

                                            <div class="product_int_desc_wrap">
                                                <div class="product_field_component">
                                                    <label>In-store Description</label>
                                                    <input class="product_int_desc" value="{{$orderLine->internal_description}}" disabled></input>
                                                </div>
                                                <div class="product_field_component">
                                                    <label>Supplier Description</label>
                                                    <input class="product_int_desc" value="{{$orderLine->supplier_description}}" disabled></input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="order_line">
                                            <div class="product_qty_price_wrap">
                                                <div class="product_field_component">
                                                </div>
                                            </div>
                                            <div class="product_qty_price_wrap">
                                                <div class="product_field_component">
                                                    <label>Price</label>
                                                    <input value="{{$orderLine->supplier_price}}" class="order_price_field approval_order_price" productId="{{$orderLine->product_id}}" disabled>

                                                </div>
                                            </div>
                                            <div class="product_qty_price_wrap">
                                                <div class="product_field_component">
                                                    <label>Requested</label>
                                                    <select id="product_qty_display_select" class="product_qty_display_select qty_select" qty="{{$orderLine->qty}}">
                                                        <option value="0" selected>0</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="product_qty_price_wrap product_unit">
                                                <div class="product_field_component">
                                                    <label>Unit</label>
                                                    <input disabled class="product_unit_display order_price_field" value="{{$orderLine->unit_description}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="submit_order_button_wrap">
                                <input type="button" value="Submit order" class="submit_order_button" onclick="submitOrderButtonClick('approval_{{$order->id}}')">
                            </div>

                            <div id="action_result_message" class="action_result_message" hidden></div>
                        </div>
                        @endforeach
                        @else
                        <div class="empty_tab_text">THERE ARE NO ORDERS FOR APPROVAL</div>
                        @endif
                    </div>

                    <!-- SUBMITTED ORDERS TAB -->
                    <div id="submitted_orders_tab" data-w-tab="Tab 5" class="w-tab-pane">
                        @if(count($submittedorders) > 0)
                        @foreach($submittedorders as $key => $submittedOrder)
                        <div id="submitted_{{$submittedOrder->id}}" class="order_section shadowRight">
                            <!-- ORDER HEADER -->                        
                            <div class="order_segment"> 
                                <label style="width:100%;">Order #{{$submittedOrder->id}}</label>
                            </div>

                            <div class="order_segment">
                                <div class="order_supplier_select_wrap">
                                    <label>Supplier</label>
                                    <input value="{{$submittedOrder->supplier_name}}" class="order_supplier_display_select" disabled></input>
                                </div>
                                <div class="order_date_wrap">
                                    <label>Order Date</label>
                                    <input value="{{$submittedOrder->date}}" class="order_display_date" disabled></input>
                                </div>
                            </div>

                            <div class="order_segment">
                                <div class="order_pickup_display_wrap">
                                    <label>Order Type</label>
                                    <input value="{{$submittedOrder->pickup}}" class="order_type_display_select" disabled></input>
                                </div>
                                <div class="order_pickup_display_wrap" {{$submittedOrder->pickup == 'delivery' ? 'hidden':''}}>
                                    <label>Pickup User</label>
                                    <input value="{{$submittedOrder->pickup_user}}" class="order_pickup_user_display_select" disabled></input>
                                </div>
                            </div>
                            
                            <!-- ORDER LINES -->
                            <div class="order_display_lines">
                                @foreach($submittedOrder->order_lines as $key => $orderLine)
                                <!-- ORDER LINE -->
                                <div id="{{$orderLine->id}}" class="order_line_section submitted_order_line">
                                    <div class="order_line_info_wrap">
                                        <!-- PRODUCT INFO -->
                                        <div class="product_info">
                                            <!-- IN-STORE PRODUCT INFO -->
                                            <div class="order_line">
                                                <div class="product_code_wrap">
                                                    <label>Product</label>
                                                    <input class="product_int_code" value="{{$orderLine->internal_code}}" disabled></input>
                                                </div>
                                                <div class="product_int_desc_wrap">
                                                    <label>In-store Description</label>
                                                    <input class="product_int_desc" value="{{$orderLine->internal_description}}" disabled></input>
                                                </div>
                                            </div>

                                            <!-- SUPPLIER PRODUCT INFO -->
                                            <div class="order_line order_line_2">
                                                <div class="product_code_wrap">
                                                    <label>Supplier Code</label>
                                                    <input class="product_int_code" value="{{$orderLine->supplier_code}}" disabled></input>
                                                </div>
                                                <div class="product_int_desc_wrap">
                                                    <label>Supplier Description</label>
                                                    <input class="product_int_desc" value="{{$orderLine->supplier_description}}" disabled></input>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order_line_price_wrap">
                                        <!-- QUANTITY INFO -->
                                        <div class="order_display_qty_1">
                                            <label>Ordered</label>
                                            <input class="product_qty_display_input product_input_1" value="{{$orderLine->qty}}" disabled>
                                            </input>
                                        </div>
                                        <div class="order_display_qty_1">
                                            <label>Available</label>
                                            <select class="order_qty_select product_qty_display_input available_qty product_input_2" qty_to_order="{{$orderLine->available_qty}}" lineId="{{$orderLine->id}}">
                                                <option value="0">0</option>
                                            </select>
                                        </div>
                                        <!-- ORDER LINE PRICE -->
                                        <div class="order_display_qty_1">
                                            <label>Price</label>
                                            <input class="product_qty_display_input submitted_supplier_price product_input_3" value="{{$orderLine->supplier_price}}">
                                            </input>
                                        </div>
                                        <div class="order_display_qty_1">
                                            <label>Unit</label>
                                            <input disabled class="product_unit_display product_qty_display_input" value="{{$orderLine->unit_description}}">
                                            </input>
                                        </div>
                                    </div>    
                                </div>
                                @endforeach
                            </div>

                            <!-- ACTION BUTTONS SECTION -->
                            <div class="submitted_order_buttons_wrap">
                                <div class="submitted_order_button_wrap">
                                    <input type="button" value="Resend order" class="submitted_order_button" onclick="resendOrderButtonClick('submitted_{{$submittedOrder->id}}')">
                                </div>
                                <div class="submitted_order_button_wrap">
                                    <input type="button" value="Receive order" class="submitted_order_button" onclick="receiveOrderButtonClick('submitted_{{$submittedOrder->id}}')">
                                </div>
                            </div>
                            <!-- ACTION RESULT MESSAGE FRAME -->
                            <div id="action_result_message_{{$submittedOrder->id}}" class="action_result_message" hidden></div>
                        </div>
                        @endforeach
                        @else
                        <!-- EMPTY ORDERS MESSAGE -->
                        <div class="empty_tab_text">THERE ARE NO SUBMITTED ORDERS</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- POP UP DIALOG FOR THE PRODUCT REQUESTS -->
            <div id="order_top_most" class="order_top_most" hidden>
                <div id="popup_product" class="order_frame">
                    <div class="order_close_bar">
                        <a onclick="closeOrder()">X</a>
                    </div>
                    <img id="product_order_image" src="https://d3e54v103j8qbb.cloudfront.net/plugins/Basic/assets/placeholder.60f9b1840c.svg" loading="lazy" width="64" alt="" class="order_image">
                    <div id="order_product" class="order_product">internal-description</div>
                    <div class="form-block-2">
                        <label for="name">Units</label>
                        <!-- input id="qty" type="number" class="order_qty w-input" placeholder="" value="0" min="0"-->
                        <select id="measure_unit" class="order_qty w-input">
                            <option value="-1" disable>Select A Measure Unit ...</option>
                        </select>
                    </div>
                    <div class="form-block-2">
                        <label for="name">Qty</label>
                        <select id="qty" class="order_qty order_qty_select order_qty_tag">
                            <option value="0" style="text-align:right;text-align-last:right;">0</option>
                        </select>
                        <div class="request_button_frame">
                            <input type="button" value="Request" class="order_button w-button" onclick="orderClick('product-id')">
                        </div>
                    </div>
                </div>
            </div>

            <div id="order_top_id" class="order_top_most" hidden>
            </div>
        </div>

        <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=604d41d40c813292693d08e7" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="/js/garcellLib.js" type="text/javascript"></script>
        <script src="/js/pendingorders.js" type="text/javascript"></script>
        <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
        @endsection    
    </body>
</html>
