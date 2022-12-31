<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talabat Livraison</title>
    <style>
        *{
            font-family:  sans-serif;
            margin: 0;
            padding: 0;
        }
        .content_gestion_roles{
            width: 900px;
            margin: 40px auto 0 auto;
            display: flex;
            flex-direction: column;
        }
        .head_livraison{
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        .logo_head_livraison{
            width: 100%;
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .img_logo_talabat{
            width: 350px;
            height: 110px;
        }
        .head_content_table_head_livraison{
            background-color: #000000;
            color: #ffffff;
            display: grid;
            grid-template-columns: 1fr;
        }
        .content_td{
            text-align: left;
        }
        .content_td, .content_td1{
            width: 100%;
            margin: 0;
        }
        .content_td1{
            text-align: end;
        }
        h2{
            margin: 0;
            text-align: left;
        }
        .top_tabel{
            padding: 3px 0;
            font-size: 17px;
            font-weight: 900;
        }
        .top_tabel:first-child{
            border-right: none !important;
        }
        .top_tabel{
        
            border-bottom: none !important;
        }
        .tr_head_livraison{
            display: grid;
            grid-template-columns: .33fr .67fr;
        }
        .tr_head_livraison >td{
            text-align: center;
        }
        .table_gestion_des_inspections{
            width: 100%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            border-collapse: collapse;
        }
        .head_table{
            background-color: #000000;
            color: #ffffff;
            display: grid;
            grid-template-columns: 0.12fr 0.12fr 0.17fr 0.35fr 0.26fr;
        }
        .content_table{
            display: grid;
            grid-template-columns: 0.12fr 0.12fr 0.17fr 0.35fr 0.13fr 0.13fr;
            align-items: center;
            border-radius: 5px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        .td_radio{
            text-align: center;
            font-size: 14px;
        }

        td{  
            border: 2px solid #000;
            padding: 12px 0;
        }
        .td_radio:nth-child(1),
        .td_radio:nth-child(2),
        .td_radio:nth-child(3),
        .td_radio:nth-child(4),
        .td_radio:nth-child(5){
            border-right: none !important;
        }
        th{
            font-size: 16px !important;
            padding: 20px 0 4px 0;
        }
        th:first-child{
            text-align: left !important;
            padding-left: 10px;
        }
        th:last-child{
            text-align: right !important;
            padding-right: 5px;
        }
    </style>
</head>
<body>
    <div class="content_gestion_roles">
        <div class="head_livraison">
            <div class="logo_head_livraison">
                <img class="img_logo_talabat" src="https://talabat.awlyg.tech/_nuxt/img/logo.d0ee1d7.png" alt="">
            </div>
            <div class="table_head_livraison">
                <table class="">
                    <h2>Bon de livraison :</h2>
                    <tbody class="">
                        <tr class="tr_head_livraison">
                            <td class="top_tabel">
                                @lang('global.date'):
                            </td>
                            <td class="top_tabel">
                                 {{ date('Y-m-d') }}
                            </td>
                        </tr>
                        <tr class="tr_head_livraison">
                            <td class="top_tabel">
                                @lang('global.orders_nb'):                           
                             </td>
                            <td class="top_tabel">
                                 {{ $orders->count() }}
                            </td>
                        </tr>
                        <tr class="tr_head_livraison">
                            <td class="top_tabel">
                                @lang('global.total'):                             
                            </td>
                            <td class="top_tabel">
                                {{ getFormattedPrice($orders->sum('total_amount')) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <table class="table_gestion_des_inspections">
            <tr class="head_table">
                <th>N°Cmd</th>
                <th>@lang('cruds.order.fields.name')</th>
                <th>@lang('cruds.order.fields.phone')</th>
                <th>@lang('cruds.order.fields.address')</th>
                <th>@lang('global.comment')</th>
            </tr>
            <tbody class="tbody_content">
                @foreach($orders as $order)
                                    
                                    <tr class="content_table">
                                        <td class="td_radio">
                                            <p class="content_td"><b>#{{ $order->reference }}</b></p>
                                        </td>
                                        <td class="td_radio">
                                            <b>{{ $order->first_name }}</b>
                                        </td>
                                        <td class="td_radio">
                                            <b>{{ $order->phone }}</b>
                                        </td>
                                        <td class="td_radio">
                                            <b>{{ $order->address1 }}</b>
                                        </td>
                                        <td class="td_radio">
                                            <p class="content_td1"><b>{{ getFormattedPrice($order->total_amount) }}</b></p>
                                        </td>
                                        <td class="td_radio">
                                            <p class="content_td1"><b></b></p>
                                        </td>
                                    </tr>
                                @endforeach
                
            </tbody>
        </table>
    </div>
</body>
</html>