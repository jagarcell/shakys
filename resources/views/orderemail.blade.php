<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    </head>
    <body>
    <div style="font-size: 30px; font-weight: bold; padding-bottom: 15px;">Order #: {{$order->id}}</div>
    @if(isset($order->instructions1))
    <div>{{$order->instructions1}}</div>
    @endif
    <div style="font-weight:bold; padding-top: 10px;">
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
    <div style="padding-top:20px">
        <div style="display:flex;border-bottom: solid;">
            <div style="width:40%;">Product Code</div><div style="width:50%;">Description</div><div style="width:10%;">Qty</div>
        </div>
        @foreach($order->lines as $key => $line)
        <div style="display:flex;padding-top:10px;">
            <div style="width:40%;">{{$line->product_code}}</div><div style="width:50%;">{{$line->product_description}}</div><div style="width:10%;text-align:end;">{{$line->qty}}</div>
        </div>
        @endforeach
    </div>
    </body>
</html>