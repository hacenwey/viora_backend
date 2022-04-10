@extends('frontend.layouts.master')

@section('meta')
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name='copyright' content=''>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="keywords" content="online shop, purchase, cart, ecommerce site, best online shopping">
<meta name="description" content="{{$product->summary}}">
<meta property="og:url" content="{{route('backend.product-detail',$product->slug)}}">
<meta property="og:type" content="article">
<meta property="og:title" content="{{$product->title}}">
<meta property="og:image" content="{{$product->photo}}">
<meta property="og:description" content="{{$product->description}}">
@endsection
@section('title', settings('app_name'). ' | '. $product->title)
@section('main-content')

@include('frontend.layouts.loader', ['page' => 'product'])

<!-- breadcrumb start -->
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="page-title">
                    <h2>{{ $product->title }}</h2>
                </div>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('backend.home') }}">{{ trans('global.home') }}</a></li>
                        @foreach ($product->categories as $category)
                            <li class="breadcrumb-item {{ count($category->children) < 1 ? 'active' : '' }}" @if(count($category->children) < 1) aria-current="page" @endif>
                                <a href="{{ route('backend.product-cat',$category->slug) }}">
                                    {{ $category->title }}
                                </a>
                            </li>
{{--                            @include('frontend._partials.category', $category)--}}
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb end -->

