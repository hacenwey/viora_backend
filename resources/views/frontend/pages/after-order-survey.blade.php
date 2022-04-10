<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
	@include('frontend.layouts.head')
</head>
<body class="js {{ app()->getLocale() == 'ar' ? 'rtl' : '' }}">

    <!-- thank-you section start -->
    <section class="section-b-space light-layout">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="success-text"><i class="fa fa-check-circle" aria-hidden="true"></i>
                        <h2>@lang('global.thank_you')</h2>
                        <p>@lang('global.order_placed_success')</p>
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

</body>
</html>
