@extends('frontend.layouts.master')
@section('title', settings()->get('app_name'))
@section('main-content')

{{-- @include('frontend.layouts.loader', ['page' => 'index']) --}}


@if(count($banners)>0)
    <!-- Home slider -->
    <section class="p-0 layout-7">
        <div class="slide-1 home-slider">
            @foreach($banners as $key=>$banner)
                <div>
                    <div class="home text-left">
                        <img src="{{ $banner->photo }}" alt="{{ $banner->title }}" class="bg-img blur-up lazyload">
                        <div class="container-fluid custom-container">
                            <div class="row">
                                <div class="col">
                                    <div class="slider-contain">
                                        <div>
                                            <h4>{{ $banner->title }}</h4>
                                            <p>{!! html_entity_decode($banner->description) !!}</p>
                                            <a href="{{ $banner->link != null ? $banner->link : route('backend.product-grids') }}" class="btn btn-solid">@lang('global.shop_now')</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <!-- Home slider end -->
@endif

<!-- collection banner -->
<section class="banner-padding banner-furniture ratio2_1">
    <div class="container-fluid">
        <div class="row partition4">
            @if($categories)
                @foreach($categories->where('is_parent', 1)->take(5) as $cat)
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('backend.product-cat',$cat->slug) }}">
                            <div class="collection-banner p-right text-right">
                                <div class="img-part">
                                    @if($cat->photo)
                                        <img src="{{$cat->photo}}" class="img-fluid blur-up lazyload bg-img" alt="{{ $cat->title }}">
                                    @else
                                        <img src="{{ asset('assets/images/kids/2.jpg') }}" class="img-fluid blur-up lazyload bg-img" alt="{{ $cat->title }}">
                                    @endif
                                </div>
                                <div class="contain-banner banner-4">
                                    <div>
                                        <h4>{{ trans('global.discover_now') }}</h4>
                                        <h2>{{ $cat->title }}</h2>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <!-- /End Single Banner  -->
                @endforeach
            @endif
        </div>
    </div>
</section>
<!-- collection banner end -->

