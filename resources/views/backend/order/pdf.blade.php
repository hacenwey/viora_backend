<!DOCTYPE html>
<html>

<head>
    <title>@lang('cruds.order.title_singular') @if($order)- {{$order->order_number}} @endif</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500&display=swap">
    <style type="text/css">
        @page {
            margin: 0px;
            size: A4;
            width: 210mm;
            height: 297mm;
        }
        html, body {
            width: 210mm;
            height: 297mm;
            margin: 0px;
            font-family: 'Montserrat', sans-serif;
            font-style: normal;
        }
        .invoice-header {
            margin: 0;
            padding: 0;
            position: fixed;
            top: 0;
            width: 100%;
        }
        .invoice-footer {
            margin: 0;
            padding: 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #037D99;
            background-position: center;
            background-repeat: no-repeat;
            background-size: 100% 100px;
            background-color: #FFF;
            color: #FFF;
        }
        .invoice-footer .footer-content{
            display: inline-flex;
            width: 100%;
        }
        .invoice-footer .footer-content div{
            width: 100%;
            position: fixed;
            bottom: 30mm;
        }
        .invoice-footer .footer-content div.address{
            max-width: 45mm;
            margin-left: 65px;
            margin-top: 36px;
            font-size: 9px;
        }
        .invoice-footer .footer-content div.phone{
            max-width: 45mm;
            margin-left: 117mm;
            margin-top: 40px;
            font-size: 9px;
        }
        .invoice-footer .footer-content div.email{
            max-width: 45mm;
            margin-left: 165mm;
            margin-top: 40px;
            font-size: 9px;
        }
        .invoice-footer .footer-content div.web{
            max-width: 45mm;
            margin-left: 271px;
            margin-top: 40px;
            font-size: 9px;
        }
        .invoice-header, .header-space,
        .invoice-footer, .footer-space {
            height: 100px;
        }
        .site-logo {
            margin-top: 20px;
            margin-left: 50px;
        }

        .site-logo img {
            width: 250px;
        }

        .top-corner img {
            position: absolute;
            top: 0;
            right: 0;
            width: 250px;
        }

        .invoice-description {
            margin-top: 20px;
            padding: 0 50px;
            width: 100%;
        }

        .order_details {
            padding: 0 50px;
            width: 100%;
        }

        .invoice-left-top p {
            margin: 0;
            line-height: 20px;
            margin-bottom: 3px;
        }

        .thead {
            background: #037D99;
            color: #FFF;
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            width: 100%;
        }

        .table .thead th {
            border: none;
            padding: 10px 0;
        }

        .table .thead tr th:not(:first-child) {
            font-weight: 300;
        }

        .table tr th:first-child {
            border-radius: 15px 0 0 0;
            padding-left: 40px;
            width: 50%;
        }

        .table tr td:first-child {
            padding-left: 40px;
        }

        .table tr td:last-child {
            padding-right: 40px;
        }

        .table tr th:last-child {
            border-radius: 0 15px 0 0;
            padding-right: 40px;
        }

        .authority h5 {
            margin-top: -10px;
            color: green;
        }

        .thanks h4 {
            color: green;
            font-size: 25px;
            font-weight: normal;
            margin-top: 20px;
        }

        .table tfoot .empty {
            border: none;
        }

        .table-bordered {
            border: none;
        }

        .table-header {
            padding: .75rem 1.25rem;
            margin-bottom: 0;
            background-color: rgba(0, 0, 0, .03);
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            width: 100%;
        }
        @media print {
            html, body {
                width: 210mm;
                height: 297mm;
            }
        }

        .table td,
        .table th {
            padding: 1.2rem;
        }
        .tfoot{
            border-top: 2px solid #000;
        }
        .tfoot td{
            border-bottom: 2px solid rgba(0,0,0,.05);
        }
        .tfoot td:last-child{
            background: rgba(0,0,0,.05);
            border-bottom: 2px solid #FFF;
        }
.invoice-footer-title{
     color:#000;
}
.footer {
  position: fixed;
  border-top: 2px solid black;
  left: 0;
  bottom: 0;
  width: 100%;
  color: black;
  text-align: center;
}
    </style>
</head>

