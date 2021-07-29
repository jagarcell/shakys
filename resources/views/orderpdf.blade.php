<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    </head>
    <body style="padding: 50px;">
    <div  style="font-size: 30px; font-weight: bold; padding-bottom: 15px;width:75%;">Order #: {{$order->id}}</div>
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
    <br>
    <div>
        <table style="width:100%; border-collapse:collapse;">
            <thead style="border-bottom: solid black 3px;">
                <tr>
                    <th style="text-align:left;" colspan="3">
                        Product Code                            
                    </th>
                    <th style="text-align:left;" colspan="5">
                        Description
                    </th>
                    <th style="text-align:left;" colspan="2">
                        Qty                    
                    </th>
                </tr>
            </thead>
            <tbody>
            @foreach($order->lines as $key => $line)
                <tr style="height:1.5em;">
                    <td style="text-align:left;" colspan="3">
                        {{$line->product_code}}
                    </td>
                    <td style="text-align:left;" colspan="5">
                        {{$line->product_description}}
                    </td>
                    <td style="text-align:right;" colspan="2">
                        {{$line->qty}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>    
    </div>
    </body>
</html>