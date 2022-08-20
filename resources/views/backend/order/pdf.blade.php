<!DOCTYPE html>
<html>

<head>
    {{-- <title>@lang('cruds.order.title_singular') @if($order)- {{$order->order_number}} @endif</title> --}}
    <link rel="stylesheet" href={{url("https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css")}}>
    <link href={{url("https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500&display=swap")}}>
    <style type="text/css">
        /* @page {
            margin: 0px;
            size: A4;
            width: 210mm;
            height: 297mm;
        } */
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
            /* position: fixed; */
            top: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 1000px;
            height: 40px;
        }
        .invoice-footer {
            margin: 0;
            padding: 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            background-image: url("images/footer_frame.png");
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

        /* .table td,
        .table th {
            padding: 1rem;
        } */
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
    </style>
</head>

<body style="width: 100%;font-size:12px">
    <div class="invoice-header">
        <div class="float-left site-logo">
            <img src="data:image/png;base64,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" alt="TababatOnline">
        </div>
        {{-- <div class="top-corner">
            <img src="images/head_frame.png" alt="">
        </div>
        <div class="clearfix"></div> --}}
    </div>
    @foreach($orders as $order)
        <div class="invoice-footer">
            <div class="footer-content">
                <div class="address">
                    <span>@lang('global.address')</span>
                    <p>{{ settings()->get('address') }}</p>
                </div>
                <div class="phone">
                    <span>@lang('global.phone')</span>
                    <p>{{ settings()->get('phone') }}</p>
                </div>
                <div class="email">
                    <span>@lang('global.email')</span>
                    <p>{{ settings()->get('email') }}</p>
                </div>
                <div class="web">
                    <span>@lang('global.web')</span>
                    <p>{{ url('/') }}</p>
                </div>
            </div>
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
                                @lang('global.invoice')
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
                                                $image = explode(',', $item->product->photo ?? "https://coolbackgrounds.io/images/backgrounds/white/pure-white-background-85a2a7fd.jpg");
                                            @endphp
                                            <div style="display: flex">
                                                <img src="{{ $image[0] }}" width="30" height="40" style="margin-left: -30px">
                                                <div style="margin-left: 10px">
                                                    <b>
                                                        {{ $item->product->title }}
                                                    </b>
                                                    <br>
                                                    <span>
                                                        @php
                                                            $str = strip_tags($item->product->summary);
                                                            // if (strlen($str) > 40)
                                                            //     $str = substr($str, 0, 40) . '...';
                                                        @endphp
                                                        {!! $str !!}
                                                    </span>
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
                </div>
            </td></tr></tbody>
            <tfoot><tr><td>
            <div class="footer-space">&nbsp;</div>
            </td></tr></tfoot>
        </table>
   
    @endforeach
</body>

</html>
