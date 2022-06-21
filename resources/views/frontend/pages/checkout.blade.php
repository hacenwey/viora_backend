@extends('frontend.layouts.master')

@section('title', settings('app_name').' | '.trans('global.checkout'))

@section('main-content')

    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h2>{{ trans('global.checkout') }}</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('backend.home') }}">{{ trans('global.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('global.checkout') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!-- section start -->
    <section class="section-b-space">
        <div class="container">
            <div class="checkout-page">
                <div class="checkout-form">
                    <form action="{{ route('backend.placeOrder') }}" method="POST">
                        @csrf

                        {{-- <input type="hidden" value="{{ App\Models\Tenant\MasrviPayment::getSessionId() }}" name="sessionid"> --}}
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 col-xs-12">
                                <div class="checkout-title">
                                    <h3>@lang('global.billing.details')</h3>
                                </div>
                                @php
                                    $fname = '';
                                    $lname = '';
                                @endphp

                                @if (Auth::guard()->check())
                                    @php
                                        $fname = Auth::guard()->user()->first_name;
                                        $lname = Auth::guard()->user()->last_name;
                                    @endphp
                                @endif
                                <div class="row check-out">
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <div class="field-label text-start">@lang('global.first_name')</div>
                                        <input type="text" name="first_name" value="{{ old('first_name', $fname) }}" placeholder="@lang('global.first_name')" required>
                                        @error('first_name')
                                            <span class='text-danger'>{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <div class="field-label text-start">@lang('global.last_name')</div>
                                        <input type="text" name="last_name" value="{{ old('last_name', $lname) }}" placeholder="@lang('global.last_name')">
                                        @error('last_name')
                                            <span class='text-danger'>{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <div class="field-label text-start">@lang('global.phone')</div>
                                        <input type="tel" id="phone" class="form-control" name="phone" value="{{ old('phone', Auth::guard()->check() ? Auth::guard()->user()->phone_number : '') }}" placeholder="@lang('global.phone')" required>
                                        @error('phone')
                                            <span class='text-danger'>{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <div class="field-label text-start">@lang('global.email')</div>
                                        <input type="text" name="email" value="{{ old('email', Auth::guard()->check() ? Auth::guard()->user()->email : '') }}" placeholder="@lang('global.email')">
                                        @error('email')
                                            <span class='text-danger'>{{$message}}</span>
                                        @enderror
                                    </div>
                                    {{-- <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label">@lang('global.country')</div>
                                        <select name="country" id="country">
                                            <option value="MR" selected>@lang('global.mauritania')</option>
                                        </select>
                                    </div> --}}
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label text-start">@lang('global.city')</div>
                                        <select name="town_city" id="town_city">
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->slug }}">{{ $city->name }}</option>
                                            @endforeach
                                            {{-- <option value="Nouadhibou">@lang('global.nouadhibou')</option>
                                            <option value="Rosso">@lang('global.rosso')</option>
                                            <option value="Adel Bagrou">@lang('global.adel_begrou')</option>
                                            <option value="Boghé">@lang('global.boghe')</option>
                                            <option value="Kiffa">@lang('global.kiffa')</option>
                                            <option value="Zouerate">@lang('global.zouerate')</option>
                                            <option value="Kaédi">@lang('global.kaedi')</option>
                                            <option value="Boû Gâdoûm">@lang('global.bou_gadoum')</option>
                                            <option value="Boutilimit">@lang('global.boutilimit')</option>
                                            <option value="Atar">@lang('global.atar')</option>
                                            <option value="Bareina">@lang('global.bareina')</option>
                                            <option value="Gouraye">@lang('global.gouraye')</option>
                                            <option value="Mâl">@lang('global.mal')</option>
                                            <option value="Dakhla et Aioun">@lang('global.dakhla_aioun')</option> --}}
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <div class="field-label text-start">@lang('global.address')</div>
                                        <input type="text" name="address1" value="{{ old('address1') }}" placeholder="@lang('global.street_address')" required>
                                        @error('address1')
                                            <span class='text-danger'>{{$message}}</span>
                                        @enderror
                                    </div>
                                    {{-- @if (!Auth::guard()->check())
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="checkbox" name="create_account" id="account-option"> &ensp;
                                            <label for="account-option">@lang('global.create_account')?</label>
                                        </div>
                                    @endif --}}
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 col-xs-12">
                                <div class="checkout-details">
                                    <div class="order-box">
                                        <div class="title-box">
                                            <div>@lang('global.product') <span>@lang('global.order')</span></div>
                                        </div>
                                        <ul class="qty">
                                            @foreach (getAllProductFromCart() as $cart)
                                                @if (Auth::guard()->check())
                                                    <li>{{ $cart->product['title'] }} x {{ $cart->quantity }} <span class="d-ltr">{{ getFormattedPrice($cart['amount']) }}</span></li>
                                                @else
                                                    <li>{{ $cart['product']['title'] }} x {{ $cart['quantity'] }} <span class="d-ltr">{{ getFormattedPrice($cart['amount']) }}</span></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        <ul class="sub-total">
                                            <li class="order_subtotal" data-price="{{totalCartPrice()}}">@lang('global.subtotal') <span class="count d-ltr">{{ getFormattedPrice(totalCartPrice()) }}</span></li>
                                            @if(session()->has('coupon'))
                                                <li class="coupon_price" data-price="{{session('coupon')['value']}}">@lang('global.coupon_applied') <span class="count d-ltr">{{ getFormattedPrice(Session::get('coupon')['value']) }}</span></li>
                                            @endif
                                            <li>
                                                @lang('global.shipping')
                                                <div class="shipping">
                                                    @if(count(shipping()) > 0 && cartCount() > 0)
                                                        <div class="shopping-option">
                                                            <select name="shipping" class="shipping-method">
                                                                <option value="0" data-price="0">@lang('global.local_pickup')</option>
                                                                @foreach(shipping() as $shipping)
                                                                    <option value="{{ $shipping->id }}" data-price="{{ $shipping->price }}" data-urgent-price="{{ $shipping->urgent_price }}" data-free-price="{{ $shipping->free_price }}" id="{{ $shipping->id }}">
                                                                        {{$shipping->type}}: {{ getFormattedPrice($shipping->price)}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <input type="checkbox" name="urgent" id="urgent" disabled>
                                                            <label for="urgent">@lang('global.urgent')</label>
                                                        </div>
                                                    @else
                                                        <div class="shopping-option">
                                                            <input type="radio" name="free-shipping" id="free-shipping" checked>
                                                            <label for="free-shipping">@lang('global.free_shipping')</label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                        </ul>
                                        <ul class="total">
                                            @php
                                                $total_amount = totalCartPrice();
                                                if(session()->has('coupon')){
                                                    $total_amount = $total_amount - Session::get('coupon')['value'];
                                                }
                                            @endphp
                                            <li id="order_total_price">@lang('global.total_price') <span class="count d-ltr">{{ getFormattedPrice($total_amount) }}</span></li>
                                        </ul>
                                    </div>
                                    <div class="payment-box">
                                        <div class="upper-box">
                                            <div class="payment-options">
                                                <ul>
                                                    <li>
                                                        <div class="radio-option">
                                                            <input type="radio" class="payment-check" name="payment_method" id="payment_cod" checked value="0">
                                                            <label for="payment_cod">@lang('global.cash_on_delivery')</label>
                                                        </div>
                                                    </li>
                                                    @foreach ($payments as $payment)
                                                        <li>
                                                            <div class="radio-option">
                                                                <input type="radio" class="payment-check" name="payment_method" id="payment{{ $payment->id }}" value="{{$payment->id}}">
                                                                <label for="payment{{ $payment->id  }}">{{ $payment->name }}</label>
                                                                <div class="descriptionWrapper mt-3" id="descriptionWrapper_{{$payment->id}}" style="display: none">
                                                                    @if($payment->has_api == 1 && $payment->api_key != null)
                                                                        <span>@lang('cruds.payment.fields.you_redirected_to_payment', ['name' => $payment->name])</span>
                                                                    @else
                                                                        <span class="small-text">{!! $payment->description !!}</span>
                                                                    @endif
                                                                    <img src="{{ $payment->image }}" alt="" width="100%">
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="d-flex space-between">
                                            <div class="text-left"><a href="{{ route('backend.cart') }}" class="btn-outline btn">@lang('global.return_to_cart')</a></div>
                                            <div class="text-right">
                                                <button type="submit" class="btn-solid btn">@lang('global.place_order')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- section end -->


@endsection
@push('styles')
	<style>
        .space-between{
            justify-content: space-between
        }
		li.shipping{
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
		.list li{
			margin-bottom:0 !important;
		}
		.list li:hover{
			background:#F7941D !important;
			color:white !important;
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

            $('[type="tel"]').keyup(phoneMask);

        });
        function phoneMask() {
            var num = $(this).val().replace(/\D/g,'');
            $(this).val(num.substring(0,2) + ' ' + num.substring(2,4) + ' ' + num.substring(4,6) + ' ' + num.substring(6,8));
        }
        $('select.nice-select').niceSelect();
	</script>
	<script>
		function showMe(box){
			var checkbox = document.getElementById('shipping').style.display;
			// alert(checkbox);
			var vis= 'none';
			if(checkbox=="none"){
				vis='block';
			}
			if(checkbox=="block"){
				vis="none";
			}
			document.getElementById(box).style.display=vis;
		}
	</script>
	<script>
		$(document).ready(function(){
			$('.shipping-method').change(function(){
			    if($(this).val() !== "0"){
                    $('#urgent').attr('disabled', false);
                }else{
                    $('#urgent').attr('disabled', 'disabled');
                }
			    $('#urgent').prop('checked',false);
				let freePrice = parseFloat( $(this).find(':selected').data('free-price') ) || 0;
				let cost = parseFloat( $(this).find(':selected').data('price') ) || 0;
				let subtotal = parseFloat( $('.order_subtotal').data('price') );
			    if(freePrice > 0 && subtotal >= freePrice){
			        cost = 0;
                }
				let coupon = parseFloat( $('.coupon_price').data('price') ) || 0;
				let total = subtotal + cost - coupon;
                getFormattedPrice(total);
            });

            $('#urgent').change(function(){
                let price = 0;
                let subtotal = parseFloat( $('.order_subtotal').data('price') );
				if($(this).is(':checked')){
                    price = parseFloat( $('.shipping-method').find(':selected').data('urgent-price') ) || 0;
                }else{
                    let freePrice = parseFloat( $('.shipping-method').find(':selected').data('free-price') ) || 0;
                    if(freePrice > 0 && subtotal >= freePrice){
                        price = 0;
                    }else{
                        price = parseFloat( $('.shipping-method').find(':selected').data('price') ) || 0;

                    }
                }

				let coupon = parseFloat( $('.coupon_price').data('price') ) || 0;
				let total = subtotal + price - coupon;
                getFormattedPrice(total);
            });

            function getFormattedPrice(price){
                $.ajax({
                    type: 'GET',
                    url: '{!! route('backend.get-formatted-price') !!}',
                    data: {
                        price: price,
                        },
                    success: function (response) {
                        $('#order_total_price span').text(response.formatted_price);
                    }
                });
            }

            $('.payment-check').change(function() {
                var val = $(this).val();
                $('.descriptionWrapper').hide()
                $('#descriptionWrapper_'+val).show();
            })
		});

	</script>

@endpush