<!-- section start -->
<section>
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-1 col-sm-2 col-xs-12">
                    <div class="row">
                        <div class="col-12 p-0">
                            <div class="slider-right-nav">
                                @php
                                    $photo = explode(',', $product->photo);
                                @endphp
                                @foreach($photo as $data)
                                    <div>
                                        <img src="{{ $data }}" alt="{{ $product->title }}" class="img-fluid blur-up lazyload">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-10 col-xs-12 order-up">
                    <div class="product-right-slick">
                        @php
                            $photo = explode(',', $product->photo);
                        @endphp
                        @foreach($photo as $key => $data)
                            <div>
                                <img src="{{ $data }}" alt="{{ $product->title }}" width="100%" class="img-fluid blur-up lazyload image_zoom_cls-{{ $key }}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="product-right product-description-box">
                        <h2>{{ $product->title }}</h2>
                        <div class="border-product">
                            <h6 class="product-title">{{ trans('global.product_details') }}</h6>
                            <p>{!!($product->summary)!!}</p>
                        </div>
                        {{-- <div class="single-product-tables border-product detail-section">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Febric:</td>
                                        <td>Chiffon</td>
                                    </tr>
                                    <tr>
                                        <td>Color:</td>
                                        <td>Red</td>
                                    </tr>
                                    <tr>
                                        <td>Material:</td>
                                        <td>Crepe printed</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> --}}
                        <div class="border-product">
                            <h6 class="product-title">share it</h6>
                            <div class="product-icon">
                                <ul class="product-social">
                                    <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ route('backend.product-detail',$product->slug) }}" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    {{-- <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fa fa-rss"></i></a></li> --}}
                                </ul>
                                <form class="d-inline-block" action="{{route('backend.add-to-wishlist',$product->slug)}}" method="GET">
                                    @csrf
                                    <button class="wishlist-btn" type="submit">
                                        <i class="fa {{ isProductWishlisted($product->id) ? 'fa-heart text-danger' : 'fa-heart-o' }}"></i>
                                        <span class="title-font">{{ trans('global.add_to_wishlist') }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        {{-- <div class="border-product">
                            <h6 class="product-title">100% SECURE PAYMENT</h6>
                            <div class="payment-card-bottom">
                                <ul>
                                    <li>
                                        <a href="#"><img src="../assets/images/icon/visa.png" alt=""></a>
                                    </li>
                                    <li>
                                        <a href="#"><img src="../assets/images/icon/mastercard.png" alt=""></a>
                                    </li>
                                    <li>
                                        <a href="#"><img src="../assets/images/icon/paypal.png" alt=""></a>
                                    </li>
                                    <li>
                                        <a href="#"><img src="../assets/images/icon/american-express.png"
                                                alt=""></a>
                                    </li>
                                    <li>
                                        <a href="#"><img src="../assets/images/icon/discover.png" alt=""></a>
                                    </li>
                                </ul>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="product-right product-form-box">
                        @php
                            $after_discount = 0;
                        @endphp
                        @if ($product->discount > 0)
                            @php
                                $after_discount=($product->price-(($product->price*$product->discount)/100));
                            @endphp

                            <h4><del>{{ getFormattedPrice($product->price) }}</del><span>{{ $product->discount }}% off</span></h4>
                            <h3>{{ getFormattedPrice($after_discount) }}</h3>
                            @else
                            <h3>{{ getFormattedPrice($product->price) }}</h3>
                        @endif

                        <form action="{{ route('backend.single-add-to-cart', ['slug' => $product->slug]) }}" method="POST">
                            @csrf
                            <div class="product-description border-product">
                                {{-- @if ($product->hasActiveDiscount()) --}}
                                    <div class="discount-wrapper" @if(!$product->hasActiveDiscount()) style="display:none" @endif>
                                        <h6 class="product-title">@lang('global.discount_timer')</h6>
                                        <div class="timer">
                                            <p id="demo">
                                                <span>
                                                    <span id="day" style="width: 0"></span>
                                                    <span class="padding-l">:</span>
                                                    <span class="timer-cal">@lang('global.days')</span>
                                                </span>
                                                <span>
                                                    <span id="hour" style="width: 0"></span>
                                                    <span class="padding-l">:</span>
                                                    <span class="timer-cal">@lang('global.hrs')</span>
                                                </span>
                                                <span>
                                                    <span id="min" style="width: 0"></span>
                                                    <span class="padding-l">:</span>
                                                    <span class="timer-cal">@lang('global.min')</span>
                                                </span>
                                                <span>
                                                    <span id="sec" style="width: 0"></span>
                                                    <span class="timer-cal">@lang('global.sec')</span>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                {{-- @endif --}}
                                @include('frontend._partials._attributes')
                                <h6 class="product-title">{{ trans('global.quantity') }}</h6>
                                <div class="qty-box">
                                    <div class="input-group">
                                        <span class="text-danger stock-alert" style="display: none;position: absolute;bottom:-23px">@lang('global.stock_errors', ['stock' => $product->stock])</span>
                                        <span class="input-group-prepend">
                                            <button type="button" class="btn quantity-left-minus" data-type="minus" data-field="">
                                                <i class="ti-angle-left"></i>
                                            </button>
                                        </span>
                                        <input type="hidden" name="productId" value="{{$product->id}}">
                                        <input type="hidden" name="stock" class="stock-input" value="{{ $product->stock }}">
                                        <input type="text" name="quantity" class="form-control input-number qty-input" value="1">
                                        <span class="input-group-prepend">
                                            <button type="button" class="btn quantity-right-plus" data-type="plus" data-field="">
                                                <i class="ti-angle-right"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="product-buttons">
                                <button type="submit" {{ $product->stock == 0 ? 'disabled' : '' }} class="btn btn-solid">{{ trans('global.add_to_cart') }}</button>
                                <a href="{{ route('backend.buy-now', ['slug' => $product->slug]) }}" class="btn btn-solid">{{ trans('global.buy_now') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Section ends -->

<!-- product-tab starts -->
<section class="tab-product m-0">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-toggle="tab"
                            href="#top-home" role="tab" aria-selected="true">{{ trans('global.description') }}</a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-toggle="tab" href="#top-contact" role="tab" aria-selected="false">{{ trans('global.video') }}</a>
                        <div class="material-border"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="review-top-tab" data-toggle="tab" href="#top-review" role="tab" aria-selected="false">{{ trans('global.write_review') }}</a>
                        <div class="material-border"></div>
                    </li>
                </ul>
                <div class="tab-content nav-material" id="top-tabContent">
                    <div class="tab-pane fade show active" id="top-home" role="tabpanel"
                        aria-labelledby="top-home-tab">
                        <p>{!! ($product->description) !!}</p>
                    </div>
                    <div class="tab-pane fade" id="top-contact" role="tabpanel" aria-labelledby="contact-top-tab">
                        <div class="mt-4 text-center">
                            {{-- <iframe width="560" height="315" src="https://www.youtube.com/embed/BUWzX78Ye_8"
                                allow="autoplay; encrypted-media" allowfullscreen></iframe> --}}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="top-review" role="tabpanel" aria-labelledby="review-top-tab">
                        <form class="theme-form" method="post" action="{{route('backend.review.store',$product->slug)}}">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="media">
                                        <label>@lang('global.rating')</label>
                                        <div class="media-body ml-3">
                                            <div class="star-rating">
                                                <div class="star-rating__wrap">
                                                    <input class="star-rating__input" id="star-rating-5" type="radio" name="rate" value="5">
                                                    <label class="star-rating__ico fa fa-star-o" for="star-rating-5" title="5 out of 5 stars"></label>
                                                    <input class="star-rating__input" id="star-rating-4" type="radio" name="rate" value="4">
                                                    <label class="star-rating__ico fa fa-star-o" for="star-rating-4" title="4 out of 5 stars"></label>
                                                    <input class="star-rating__input" id="star-rating-3" type="radio" name="rate" value="3">
                                                    <label class="star-rating__ico fa fa-star-o" for="star-rating-3" title="3 out of 5 stars"></label>
                                                    <input class="star-rating__input" id="star-rating-2" type="radio" name="rate" value="2">
                                                    <label class="star-rating__ico fa fa-star-o" for="star-rating-2" title="2 out of 5 stars"></label>
                                                    <input class="star-rating__input" id="star-rating-1" type="radio" name="rate" value="1">
                                                    <label class="star-rating__ico fa fa-star-o" for="star-rating-1" title="1 out of 5 stars"></label>
                                                    @error('rate')
                                                    <span
                                                        class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="name">@lang('global.name')</label>
                                    <input type="text" class="form-control" id="name" placeholder="@lang('global.enter') @lang('global.your') @lang('global.name')" name="name" value="{{ Auth::guard()->check() ? Auth::guard()->user()->name : '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="email">@lang('global.email')</label>
                                    <input type="text" class="form-control" id="email" placeholder="@lang('global.enter') @lang('global.your') @lang('global.email')" name="email" value="{{ Auth::guard()->check() ? Auth::guard()->user()->email : '' }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="subject">@lang('global.subject')</label>
                                    <input type="text" class="form-control" id="subject" name="title" placeholder="@lang('global.review') @lang('global.subject')" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="review">@lang('global.review')</label>
                                    <textarea class="form-control" placeholder="@lang('global.write_review')" name="review" id="review" rows="6"></textarea>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-solid" type="submit">@lang('global.submit') @lang('global.your') @lang('global.review')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- product-tab ends -->

<!-- section start -->
<section class="section-b-space blog-detail-page review-page">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="d-flex">
                    <h6>
                        {{ ceil($product->getReview->avg('rate')) }}
                        <span>(@lang('global.overall'))</span>
                    </h6>
                    <span class="ml-2">
                        @lang('global.based_on', ['count' => $product->getReview->count()])
                    </span>
                </div>
            </div>
            <div class="col-sm-12">
                <ul class="comment-section">
                    @foreach($product['getReview'] as $data)
                        <li id="review{{ $data->id }}" style="width: 100%">
                            <div class="media">
                                @if($data->user_id != null && $data->user_info['photo'])
                                    <img src="{{ $data->user_info['photo'] }}" alt="{{ $data->user_info['photo'] }}">
                                @else
                                    <img src="{{ asset('/assets/images/avtar.jpg') }}" alt="">
                                @endif
                                <div class="media-body">
                                    <div class="d-flex">
                                        <h6>{{  $data->user_id != null ? $data->user_info['name'] : $data->name }} </h6>
                                        &nbsp;
                                        <span> ( {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('M d D, Y g: i a') }} ) </span>
                                        &nbsp;
                                        @include('frontend._partials._productRates', ['product' => $product])
                                    </div>
                                    <p>
                                        {{ $data->review }}
                                    </p>
                                    {{-- <ul class="comnt-sec">
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-thumbs-o-up" aria-hidden="true"></i><span>(14)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="unlike"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i>(2)</div>
                                            </a>
                                        </li>
                                    </ul> --}}
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- section end -->

<!-- product section start -->
<section class="section-b-space ratio_asos">
    <div class="container">
        <div class="row">
            <div class="col-12 product-related">
                <h2>@lang('global.related_products')</h2>
            </div>
        </div>
        <div class="row search-product">
            @foreach($product->relatedProducts($product) as $data)
                @if($data->id !== $product->id)
                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <div class="product-box">
                            <div class="img-wrapper">
                                @php
                                    $photo = explode(',', $data->photo);
                                @endphp
                                <div class="front">
                                    <a href="{{ route('backend.product-detail', $data->slug) }}">
                                        <img src="{{ $photo[0] }}" class="img-fluid blur-up lazyload bg-img" alt="{{ $data->title }}">
                                    </a>
                                </div>
                                <div class="back">
                                    <a href="{{ route('backend.product-detail', $data->slug) }}">
                                        <img src="{{ count($photo) > 1 ? $photo[1] : $photo[0] }}" class="img-fluid blur-up lazyload bg-img" alt="{{ $data->title }}">
                                    </a>
                                </div>
                                <div class="cart-info cart-wrap">
                                    <button data-toggle="modal" data-target="#modal{{ $data->id }}" title="@lang('global.add_to_cart')">
                                        <i class="ti-shopping-cart"></i>
                                    </button>
                                    <a href="{{ route('backend.add-to-wishlist',$data->slug) }}" title="{{ trans('global.add_to_wishlist') }}">
                                        <i class="fa {{ isProductWishlisted($data->id) ? 'fa-heart text-danger' : 'fa-heart-o' }}" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" data-toggle="modal" data-target="#modal{{ $data->id }}" title="@lang('global.quick_view')">
                                        <i class="ti-search" aria-hidden="true"></i>
                                    </a>
                                     <a href="{{ route('backend.add-product-compare', ['slug' => $data->slug]) }}" title="@lang('global.compare')">
                                        <i class="ti-reload" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="product-detail">
                                @include('frontend._partials._productRates', ['product' => $data])
                                <a href="{{ route('backend.product-detail', $data->slug) }}">
                                    <h6>{{ $data->title }}</h6>
                                </a>
                                @include('frontend._partials._discountWrapper', ['product' => $data, 'tag' => 'h5'])
                                @include('frontend._partials._attributesPreview', ['product' => $data])
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
<!-- product section end -->

<!-- Modal -->
@foreach ($product->relatedProducts($product) as $prod)
    @php
        $after_discount = 0;
    @endphp
    @if ($prod->discount > 0)
        @php
            $after_discount=($prod->price - ($prod->price * $prod->discount)/100);
        @endphp
    @endif
    @include('frontend._partials._productModal', ['product' => $prod])
@endforeach
<!-- Modal end -->

@endsection
@push('styles')
<style>
    /* Rating */
    .rating_box {
        display: inline-flex;
    }

    .star-rating {
        font-size: 0;
        padding-left: 10px;
        padding-right: 10px;
    }

    .star-rating__wrap {
        display: inline-block;
        font-size: 1rem;
    }

    .star-rating__wrap:after {
        content: "";
        display: table;
        clear: both;
    }

    .star-rating__ico {
        float: right;
        padding-left: 2px;
        cursor: pointer;
        color: #F7941D;
        font-size: 16px;
        margin-top: 5px;
    }

    .star-rating__ico:last-child {
        padding-left: 0;
    }

    .star-rating__input {
        display: none;
    }

    .star-rating__ico:hover:before,
    .star-rating__ico:hover~.star-rating__ico:before,
    .star-rating__input:checked~.star-rating__ico:before {
        content: "\F005";
    }
</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script type="text/javascript">
    // Set the date we're counting down to
    var countDownDate = new Date('{!! $product->discount_end !!}').getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        document.getElementById("day").innerHTML = days;
        document.getElementById("hour").innerHTML = hours;
        document.getElementById("min").innerHTML = minutes;
        document.getElementById("sec").innerHTML = seconds;

        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("timer").css('display', 'none');
        }
    }, 1000);
</script>

{{-- <script>
        $('.cart').click(function(){
            var quantity=$('#quantity').val();
            var pro_id=$(this).data('id');
            // alert(quantity);
            $.ajax({
                url:"{{route('backend.add-to-cart')}}",
type:"POST",
data:{
_token:"{{csrf_token()}}",
quantity:quantity,
pro_id:pro_id
},
success:function(response){
console.log(response);
if(typeof(response)!='object'){
response=$.parseJSON(response);
}
if(response.status){
swal('success',response.msg,'success').then(function(){
document.location.href=document.location.href;
});
}
else{
swal('error',response.msg,'error').then(function(){
document.location.href=document.location.href;
});
}
}
})
});
</script> --}}

@endpush