@if(count($featured) > 0)
    <!-- Paragraph-->
        <div class="title1 section-t-space">
{{--            <h4>{{ trans('global.special_offer') }}</h4>--}}
            <a href="{{ route('backend.section.products', ['slug' => 'top_collection']) }}">
                <h2 class="title-inner1">{{ trans('global.top_collection') }}</h2>
            </a>
        </div>
        {{-- <div class="container">
            <div class="row">
                <div class="col-xl-6 offset-xl-3">
                    <div class="product-para">
                        <p class="text-center">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</p>
                    </div>
                </div>
            </div>
        </div> --}}
    <!-- Paragraph end -->

    <!-- Product slider -->
    <section class="pt-0 section-b-space ratio_asos">
        <div class="container">
            <div class="row game-product product-4 grid-products">
                @foreach($featured as $prod)
                    <div class="product-box">
                        <div class="img-wrapper">
                            @php
                                $photo = explode(',', $prod->photo);
                            @endphp
                            <div class="lable-block">
                                @if ($prod->isNew())
                                    <span class="lable3">{{ trans('global.new') }}</span>
                                @endif
                                @if ($prod->stock == 0)
                                    <span class="lable4">{{ trans('global.out_of_stock') }}</span>
                                @else
                                    <span class="lable4">{{ trans('global.on_sale') }}</span>
                                @endif
                            </div>
                            <div class="front">
                                <a href="{{ route('backend.product-detail', ['slug' => $prod->slug]) }}">
                                    <img src="{{ $photo[0] }}" class="img-fluid blur-up lazyload bg-img" alt="{{ $prod->title }}">
                                </a>
                            </div>
                            <div class="back">
                                <a href="{{ route('backend.product-detail', ['slug' => $prod->slug]) }}">
                                    <img src="{{ count($photo) > 1 ? $photo[1] : $photo[0] }}" class="img-fluid blur-up lazyload bg-img" alt="{{ $prod->title }}">
                                </a>
                            </div>
                            <div class="cart-info cart-wrap">
                                {{-- <button data-toggle="modal" data-target="#addtocart" title="{{ trans('global.add_to_cart') }}">
                                    <i class="ti-shopping-cart"></i>
                                </button> --}}
                                <a href="{{ route('backend.add-to-wishlist',$prod->slug) }}" title="{{ trans('global.add_to_wishlist') }}">
                                    <i class="ti-heart" aria-hidden="true"></i>
                                </a>
                                <a href="#" data-toggle="modal" data-target="#modal{{ $prod->id }}" title="{{ trans('global.quick_view') }}">
                                    <i class="ti-search" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('backend.add-product-compare', ['slug' => $prod->slug]) }}" title="@lang('global.compare')">
                                    <i class="ti-reload" aria-hidden="true"></i>
                                </a>
                            </div>
                            @if (count($prod->attributes) > 0)
                                <div class="add-button" data-toggle="modal" data-target="#modal{{ $prod->id }}" style="{{ $prod->stock == 0 ? 'pointer-events:none' : '' }}">
                                    {{ trans('global.add_to_cart') }}
                                </div>
                            @else
                                <form action="{{ route('backend.single-add-to-cart', ['slug' => $prod->slug]) }}" method="POST">
                                    @csrf
                                    <div class="product-description border-product">
                                        <div class="qty-box">
                                            <div class="input-group">
                                                <input type="hidden" name="productId" value="{{ $prod->id }}">
                                                <input type="hidden" name="quantity" class="form-control input-number qty-input" value="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-buttons">
                                        <button type="submit" {{ $prod->stock == 0 ? 'disabled' : '' }} class="btn add-button">{{ trans('global.add_to_cart') }}</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                        <div class="product-detail">
                            @include('frontend._partials._productRates', ['product' => $prod])
                            <a href="{{ route('backend.product-detail', ['slug' => $prod->slug]) }}">
                                <h6>{{ $prod->title }}</h6>
                            </a>
                            @include('frontend._partials._discountWrapper', ['product' => $prod, 'tag' => 'h4'])
                            @include('frontend._partials._attributesPreview', ['product' => $prod])
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Product slider end -->

@endif

@if(count($populars) > 0)
    <!-- product slider start -->
    <section class="section-b-space tools-grey ratio_square">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="title3">
                        <a href="{{ route('backend.section.products', ['slug' => 'popular_products']) }}">
                            <h2 class="title-inner3">@lang('global.popular_products')</h2>
                        </a>
                        <div class="line"></div>
                    </div>
                    <div class="product_4 product-m no-arrow">
                        @foreach(collect($populars) as $product)
                            <div class="product-box product-wrap">
                                <div class="img-wrapper">
                                    @php
                                        $photo = explode(',',$product->photo);
                                    @endphp
                                    @if($product->isNew())
                                        <div class="ribbon"><span>@lang('global.new')</span></div>
                                    @endif
                                    <div class="front">
                                        <a href="{{ route('backend.product-detail', ['slug' => $product->slug]) }}">
                                            <img alt="{{ $product->title }}" src="{{ $photo[0] }}" class="img-fluid blur-up lazyload bg-img"></a>
                                    </div>
                                    <div class="cart-info cart-wrap">
                                        <a href="{{ route('backend.add-to-wishlist',$product->slug) }}" title="{{ trans('global.add_to_wishlist') }}">
                                            <i class="fa fa-heart" aria-hidden="true"></i>
                                        </a>
                                        @if (count($product->attributes) > 0)
                                            <button title="{{ trans('global.add_to_cart') }}" data-toggle="modal" data-target="#modal{{ $product->id }}">
                                                <i class="ti-shopping-cart"></i>
                                                {{ trans('global.add_to_cart') }}
                                            </button>
                                        @else
                                            <form action="{{ route('backend.single-add-to-cart', ['slug' => $product->slug]) }}" method="POST">
                                                @csrf
                                                <div class="product-description border-product">
                                                    <div class="qty-box">
                                                        <div class="input-group">
                                                            <input type="hidden" name="productId" value="{{ $product->id }}">
                                                            <input type="hidden" name="quantity" class="form-control input-number qty-input" value="1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button title="{{ trans('global.add_to_cart') }}" type="submit" style="width: 137.5px;height: 100%">
                                                    <i class="ti-shopping-cart"></i>
                                                    {{ trans('global.add_to_cart') }}
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('backend.add-product-compare', ['slug' => $product->slug]) }}" title="@lang('global.compare')">
                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                        </a>
                                        <a class="mobile-quick-view" href="#" data-toggle="modal" data-target="#modal{{ $product->id }}" title="{{ trans('global.quick_view') }}">
                                            <i class="ti-search" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                    <div class="quick-view-part">
                                        <a href="#" data-toggle="modal" data-target="#modal{{ $product->id }}" title="{{ trans('global.quick_view') }}">
                                            <i class="ti-search" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    @include('frontend._partials._productRates', ['product' => $product])
                                    <a href="{{ route('backend.product-detail', ['slug' => $product->slug]) }}">
                                        <h6>{{ $product->title }}</h6>
                                    </a>
                                    @include('frontend._partials._discountWrapper', ['product' => $product, 'tag' => 'h5'])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- product slider end -->
