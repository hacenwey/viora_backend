@extends('frontend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.products'))

@section('main-content')

@include('frontend.layouts.loader', ['page' => 'collection'])


<!-- section start -->
<section class="section-b-space">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="collection-content col">
                    <div class="page-main-content">
                        <div class="top-banner-wrapper">
                            <a href="#">
                                <img src="{{ $collection->photo }}" class="img-fluid blur-up lazyload" alt="" style="min-width: 100%;height: 385px;object-fit: cover">
                            </a>
                            <div class="top-banner-content small-section pb-0">
                                <h4>{{ $collection->title }}</h4>
                                <h5>{{ $collection->summary }}</h5>
                                <p>{!! $collection->description !!}</p>
                            </div>
                        </div>
                        <div class="collection-product-wrapper">
                            <div class="section-t-space portfolio-section portfolio-padding metro-section port-col">
                                <div class="isotopeContainer row">
                                    @foreach($products as $product)
                                        <div class="col-xl-3 col-sm-6 isotopeSelector">
                                            <div class="product-box">
                                                <div class="img-wrapper">
                                                    @php
                                                        $photo = explode(',',$product->photo);
                                                    @endphp
                                                    <div class="front">
                                                        <a href="{{ route('backend.product-detail', ['slug' => $product->slug]) }}">
                                                            <img src="{{ $photo[0] }}" class="img-fluid blur-up lazyload" alt="{{ $product->title }}">
                                                        </a>
                                                    </div>
                                                    <div class="cart-info cart-wrap">
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
                                                    </div>
                                                </div>
                                                <div class="product-detail">
                                                    <a href="{{ route('backend.product-detail', ['slug' => $product->slug]) }}">
                                                        <h6>{{ $product->title }}</h6>
                                                    </a>
                                                    @include('frontend._partials._discountWrapper', ['product' => $product, 'tag' => 'h5'])
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="product-pagination mt-0">
                                <div class="theme-paggination-block">
                                    <div class="row">
                                        {{ $products->appends($_GET)->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- section End -->

    <!-- Modal -->
    @if(count($products) > 0)
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
    <!-- Modal end -->

@endsection
@push('styles')
<style>
    .pagination{
        display:inline-flex;
    }
    .filter_button{
        /* height:20px; */
        text-align: center;
        background:#F7941D;
        padding:8px 16px;
        margin-top:10px;
        color: white;
    }
</style>
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

@endpush
