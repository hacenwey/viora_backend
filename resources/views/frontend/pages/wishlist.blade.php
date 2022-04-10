@extends('frontend.layouts.master')
@section('title','Wishlist Page')
@section('main-content')
	<!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h2>{{ trans('global.wishlist') }}</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('backend.home') }}">{{ trans('global.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('global.wishlist') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--section start-->
    <section class="wishlist-section section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table cart-table table-responsive-xs">
                        <thead>
                            <tr class="table-head">
                                <th scope="col">@lang('global.image')</th>
                                <th scope="col">@lang('global.product_name')</th>
                                <th scope="col">@lang('global.price')</th>
                                <th scope="col">@lang('global.availability')</th>
                                <th scope="col">@lang('global.action')</th>
                            </tr>
                        </thead>
                        @if(count(getAllProductFromWishlist()) > 0)
                            @foreach(getAllProductFromWishlist() as $key => $wishlist)
                                <tbody>
                                    <tr>
                                        @php
											$photo = explode(',',$wishlist->product['photo']);
										@endphp
                                        <td>
                                            <a href="{{route('backend.product-detail',$wishlist->product['slug'])}}"><img src="{{ $photo[0] }}" alt="{{ $wishlist->product['title'] }}"></a>
                                        </td>
                                        <td><a href="{{route('backend.product-detail',$wishlist->product['slug'])}}">{{ $wishlist->product['title'] }}</a>
                                            <div class="mobile-cart-content row">
                                                <div class="col-xs-3">
                                                    @if ($wishlist->product['stock'] > 0)
                                                        <p>@lang('global.in_stock')</p>
                                                    @else
                                                        <p>@lang('global.out_of_stock')</p>
                                                    @endif
                                                </div>
                                                <div class="col-xs-3">
                                                    <h2 class="td-color">{{ getFormattedPrice($wishlist->product['price']) }}</h2>
                                                </div>
                                                <div class="col-xs-3">
                                                    <h2 class="td-color">
                                                        <a href="{{route('backend.wishlist-delete',$wishlist->id)}}" class="icon mr-1"><i class="ti-close"></i></a>
                                                        <a href="{{route('backend.add-to-cart',$wishlist->product['slug'])}}" class="cart"><i class="ti-shopping-cart"></i></a></h2>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h2>{{ getFormattedPrice($wishlist->product['price']) }}</h2>
                                        </td>
                                        <td>
                                            @if ($wishlist->product['stock'] > 0)
                                                <p>@lang('global.in_stock')</p>
                                            @else
                                                <p>@lang('global.out_of_stock')</p>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{route('backend.wishlist-delete',$wishlist->id)}}" class="icon mr-3"><i class="ti-close"></i> </a>
                                            @if ($wishlist->product['stock'] > 0)
                                                @if (count($wishlist->product['attributes']) > 0)
                                                    <a href="{{ route('backend.product-detail', ['slug' => $wishlist->product['slug']]) }}" class="cart"><i class="ti-shopping-cart"></i></a>
                                                @else
                                                    <a href="{{route('backend.add-to-cart',$wishlist->product['slug'])}}" class="cart"><i class="ti-shopping-cart"></i></a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @else
                            <tbody>
                                <tr>
                                    <td colspan="5">@lang('global.wishlist_empty')</td>
                                </tr>
                            </tbody>
                        @endif
                    </table>
                </div>
            </div>
            <div class="row wishlist-buttons">
                <div class="col-12">
                    <a href="{{ route('backend.product-grids') }}" class="btn btn-solid">@lang('global.continue_shopping')</a>
                    @if(count(getAllProductFromWishlist()) > 0)
                        <a href="{{ route('backend.checkout') }}" class="btn btn-solid">@lang('global.checkout')</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!--section end-->

@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
@endpush