@endif

<!-- Parallax banner -->
<section class="p-0">
    <div class="full-banner parallax text-center p-right">
        <img src="{{ settings()->get('middle_background') }}" alt="" class="bg-img blur-up lazyload">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="banner-contain">
                        {!! settings()->get('middle_section_content') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Parallax banner end -->

<!-- product slider -->
<section class="tools_product bg-title section-b-space">
    <div class="container">
        <div class="row multiple-slider">
            {{-- <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="theme-card">
                    <h5 class="title-border">{{ trans('global.under') }} {{ getFormattedPrice(settings('under_value_section')) }}</h5>
                    <div class="offer-slider slide-1">
                        @foreach ($products->where('price', '<', settings('under_value_section'))->chunk(3) as $groups)
                            <div>
                                @foreach ($groups as $key => $nprod)
                                    @php
                                        $photo = explode(',',$nprod->photo);
                                    @endphp
                                    <div class="media">
                                        <a href="{{ route('backend.product-detail', ['slug' => $nprod->slug]) }}">
                                            <img class="img-fluid blur-up lazyload" src="{{ $photo[0] }}" alt="{{ $nprod->title }}">
                                        </a>
                                        <div class="media-body align-self-center">
                                            @include('frontend._partials._productRates', ['product' => $nprod])
                                            <a href="{{ route('backend.product-detail', ['slug' => $nprod->slug]) }}">
                                                <h6>{{ $nprod->title }}</h6>
                                            </a>
                                            @include('frontend._partials._discountWrapper', ['product' => $nprod, 'tag' => 'h5'])
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div> --}}
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="theme-tab">
                    <div class="bg-title-part">
                        <h5 class="title-border">{{ trans('global.recommended_for_you') }}</h5>
                        <ul class="tabs tab-title">
                            @if ($newproducts->count() > 0)
                                <li class="current">
                                    <a href="tab-4">{{ trans('global.new_products') }}</a>
                                </li>
                            @endif
                            <li class="{{ $newproducts->count() > 0 ? '' : 'current' }}">
                                <a href="tab-5">{{ trans('global.featured_products') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content-cls ratio_asos">
                        <div id="tab-4" class="tab-content active default">
                            <div class="product-4 game-product product-m no-arrow">
                                @foreach ($newproducts as $key => $nprod)
                                    <div class="product-box">
                                        @php
                                            $photo = explode(',',$nprod->photo);
                                        @endphp
                                        <div class="img-wrapper">
                                            <div class="lable-block">
                                                @if ($nprod->isNew())
                                                    <span class="lable3">{{ trans('global.new') }}</span>
                                                @endif
                                                @if ($nprod->stock == 0)
                                                    <span class="lable4">{{ trans('global.out_of_stock') }}</span>
                                                @else
                                                    <span class="lable4">{{ trans('global.on_sale') }}</span>
                                                @endif
                                            </div>
                                            <div class="front">
                                                <a href="{{ route('backend.product-detail', ['slug' => $nprod->slug]) }}"><img
                                                        src="{{ $photo[0] }}"
                                                        class="img-fluid blur-up lazyload bg-img" alt="{{ $nprod->title }}"></a>
                                            </div>
                                            <div class="cart-info cart-wrap">
                                                <a href="{{ route('backend.add-to-wishlist',$nprod->slug) }}" title="{{ trans('global.add_to_wishlist') }}" tabindex="0"><i
                                                        class="ti-heart" aria-hidden="true"></i></a>
                                                <a href="#" data-toggle="modal" data-target="#modal{{ $nprod->id }}"
                                                    title="{{ trans('global.quick_view') }}" tabindex="0"><i class="ti-search"
                                                        aria-hidden="true"></i></a>
                                                 <a href="{{ route('backend.add-product-compare', ['slug' => $nprod->slug]) }}" title="@lang('global.compare')" tabindex="0">
                                                     <i class="ti-reload" aria-hidden="true"></i>
                                                 </a>
                                            </div>
                                            @if (count($nprod->attributes) > 0)
                                                <div class="add-button" data-toggle="modal" data-target="#modal{{ $nprod->id }}" style="{{ $nprod->stock == 0 ? 'pointer-events:none' : '' }}">
                                                    {{ trans('global.add_to_cart') }}
                                                </div>
                                            @else
                                                <form action="{{ route('backend.single-add-to-cart', ['slug' => $nprod->slug]) }}" method="POST">
                                                    @csrf
                                                    <div class="product-description border-product">
                                                        <div class="qty-box">
                                                            <div class="input-group">
                                                                <input type="hidden" name="productId" value="{{ $nprod->id }}">
                                                                <input type="hidden" name="quantity" class="form-control input-number qty-input" value="1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-buttons">
                                                        <button type="submit" {{ $nprod->stock == 0 ? 'disabled' : '' }} class="btn add-button">{{ trans('global.add_to_cart') }}</button>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="product-detail">
                                            @include('frontend._partials._productRates', ['product' => $nprod])
                                            <a href="{{ route('backend.product-detail', ['slug' => $nprod->slug]) }}">
                                                <h6>{{ $nprod->title }}</h6>
                                            </a>
                                            @include('frontend._partials._discountWrapper', ['product' => $nprod, 'tag' => 'h5'])
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div id="tab-5" class="tab-content {{ $newproducts->count() > 0 ? '' : 'active default' }}">
                            <div class="product-4 game-product product-m no-arrow">
                                @foreach ($featured as $key => $nprod)
                                    <div class="product-box">
                                        @php
                                            $photo = explode(',',$nprod->photo);
                                        @endphp
                                        <div class="img-wrapper">
                                            <div class="lable-block">
                                                @if ($nprod->isNew())
                                                    <span class="lable3">{{ trans('global.new') }}</span>
                                                @endif
                                                @if ($nprod->stock == 0)
                                                    <span class="lable4">{{ trans('global.out_of_stock') }}</span>
                                                @else
                                                    <span class="lable4">{{ trans('global.on_sale') }}</span>
                                                @endif
                                            </div>
                                            <div class="front">
                                                <a href="{{ route('backend.product-detail', ['slug' => $nprod->slug]) }}"><img
                                                        src="{{ $photo[0] }}"
                                                        class="img-fluid blur-up lazyload bg-img" alt="{{ $nprod->title }}"></a>
                                            </div>
                                            <div class="cart-info cart-wrap">
                                                <a href="javascript:void(0)" title="{{ trans('global.add_to_wishlist') }}" tabindex="0"><i
                                                        class="ti-heart" aria-hidden="true"></i></a>
                                                <a href="#" data-toggle="modal" data-target="#modal{{ $nprod->id }}"
                                                    title="{{ trans('global.quick_view') }}" tabindex="0"><i class="ti-search"
                                                        aria-hidden="true"></i></a>
                                                 <a href="{{ route('backend.add-product-compare', ['slug' => $nprod->slug]) }}" title="@lang('global.compare')" tabindex="0"><i class="ti-reload"
                                                        aria-hidden="true"></i></a>
                                            </div>
                                            <div class="add-button" data-toggle="modal" data-target="#modal{{ $nprod->id }}" style="{{ $nprod->stock == 0 ? 'pointer-events:none' : '' }}">
                                                {{ trans('global.add_to_cart') }}
                                            </div>
                                        </div>
                                        <div class="product-detail">
                                            @include('frontend._partials._productRates', ['product' => $nprod])
                                            <a href="{{ route('backend.product-detail', ['slug' => $nprod->slug]) }}">
                                                <h6>{{ $nprod->title }}</h6>
                                            </a>
                                            @include('frontend._partials._discountWrapper', ['product' => $nprod, 'tag' => 'h5'])
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- product slider end -->

@if(count($return_in_stock) > 0)
    <!-- Product slider -->
    <section class="section-b-space j-box pets-box ratio_square">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="title1 title5">
                        <a href="{{ route('backend.section.products', ['slug' => 'return_in_stock']) }}">
                            <h2 class="title-inner1">@lang('global.return_in_stock')</h2>
                        </a>
                        <hr role="tournament6">
                    </div>
                    <div class="product-4 product-m no-arrow">
                        @foreach($return_in_stock as $product)
                            <div class="product-box">
                                <div class="img-wrapper">
                                    @php
                                        $photo = explode(',',$product->photo);
                                    @endphp
                                    <div class="front">
                                        <a href="{{ route('backend.product-detail', ['slug' => $product->slug]) }}">
                                            <img src="{{ $photo[0] }}" class="img-fluid blur-up lazyload bg-img" alt="">
                                        </a>
                                    </div>
                                    <div class="cart-info cart-wrap">
{{--                                        <button data-toggle="modal" data-target="#addtocart" title="Add to cart">--}}
{{--                                            <i class="ti-shopping-cart"></i>--}}
{{--                                        </button>--}}
                                        @if (count($product->attributes) > 0)
                                            <button title="{{ trans('global.add_to_cart') }}" data-toggle="modal" data-target="#modal{{ $product->id }}">
                                                <i class="ti-shopping-cart"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('backend.single-add-to-cart', ['slug' => $product->slug]) }}" method="POST">
                                                @csrf
                                                <div class="product-description border-product">
                                                    <div class="qty-box">
                                                        <div class="input-group">
                                                            <input type="hidden" name="productId" value="{{ $product->id }}">
                                                            <input type="hidden" name="quantity" class="form-control input-number qty-input" value="1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <button title="{{ trans('global.add_to_cart') }}" type="submit">
                                                    <i class="ti-shopping-cart"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('backend.add-to-wishlist',$product->slug) }}" title="{{ trans('global.add_to_wishlist') }}">
                                            <i class="ti-heart" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" data-toggle="modal" data-target="#modal{{ $product->id }}" title="{{ trans('global.quick_view') }}">
                                            <i class="ti-search" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{ route('backend.add-product-compare', ['slug' => $product->slug]) }}" title="@lang('global.compare')">
                                            <i class="ti-reload" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="product-detail">
                                    @include('frontend._partials._productRates', ['product' => $product])
                                    <a href="{{ route('backend.product-detail', ['slug' => $product->slug]) }}">
                                        <h6>{{ $product->title }}</h6>
                                    </a>
                                    @include('frontend._partials._discountWrapper', ['product' => $product, 'tag' => 'h6'])
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product slider end -->
@endif

