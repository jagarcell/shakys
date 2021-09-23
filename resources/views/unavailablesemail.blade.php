<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
            .bodyClass{
                font-size: 12px;
            }

            .alignRight{
                text-align: right;
            }

            .title{
                width: 100%;
            }

            .headerWrap{
                width: 100%; 
                display: flex; 
                height: 40px; 
                border-bottom: solid black 5px;
            }

            .lineWrap{
                width: 100%; 
                display: flex; 
                height: 40px; 
                border-bottom: solid black 2px;
            }

            .productSegment {
                width: 40%; 
                margin: auto; 
                padding-left:20px;
                text-align: left;
            }

            .qtySegment{
                width: 20%; 
                margin: auto; 
                padding-left:20px;
                text-align: right;
            }

            @media screen and (max-width:767px) {
             
                .bodyClass{
                    font-size: 8px;
                } 
            }
        </style>        
    </head>
    <body class="bodyClass">
        <div class="title">
            <div style="width:fit-content; margin:auto;">Unavailable Products At</div>
            <div style="width: fit-content; margin: auto; height: 40px; margin-top: auto;">{{$Unavailables[0]->supplier_name}}</div>
        </div>
        <div class="headerWrap">
            <div class="productSegment">Product</div>
            <div class="qtySegment">Ordered</div>
            <div class="qtySegment">Found</div>
            <div class="qtySegment">Unavailable</div>
        </div>
        @foreach($Unavailables[0]->lines as $Key => $Line)
        <div class="lineWrap">
            <div class="productSegment">{{$Line->internal_description}}</div>
            <div class="qtySegment">{{$Line->qty}}</div>
            <div class="qtySegment">{{$Line->available_qty}}</div>
            <div class="qtySegment">{{$Line->qty - $Line->available_qty}}</div>
        </div>
        @endforeach
    </body>
</html>