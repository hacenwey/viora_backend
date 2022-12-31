<!DOCTYPE html>
<html>

<head>
    {{-- <title>@lang('global.blpdf')</title> --}}
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
            font-size: 18px;
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
            background-image: url("{{ asset('images/footer_frame.png') }}");
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
            float: right;
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
/*
        .table .thead tr th:not(:first-child) {
            font-weight: 300;
        } */

        .table tr th:first-child {
            border-radius: 15px 0 0 0;
            padding-left: 40px;
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

    @if($orders->count() > 0)

        <div class="invoice-header">
            <div class="float-left site-logo">
                <img src="{{ settings()->get('logo') }}">
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
                        </div>
                        <div class="invoice-right-top float-right" class="text-right">
                            <h1 style="color: #037D99;text-transform: uppercase;letter-spacing:5px;font-weight:900;">
                                @lang('global.blpdf')
                            </h1>
                            <span style="font-size: 12px">@lang('global.date'): {{ date('Y-m-d') }}</span>
                            <p style="font-size: 12px">@lang('global.orders_nb'): {{ $orders->count() }}</p>
                            <p style="font-size: 12px">@lang('global.total'): {{ getFormattedPrice($orders->sum('total_amount')) }}</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <section class="order_details pt-3">
                        <table class="table table-striped">
                            <thead class="thead" style="border-radius: 15px 0 0 0;">
                                <tr style="border-radius: 15px 0 0 0;">
                                    <th class="text-center" style="border-radius: 15px 0 0 0;">@lang('cruds.order.fields.no')</th>
                                    <th class="text-center">@lang('cruds.order.fields.name')</th>
                                    <th class="text-center">@lang('cruds.order.fields.phone')</th>
                                    <th class="text-center">@lang('cruds.order.fields.address')</th>
                                    <th class="text-right" style="padding-right: 10px;border-top-radius:10px">@lang('global.total')</th>
                                    <th class="text-center" style="width: 25%">@lang('global.comment')</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            #{{ $order->reference }}
                                        </td>
                                        <td class="text-center">
                                            {{ $order->first_name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $order->phone }}
                                        </td>
                                        <td class="text-center">
                                            {{ $order->address1 }}
                                        </td>
                                        <td class="text-right">
                                            {{ getFormattedPrice($order->total_amount) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>
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