@if(count($collections) > 0)

    <!-- absolute banner -->
    <section class="banner-furniture absolute_banner ratio3_2">
        <div class="container">
            <div class="col-md-12 text-center">
                <div class="title3">
                    <h2>@lang('global.discover_our_collection')</h2>
                    <div class="line"></div>
                </div>
            </div>
            <div class="row partition3">
                @foreach($collections as $collection)
                    <div class="col-md-4">
                        <a href="{{ route('backend.collection-details', ['slug' => $collection->slug]) }}">
                            <div class="collection-banner p-left text-left">
                                <img src="{{ $collection->photo }}" alt="" class="img-fluid blur-up lazyload bg-img">
                                <div class="absolute-contain">
                                    <h3>{{ $collection->title }}</h3>
                                    <h4>{{ $collection->summary }}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- absolute banner -->

@endif

@if (settings()->has('instagram_key'))
    <!-- instagram section -->
    <section class="instagram ratio_square">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 p-0">
                    <h2 class="title-borderless"># @lang('global.instagram')</h2>
                    <div class="slide-7 no-arrow slick-instagram">
                        <div>
                            <a href="#">
                                <div class="instagram-box"><img src="../assets/images/slider/kids/2.jpg" class="bg-img"
                                        alt="Avatar" style="width:100%">
                                    <div class="overlay"><i class="fa fa-instagram" aria-hidden="true"></i></div>
                                </div>
                            </a>
                        </div>
                        <div>
                            <a href="#">
                                <div class="instagram-box"><img src="../assets/images/slider/kids/3.jpg" class="bg-img"
                                        alt="Avatar" style="width:100%">
                                    <div class="overlay"><i class="fa fa-instagram" aria-hidden="true"></i></div>
                                </div>
                            </a>
                        </div>
                        <div>
                            <a href="#">
                                <div class="instagram-box"><img src="../assets/images/slider/kids/4.jpg" class="bg-img"
                                        alt="Avatar" style="width:100%">
                                    <div class="overlay"><i class="fa fa-instagram" aria-hidden="true"></i></div>
                                </div>
                            </a>
                        </div>
                        <div>
                            <a href="#">
                                <div class="instagram-box"><img src="../assets/images/slider/kids/9.jpg" class="bg-img"
                                        alt="Avatar" style="width:100%">
                                    <div class="overlay"><i class="fa fa-instagram" aria-hidden="true"></i></div>
                                </div>
                            </a>
                        </div>
                        <div>
                            <a href="#">
                                <div class="instagram-box"><img src="../assets/images/slider/kids/6.jpg" class="bg-img"
                                        alt="Avatar" style="width:100%">
                                    <div class="overlay"><i class="fa fa-instagram" aria-hidden="true"></i></div>
                                </div>
                            </a>
                        </div>
                        <div>
                            <a href="#">
                                <div class="instagram-box"><img src="../assets/images/slider/kids/7.jpg" class="bg-img"
                                        alt="Avatar" style="width:100%">
                                    <div class="overlay"><i class="fa fa-instagram" aria-hidden="true"></i></div>
                                </div>
                            </a>
                        </div>
                        <div>
                            <a href="#">
                                <div class="instagram-box"><img src="../assets/images/slider/kids/8.jpg" class="bg-img"
                                        alt="Avatar" style="width:100%">
                                    <div class="overlay"><i class="fa fa-instagram" aria-hidden="true"></i></div>
                                </div>
                            </a>
                        </div>
                        <div>
                            <a href="#">
                                <div class="instagram-box"><img src="../assets/images/slider/kids/9.jpg" class="bg-img"
                                        alt="Avatar" style="width:100%">
                                    <div class="overlay"><i class="fa fa-instagram" aria-hidden="true"></i></div>
                                </div>
                            </a>
                        </div>
                        <div>
                            <a href="#">
                                <div class="instagram-box"><img src="../assets/images/slider/kids/2.jpg" class="bg-img"
                                        alt="Avatar" style="width:100%">
                                    <div class="overlay"><i class="fa fa-instagram" aria-hidden="true"></i></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- instagram section end -->
