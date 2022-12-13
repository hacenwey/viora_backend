<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <style>
*{
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Roboto', sans-serif;
}
.main_container{
    width: 100%;
    height: 250px;
    padding: 20px 50px 50px;
    margin-bottom: 30px;
    font-family: 'Roboto', sans-serif;
}
.logo{
    width: 50%;
    height: 100px;
    float: left;
}
h2{
    margin-bottom: 10px;
    font-family: 'Roboto', sans-serif;
}
h3{
    margin-bottom: 10px;
    font-family: 'Roboto', sans-serif;
}
.logo_image{
    width: 200px;
}
table{
    width: 100%;
    font-family: 'Roboto', sans-serif;

}
.tableau_facture{
    width: 900px;
    padding: 10px 0px;
    margin-top: 40px;
    font-family: 'Roboto', sans-serif;

}
.head_table{
    width: 100%;
    height: 50px;
    background-color: black;

}
.content{
    position: relative;
    width: 100%;
    height: 20px;
    font-family: 'Roboto', sans-serif;
}

.image-cont{
    width: 60px;
    height: 50px;
    float: left;
}
.image_product{
    width: 50px;

}
.produits{
    color: white;
    width: 300px;
    padding: 15px 10px;
    float: left;
    font-family: 'Roboto', sans-serif;

}
.photo{
    width: 300px;
    height: 50px;
    padding: 10px 10px;
    float: left;
}
.name-product{
    float: left;
    width: 200px;
    height: 50px;

}
.quantite{
    width: 100px;
    float: left;
    color: white;
    padding: 15px 0;
    font-family: 'Roboto', sans-serif;



}
.last-facture{
    width: 100%;
    height: 80px;
    padding: 0 30px;
    font-family: 'Roboto', sans-serif;
}

.number{
    width: 100px;
    height: 50px;
    float: left;
    color: black;
    padding: 15px 0;
    font-family: 'Roboto', sans-serif;


}
.total{
    width: 250px;
    float: right;
    margin-top: 20px;
    font-family: 'Roboto', sans-serif;
}
.sous-total{
    height: 25px;
    font-family: 'Roboto', sans-serif;
}

.title{
    width: 150px;
    color: black;
    font-size: 15px;
    float: left;
    font-family: 'Roboto', sans-serif;
}
.title-price{
    width: 80px
    height: 40px;
    float: left;
    color: rgb(84, 84, 84);
    font-family: 'Roboto', sans-serif;
}

.fotter{
    position: relative;
    width: 100%;
    padding: 20px 20px;
    margin-top: 40px;
    text-align: center;
    font-family: 'Roboto', sans-serif;
}


    </style>

</head>
<body>
    @foreach($orders as $order)

    <div class="main_coFntainer">
        <div class="logo">
            <img class="logo_image" src="
            https://talabat.awlyg.tech/_nuxt/img/logo.d0ee1d7.png" alt="TababatOnlineg" alt="">
        </div>
        <div class="logo">
            <h3>Talabate Online</h3>
            <span>Email: {{ settings()->get('email') }}</span><br>
            <span>Mob: {{ settings()->get('phone') }}</span><br>
            <span>Nouakchott-Mauritanie</span><br>
        </div>
        <div class="logo">
            <h2>FACTURE</h2>

            <span>{{$order->first_name}} </span><br>
            <span>{{$order->last_name}}</span><br>
            <span>{{ settings()->get('address') }}</span><br>
            <span>{{ $order->phone }}</span><br>
        </div>
        <div class="logo">
            <table>
                <tr>
                    <td>Date de la facture :  </td>
                    <td>{{date('d-m-Y')}}</td>
                </tr>


                <tr>
                    <td>Méthode de paiement :  </td>
                    <td>Paiment à la livraison</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="tableau_facture">
        <div class="head_table">
            <div class="produits">Produits</div>
            <div class="quantite">Quantite</div>
            <div class="quantite">Prix</div>
            <div class="quantite">Total</div>
        </div>

        @foreach($order->products as $item)
        <div class="content">
            <div class="photo">
                <div class="image-cont"><img class="image_product" src="{{$item->product->photo ?? 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Solid_white_bordered.svg/2048px-Solid_white_bordered.svg.png'}}"></div>
                <div class="name-product">{{ $item->product->title??null  }}</div>
            </div>
            <div class="number">{{ $item->quantity}}</div>
            <div class="number">{{ getFormattedPrice($item->price)}}</div>
            <div class="number">{{ getFormattedPrice($item->sub_total)}}</div>
        </div>
        <hr>
        @endforeach
    </div>
    <div class="last-facture">
        <div class="total">
            <div class="sous-total">
                <div class="title">Sous-Total</div>
                <div class="title-price"> {{ getFormattedPrice($item->price) }}</div>
            </div>
            <hr>
            <div class="sous-total">
                <div class="title">Livraison</div>
                <div class="title-price"> @if($order->shipping_id != null)
                    {{ $order->shipping->type }} |
                    {{ getFormattedPrice($order->shipping->price) }}
                @else
                    @lang('global.local_pickup')
                @endif</div>
            </div>
            <hr>
            <div class="sous-total">
                <div class="title">Total</div>
                <div class="title-price">  {{ getFormattedPrice($order->total_amount) }}</div>
            </div>
        </div>
    </div>

    <div class="fotter">
        <hr>
        <p>Merci pour votre achat!</p>
        <span>TalabateOnline.mr</span>
    </div>
    @endforeach

</body>
</html>
