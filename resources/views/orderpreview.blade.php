<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    </head>
    <body style="padding:50px;">
    <div style="display:flex;">
        <div style="font-size: 30px; font-weight: bold; padding-bottom: 15px;width:75%;">Order #: {{$Order->id}}</div>
        <div style="display:grid;">
            <a href="/exporttopdf?id={{$Order->id}}">Export To PDF</a>
            <br>
            <a href="/{{$Order->previousURL}}">Back To Orders</a>
        </div>
    </div>
    @if(isset($Order->instructions1))
    <div>{{$Order->instructions1}}</div>
    @endif
    <div style="font-weight:bold; padding-top: 10px;">
        @if(isset($Order->instructions2))
        <div>{{$Order->instructions2}}</div>
        @endif
        @if(isset($Order->instructions3))
        <div>{{$Order->instructions3}}</div>
        @endif
        @if(isset($Order->instructions4))
        <div>{{$Order->instructions4}}</div>
        @endif
        @if(isset($Order->instructions5))
        <div>{{$Order->instructions5}}</div>
        @endif
    </div>
    <div style="padding-top:20px">
        <div style="display:flex;border-bottom: solid;">
            <div style="width:40%;">Product Code</div><div style="width:50%;">Description</div><div style="width:10%;">Qty</div>
        </div>
        @foreach($Order->lines as $key => $line)
        <div style="display:flex;padding-top:10px;">
            <div style="width:40%;">{{$line->product_code}}</div><div style="width:50%;">{{$line->product_description}}</div><div style="width:10%;text-align:end;">{{$line->qty}}</div>
        </div>
        @endforeach
    </div>
    </body>
</html>