@endif


<!--  logo section -->
<section class="section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="slide-6 no-arrow">
                    @foreach ($brands as $brand)
                        <div>
                            <div class="logo-block">
                                <a href="{{ route('backend.product-grids', ['brands' => $brand->slug]) }}"><img src="{{ $brand->logo }}" alt="{{ $brand->title }}" width="125" height="125"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!--  logo section end-->


@if($products)
    @foreach($products as $key => $product)
        @php
            $after_discount = 0;
        @endphp
        @if ($product->discount > 0)
            @php
                $after_discount=($product->price - ($product->price * $product->discount)/100);
            @endphp
        @endif
        @include('frontend._partials._productModal', ['product' => $product])
    @endforeach
@endif

@endsection

@push('styles')
{{-- <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5f2e5abf393162001291e431&product=inline-share-buttons' async='async'></script> --}}

<style>
    /* Banner Sliding */
    #Gslider .carousel-inner {
        background: #000000;
        color: black;
    }

    #Gslider .carousel-inner {
        height: 550px;
    }

    #Gslider .carousel-inner img {
        width: 100% !important;
        opacity: .8;
    }

    #Gslider .carousel-inner .carousel-caption {
        bottom: 60%;
    }

    #Gslider .carousel-inner .carousel-caption h1 {
        font-size: 50px;
        font-weight: bold;
        line-height: 100%;
        color: #F7941D;
    }

    #Gslider .carousel-inner .carousel-caption p {
        font-size: 18px;
        color: black;
        margin: 28px 0 28px 0;
    }

    #Gslider .carousel-indicators {
        bottom: 70px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>



