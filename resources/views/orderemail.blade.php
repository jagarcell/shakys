<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            .order_id{
                font-size: 30px; 
                font-weight: bold; 
                padding-bottom: 15px;
            }
            .order_instructions{
                font-weight:bold; 
                padding-top: 10px;
            }
            .lines_section{
                padding-top:20px;
                border-bottom: solid 1px black;
            }
            .lines_header{
                display:flex;
                border-bottom: solid;
            }
            .code_column{
                width:40%;
            }
            .description_column{
                width:50%;
            }
            .qty_column{
                width:10%;
            }
            .order_line{
                display:flex;
                padding-top:10px;
            }
            .align_right{
                text-align:end;
            }
            .link_label{
                margin-top: 20px;
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
    <div class="order_id">Order #: {{$order->id}}</div>
    @if(isset($order->instructions1))
    <div>{{$order->instructions1}}</div>
    @endif
    <div class="order_instructions">
        @if(isset($order->instructions2))
        <div>{{$order->instructions2}}</div>
        @endif
        @if(isset($order->instructions3))
        <div>{{$order->instructions3}}</div>
        @endif
        @if(isset($order->instructions4))
        <div>{{$order->instructions4}}</div>
        @endif
        @if(isset($order->instructions5))
        <div>{{$order->instructions5}}</div>
        @endif
    </div>
    <div class="lines_section">
        <div class="lines_header">
            <div class="code_column">Product Code</div><div class="description_column">Description</div><div class="qty_column">Qty</div>
        </div>
        @foreach($order->lines as $key => $line)
        <div class="order_line">
            <div class="code_column">{{$line->product_code}}</div><div class="description_column">{{$line->product_description}}</div><div class="qty_column align_right">{{$line->qty}}</div>
        </div>
        @endforeach
    </div>
    <div class="link_label">Click the link below to visit the orders dashboard (Only for Pickup Users)</div>
    <div>{{$order->homePage}}/pickupdashboard</div>
    </body>
</html>