<body style="width: 100%;font-size:12px">

    @if($order)

        <div class="invoice-header">
            <div class="float-left site-logo">
                <img src="https://talabat.awlyg.tech/_nuxt/img/logo.d0ee1d7.png">            </div>
            <div class="top-corner">
                {{-- <img src="{{ asset('images/head_frame.png') }}" alt=""> ----}}
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="footer">
            <span class="invoice-footer-title">Merci pour votre achat</span>
        </div>
        <table style="width:100%">
            <thead><tr><td>
            <div class="header-space">&nbsp;</div>
            </td></tr></thead>
            <tbody><tr><td>
                <div class="content" style="width: 100%">
                    <div class="invoice-description">
                        <div class="invoice-left-top float-left">
                            <span>@lang('global.invoice_to')</span>
                            <p style="color: #037D99;margin-left:10px;text-transform: uppercase;letter-spacing:3px;">{{$order->first_name}} {{$order->last_name}}</p>
                            <p style="margin-left:10px">{{ $order->phone }}</p>
                            <p style="margin-left:10px">{{ $order->email }}</p>
                        </div>
                        <div class="invoice-right-top float-right" class="text-right">
                            <h1 style="color: #037D99;text-transform: uppercase;letter-spacing:5px;font-weight:900;">
                                FACTURE
                            </h1>
                                <span style="font-size: 12px">@lang('global.invoice_no'): {{$order->reference}}</span>
                                <p style="font-size: 12px">@lang('global.invoice_date'):
                                    {{ $order->created_at->format('D d, m Y, h:m') }}</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <section class="order_details pt-3">
                        <table class="table table-striped">
                            <thead class="thead" style="border-radius: 15px 0 0 0;">
                                <tr style="border-radius: 15px 0 0 0;">
                                    <th style="padding-left: 10px;border-radius: 15px 0 0 0;">@lang('cruds.product.title')</th>
                                    <th class="text-center">@lang('cruds.product.fields.price')</th>
                                    <th class="text-center">@lang('global.quantity')</th>
                                    <th class="text-right" style="padding-right: 10px;border-top-radius:10px">@lang('global.total')</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @foreach($order->products as $item)
                                    <tr>
                                        <td>
                                            @php
                                                $image = explode(',', $item->product->photo);
                                            @endphp

                                            <div style="display:flex;">
                                                @if (strpos($image[0] ?? '', '.png') === false)
                                                <img src="{{ $image[0] ?? '' }}" width="30" height="30" style="margin-left: -30px">
                                                @endif
                                                <div style="margin-left: 10px">
                                                    <b>
                                                        {{ $item->product->title ?? ''}}
                                                    </b>
                                                
                                                    
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {{ getFormattedPrice($item->price) }}
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-right"><span>{{ getFormattedPrice($item->sub_total) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="tfoot">
                                <tr>
                                    <td scope="col" class="empty"></td>
                                    <td scope="col" class="empty"></td>
                                    <td scope="col" class="text-center">@lang('global.subtotal')</td>
                                    <td scope="col" class="text-right" style="background: rgba(0,0,0,.05);border-bottom: 2px solid #FFF;"> <span>{{ getFormattedPrice($order->sub_total) }}</span></td>
                                </tr>
                                <tr>
                                    <td scope="col" class="empty"></td>
                                    <td scope="col" class="empty"></td>
                                    <td scope="col" class="text-center">@lang('global.shipping')</td>
                                    <td class="text-right" style="background: rgba(0,0,0,.05);border-bottom: 2px solid #FFF;">
                                        <span>
                                            @if($order->shipping_id != null)
                                                {{ $order->shipping->type }} |
                                                {{ getFormattedPrice($order->shipping->price) }}
                                            @else
                                                @lang('global.local_pickup')
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @if ($order->coupon > 0)
                                    <tr>
                                        <td scope="col" class="empty"></td>
                                        <td scope="col" class="empty"></td>
                                        <td scope="col" class="text-center">@lang('global.discount')</td>
                                        <td class="text-right" style="background: rgba(0,0,0,.05);border-bottom: 2px solid #FFF;">
                                            <span>
                                                {{ getFormattedPrice($order->coupon) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td scope="col" class="empty"></td>
                                    <td scope="col" class="empty"></td>
                                    <td scope="col" class="text-center">@lang('global.total')</td>
                                    <td class="text-right" style="background: #037D99;color:#FFF">
                                        <b>
                                            {{ getFormattedPrice($order->total_amount) }}
                                        </b>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </section>
                    <div class="float-right mt-5" style="margin-right: 50px;">
                        {{-- <p style="border-top:1px solid #b3b3b3;margin-right: -50px">@lang('global.signature')</p> --}}
                        {{-- <img src="{{ settings('signature') }}" alt="" width="150" style="margin-left: -50px"> --}}
                    </div>
                </div>
            </td></tr></tbody>
            <tfoot><tr><td>
            <div class="footer-space">&nbsp;</div>
            </td></tr></tfoot>
        </table>

        
    @else
        <h5 class="text-danger">Invalid</h5>
    @endif
    
</body>

</html>