{{-- <script>
        $('.cart').click(function(){
            var quantity=1;
            var pro_id=$(this).data('id');
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
// document.location.href=document.location.href;
});
}
else{
window.location.href='user/login'
}
}
})
});
</script> --}}
{{-- <script>
        $('.wishlist').click(function(){
            var quantity=1;
            var pro_id=$(this).data('id');
            // alert(pro_id);
            $.ajax({
                url:"{{route('backend.add-to-wishlist')}}",
type:"POST",
data:{
_token:"{{csrf_token()}}",
quantity:quantity,
pro_id:pro_id,
},
success:function(response){
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
// document.location.href=document.location.href;
});
}
}
});
});
</script> --}}
<script>
    /*==================================================================
        [ Isotope ]*/
        var $topeContainer = $('.isotope-grid');
        var $filter = $('.filter-tope-group');

        // filter items on button click
        $filter.each(function () {
            $filter.on('click', 'button', function () {
                var filterValue = $(this).attr('data-filter');
                $topeContainer.isotope({filter: filterValue});
            });

        });

        // init Isotope
        $(window).on('load', function () {
            var $grid = $topeContainer.each(function () {
                $(this).isotope({
                    itemSelector: '.isotope-item',
                    layoutMode: 'fitRows',
                    percentPosition: true,
                    animationEngine : 'best-available',
                    masonry: {
                        columnWidth: '.isotope-item'
                    }
                });
            });
        });

        var isotopeButton = $('.filter-tope-group button');

        $(isotopeButton).each(function(){
            $(this).on('click', function(){
                for(var i=0; i<isotopeButton.length; i++) {
                    $(isotopeButton[i]).removeClass('how-active1');
                }

                $(this).addClass('how-active1');
            });
        });
</script>
<script>
    function cancelFullScreen(el) {
            var requestMethod = el.cancelFullScreen||el.webkitCancelFullScreen||el.mozCancelFullScreen||el.exitFullscreen;
            if (requestMethod) { // cancel full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
        }

        function requestFullScreen(el) {
            // Supports most browsers and their versions.
            var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullscreen;

            if (requestMethod) { // Native full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
            return false
        }
</script>

@endpush
