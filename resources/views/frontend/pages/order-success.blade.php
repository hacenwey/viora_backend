@extends('frontend.layouts.master')

@section('title', settings()->get('app_name').' | '.trans('global.order_success'))

@section('main-content')

    <!-- thank-you section start -->
    <section class="section-b-space light-layout">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="success-text"><i class="fa fa-check-circle" aria-hidden="true"></i>
                        @if ($order->status == 'new')
                            <h2>@lang('global.thank_you')</h2>
                            <p>@lang('global.order_placed_success')</p>
                        @else
                            <h2>@lang('global.'.$order->status)</h2>
                        @endif
                        <p>@lang('global.order_id'): {{ $order->reference }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section ends -->

    @php
        use MattDaneshvar\Survey\Models\Survey;
        $survey = Survey::where('id', settings('after_order_survey'))->first();
    @endphp
    @if ($survey)
        @include('survey::standard', ['survey' => $survey])
    @endif

    <!-- order-detail section start -->
    <section class="section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="product-order">
                        <h3>@lang('global.order_details')</h3>
                        @foreach ($order->products as $item)
                            <div class="row product-order-detail">
                                @php
                                    $photo = explode(',', $item->product->photo);
                                @endphp
                                <div class="col-3">
                                    <img src="{{ $photo[0] }}" alt="" class="img-fluid blur-up lazyload">
                                </div>
                                <div class="col-3 order_detail">
                                    <div>
                                        <h4>@lang('global.product_name')</h4>
                                        <h5>{{ $item->product->title }}</h5>
                                    </div>
                                </div>
                                <div class="col-3 order_detail">
                                    <div>
                                        <h4>@lang('global.quantity')</h4>
                                        <h5>{{ $item->quantity }}</h5>
                                    </div>
                                </div>
                                <div class="col-3 order_detail">
                                    <div>
                                        <h4>@lang('global.price')</h4>
                                        <h5>{{ getFormattedPrice($item->sub_total) }}</h5>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="total-sec">
                            <ul>
                                <li>@lang('global.subtotal') <span>{{ getFormattedPrice($order->sub_total) }}</span></li>
                                <li>
                                    @lang('global.shipping')
                                    <span>
                                        @if($order->shipping_id != null)
                                            {{ $order->shipping->type }} |
                                            {{ getFormattedPrice($order->shipping->price) }}
                                        @else
                                            @lang('global.local_pickup')
                                        @endif
                                    </span>
                                </li>
                                {{-- <li>tax(GST) <span>$10.00</span></li> --}}
                            </ul>
                        </div>
                        <div class="final-total">
                            <h3>@lang('global.total') <span>{{ getFormattedPrice($order->total_amount) }}</span></h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row order-success-sec">
                        <div class="col-sm-6">
                            <h4>@lang('global.summary')</h4>
                            <ul class="order-detail">
                                <li>@lang('global.order_id'): {{ $order->reference }}</li>
                                <li>@lang('global.order_date'): {{ Carbon\Carbon::parse($order->created_at)->format('M d D, Y g: i a') }}</li>
                                <li>@lang('global.order_total'): {{ getFormattedPrice($order->total_amount) }}</li>
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <h4>@lang('global.shipping_address')</h4>
                            <ul class="order-detail">
                                <li>{{ $order->first_name }} {{ $order->last_name }}</li>
                                <li>{{ $order->address1 }}</li>
                                <li>{{ $order->country }}, {{ $order->town_city }}</li>
                                <li>@lang('global.contact_no'): {{ $order->phone }}</li>
                            </ul>
                        </div>
                        <div class="col-sm-12 payment-mode">
                            <h4>@lang('global.payment_method')</h4>
                            <p>{{ $order->payment_method }}</p>
                        </div>
                        <div class="col-md-12">
                            <div class="delivery-sec">
                                <h3>@lang('global.expected_date_of_delivery')</h3>
                                <h2>
                                    @if ($order->urgent == '1')
                                        @lang('global.after_one_hour')
                                        {{ Carbon\Carbon::parse($order->created_at)->addHours(1)->format('H:m') }}
                                    @else
                                        {{ Carbon\Carbon::parse($order->created_at)->addDays(1)->format('M d D, Y') }}
                                    @endif
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section ends -->


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
        });
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
				let cost = parseFloat( $(this).find(':selected').data('price') ) || 0;
				let subtotal = parseFloat( $('.order_subtotal').data('price') );
				let coupon = parseFloat( $('.coupon_price').data('price') ) || 0;
				let total = subtotal + cost - coupon;
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
                $('.descriptionWrapper').hide()
                $(this).closest('.descriptionWrapper').show();
            })
		});

	</script>

@endpush
