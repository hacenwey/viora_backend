@extends('frontend.layouts.master')
@section('title', settings()->get('app_name').' | '.trans('global.cart'))
@section('main-content')
{{-- {{ dd(getAllProductFromCart()) }} --}}
<!-- breadcrumb start -->
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="page-title">
                    <h2>{{ trans('global.cart') }}</h2>
                </div>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('backend.home') }}">{{ trans('global.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ trans('global.cart') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb End -->

<!--section start-->
<section class="cart-section section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <table class="table cart-table table-responsive-xs">
                    <thead>
                        <tr class="table-head">
                            <th scope="col">@lang('cruds.cart.fields.image')</th>
                            <th scope="col">@lang('cruds.cart.fields.product_name')</th>
                            <th scope="col">@lang('cruds.cart.fields.price')</th>
                            <th scope="col">@lang('cruds.cart.fields.quantity')</th>
                            <th scope="col">@lang('cruds.cart.fields.total')</th>
                            <th scope="col">@lang('global.action')</th>
                        </tr>
                    </thead>
                    <form action="{{ route('backend.cart.update') }}" method="POST">
                        @csrf
                        @if(getAllProductFromCart())
                            @foreach(getAllProductFromCart() as $key => $cart)
                                @if (Auth::guard()->check())
                                    <tbody>
                                        <tr>
                                            <td>
                                                @php
                                                    $photo = explode(',', $cart->product['photo']);
                                                @endphp
                                                <a href="{{route('backend.product-detail',$cart->product['slug'])}}"><img src="{{ $photo[0] }}" alt="{{ $cart->product['title'] }}"></a>
                                            </td>
                                            <td>
                                                <a href="{{ route('backend.product-detail',$cart['product']['slug']) }}">{{ $cart->product['title'] }}</a>
                                                <span>
                                                    @if(isset($cart['attributes']))
                                                        @foreach (json_decode($cart['attributes']) as $i => $attr)
                                                            @if ($attr != null)
                                                                <b>{{ $i }}:</b> {{ $attr }},
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </span>
                                                <div class="mobile-cart-content row">
                                                    <div class="col-xs-3">
                                                        <div class="qty-box">
                                                            <div class="input-group">
                                                                <input type="text" name="quantity[{{$key}}]" class="form-control input-number" value="{{ $cart->quantity }}">
                                                                {{-- <input type="hidden" name="qty_id[]" value="{{$cart->id}}"> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <h2 class="td-color">{{ getFormattedPrice($cart['price']) }}</h2>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <h2 class="td-color"><a href="{{ route('backend.cart-delete',$cart->id) }}" class="icon"><i class="ti-close"></i></a>
                                                        </h2>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h2>{{ getFormattedPrice($cart['price']) }}</h2>
                                            </td>
                                            <td>
                                                <div class="qty-box">
                                                    <div class="input-group">
                                                        <input type="number" name="quantity[{{$key}}]" class="form-control input-number" value="{{ $cart->quantity }}">
                                                        <input type="hidden" name="qty_id[]" value="{{$cart->id}}">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h2 class="td-color">{{ getFormattedPrice($cart['amount']) }}</h2>
                                            </td>
                                            <td><a href="{{ route('backend.cart-delete',$cart->id) }}" class="icon"><i class="ti-close"></i></a></td>
                                        </tr>
                                    </tbody>
                                @else
                                    <tbody>
                                        <tr>
                                            <td>
                                                @php
                                                    $photo = explode(',', $cart['product']['photo']);
                                                @endphp
                                                <a href="{{route('backend.product-detail',$cart['product']['slug'])}}"><img src="{{ $photo[0] }}" alt="{{ $cart['product']['title'] }}"></a>
                                            </td>
                                            <td>
                                                <a href="{{ route('backend.product-detail',$cart['product']['slug']) }}">{{ $cart['product']['title'] }}</a><br>
                                                <span>
                                                    @foreach (json_decode($cart['attributes']) as $i => $attr)
                                                    @if ($attr != null)
                                                            <b>{{ $i }}:</b> {{ $attr }},
                                                        @endif
                                                    @endforeach
                                                </span>
                                                <div class="mobile-cart-content row">
                                                    <div class="col-xs-3">
                                                        <div class="qty-box">
                                                            <div class="input-group">
                                                                <input type="text" name="quantity[{{$key}}]" class="form-control input-number" value="{{ $cart['quantity'] }}">
                                                                {{-- <input type="hidden" name="qty_id[]" value="{{$key}}"> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <h2 class="td-color">{{ getFormattedPrice($cart['price']) }}</h2>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <h2 class="td-color"><a href="{{ route('backend.cart-delete',$key) }}" class="icon"><i class="ti-close"></i></a>
                                                        </h2>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h2>{{ getFormattedPrice($cart['price']) }}</h2>
                                            </td>
                                            <td>
                                                <div class="qty-box">
                                                    <div class="input-group">
                                                        <input type="number" name="quantity[{{$key}}]" class="form-control input-number" value="{{ $cart['quantity'] }}">
                                                        <input type="hidden" name="qty_id[]" value="{{$key}}">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h2 class="td-color">{{ getFormattedPrice($cart['amount']) }}</h2>
                                            </td>
                                            <td><a href="{{ route('backend.cart-delete',$key) }}" class="icon"><i class="ti-close"></i></a></td>
                                        </tr>
                                    </tbody>
                                @endif
                            @endforeach
                        @else
                            <tbody>
                                <tr>
                                    <td class="text-center" colspan="6">
                                        @lang('cruds.cart.fields.no_item_found')
                                        <a href="{{route('backend.product-grids')}}" style="color:blue;">@lang('global.continue_shopping')</a>
                                    </td>
                                </tr>
                            </tbody>
                        @endif
                        <tfoot>
                            <tr>
                                <td colspan="6">
                                    <button class="btn btn-solid" type="submit">@lang('global.update')</button>
                                </td>
                            </tr>
                        </tfoot>
                    </form>
                </table>
                <table class="table cart-table table-responsive-md">
                    <tfoot>
                        <tr>
                            <td>{{ trans('global.subtotal') }}</td>
                            <td>{{ getFormattedPrice(totalCartPrice()) }}</td>
                        </tr>
                        @if(session()->has('coupon'))
                            <tr>
                                <td>{{ trans('global.discount') }}</td>
                                <td>
                                    {{ getFormattedPrice(Session::get('coupon')['value']) }}
                                </td>
                            </tr>
                        @endif

                        <!--  -->
                        @if(session()->has('points_fidelite'))
                            <tr>
                                <td>{{ trans('global.points') }}</td>
                                <td>
                                    {{ getFormattedPrice(Session::get('points_fidelite')['points_to_currency'])}}
                                </td>
                            </tr>
                        @endif

                        <!--  -->
                        <tr>
                            @php
                                $total_amount = totalCartPrice();
                                if(session()->has('coupon')){
                                    $total_amount = $total_amount - Session::get('coupon')['value'];
                                }
                                if(session()->has('points_fidelite')){
                                    $total_amount = $total_amount - Session::get('points_fidelite')['points_to_currency'];
                                }
                            @endphp
                            <td>{{ trans('global.final_price') }}</td>
                            <td>
                                <h4>{{ getFormattedPrice($total_amount) }}</h4>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-sm-12">
                <div style="width: fit-content">
                    <form action="{{route('backend.coupon-points-store')}}" method="POST">
                        @csrf
                        <!-- coupon -->
                        <input name="code" class="form-control" 
                            placeholder="@lang('global.enter') @lang('global.coupon')" 
                            value="{{ session()->has('coupon') ? Session::get('coupon')['code'] : '' }}">
                        @if(session()->has('coupon'))
                            <div class="pl-2">
                                <span style="font-size:12px">
                                    @lang('global.coupon_applied')
                                    <b>{{ Session::get('coupon')['code'] }}</b>
                                </span>
                            </div>
                        @endif

                        <!-- points fidelite -->
                        @if(!empty(getUserPoints()))
                            <div class="mt-3">
                                <input name="point_fidelite" 
                                    class="form-control " id="point-fidelite" 
                                    placeholder="@lang('global.enter') @lang('global.points')"
                                    value="{{ session()->has('points_fidelite') ? Session::get('points_fidelite')['points'] : '' }}">
                                @if(session()->has('points_fidelite'))
                                    <div class="pl-2">
                                        <span style="font-size:12px">
                                            @lang('global.points_applied') :
                                            <b>{{Session::get('points_fidelite')['points']}} / {{getUserPoints()}}</b>
                                            <b>({{Session::get('points_fidelite')['points_to_currency']}} MRU)</b>
                                        </span>
                                    </div>
                                @else
                                <div class="pl-2">
                                        <span style="font-size:12px">
                                            @lang('global.points_balance') :
                                            <b>{{getUserPoints()}}</b>
                                            <b>({{getUserPointsToCurrency()}} MRU)</b>
                                        </span>
                                    </div>
                                @endif
                            </div>    
                        @endif
                        
                        <button class="btn mt-3">@lang('global.apply')</button>
                    </form>

                </div>
            </div>
        </div>
        <div class="row cart-buttons">
            <div class="col-6"><a href="{{ route('backend.product-grids') }}" class="btn btn-solid">@lang('global.continue_shopping')</a></div>
            <div class="col-6"><a href="{{ route('backend.checkout') }}" class="btn btn-solid">@lang('global.checkout')</a></div>
        </div>
    </div>
</section>
<!--section end-->


@endsection
@push('styles')
<style>
    li.shipping {
        display: inline-flex;
        width: 100%;
        font-size: 14px;
    }

    li.shipping .input-group-icon {
        width: 100%;
        margin-left: 10px;
    }

    .input-group-icon .icon {
        position: absolute;
        left: 20px;
        top: 0;
        line-height: 40px;
        z-index: 3;
    }

    .form-select {
        height: 30px;
        width: 100%;
    }

    .form-select .nice-select {
        border: none;
        border-radius: 0px;
        height: 40px;
        background: #f6f6f6 !important;
        padding-left: 45px;
        padding-right: 40px;
        width: 100%;
    }

    .list li {
        margin-bottom: 0 !important;
    }

    .list li:hover {
        background: #F7941D !important;
        color: white !important;
    }

    .form-select .nice-select::after {
        top: 14px;
    }
</style>
@endpush
@push('scripts')
<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
            $("select.select2").select2();
        });
  		$('select.nice-select').niceSelect();
</script>
<script>
    $(document).ready(function(){
			$('.shipping select[name=shipping]').change(function(){
				let cost = parseFloat( $(this).find('option:selected').data('price') ) || 0;
				let subtotal = parseFloat( $('.order_subtotal').data('price') );
				let coupon = parseFloat( $('.coupon_price').data('price') ) || 0;
				// alert(coupon);
				$('#order_total_price span').text('$'+(subtotal + cost-coupon).toFixed(2));
			});

    });

    $("#point-fidelite").keyup(function(event){
        const pointsSolde ="{{ getUserPoints() }}";
        const val = parseInt(event.target.value);
        if (val < 0) event.target.value = 0;
        if (val > pointsSolde) event.target.value = pointsSolde;
    })

</script>

@endpush
