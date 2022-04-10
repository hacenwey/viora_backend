<!-- footer start -->
<footer class="footer-light">
    @include('frontend.layouts.newsletter')
    <section class="section-b-space light-layout">
        <div class="container">
            <div class="row footer-theme partition-f">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-title footer-mobile-title">
                        <h4>{{ trans('global.about') }}</h4>
                    </div>
                    <div class="footer-contant">
                        <div class="footer-logo">
                            <img src="{{ settings()->get('logo') }}" alt="{{ settings()->get('store_name') }}" height="35px">
                        </div>
                        <p>
                            {{ settings()->get('short_des') }}
                        </p>
                        <div class="footer-social">
                            <ul>
                                @if (settings()->get('facebook'))
                                    <li><a href="{{ settings()->get('facebook') }}"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                @endif
                                @if (settings()->get('twitter'))
                                    <li><a href="{{ settings()->get('twitter') }}"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                @endif
                                @if (settings()->get('snapchat'))
                                    <li><a href="{{ settings()->get('snapchat') }}"><i class="fa fa-snapchat" aria-hidden="true"></i></a></li>
                                @endif
                                @if (settings()->get('whatsapp'))
                                    <li><a href="{{ settings()->get('whatsapp') }}"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col offset-xl-1">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>{{ trans('global.myaccount') }}</h4>
                        </div>
                        <div class="footer-contant">
                            <ul>
                                @foreach (getAllCategory() as $item)
                                    <li><a href="{{ route('backend.product-cat', [$item->slug]) }}">{{ $item->title }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>{{ trans('global.pages') }}</h4>
                        </div>
                        <div class="footer-contant">
                            <ul>
                                @foreach (static_pages() as $page)
                                    <li><a href="{{ route('backend.pages', ['page' => $page->slug]) }}">{{ $page->title }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="sub-title">
                        <div class="footer-title">
                            <h4>{{ trans('global.store_informations') }}</h4>
                        </div>
                        <div class="footer-contant">
                            <ul class="contact-list">
                                <li>
                                    <i class="fa fa-map-marker"></i>
                                    {{ settings()->get('address') }}
                                </li>
                                <li>
                                    <i class="fa fa-phone"></i>
                                    {{ trans('global.callus') }} {{ settings()->get('phone') }}
                                </li>
                                <li>
                                    <i class="fa fa-whatsapp"></i>
                                    {{ trans('global.whatsapp') }}
                                    <a href="https://web.whatsapp.com/send?phone={{ settings()->get('whatsapp') }}&text=" target="_blank" rel="noopener noreferrer">{{ settings()->get('whatsapp') }}</a>
                                </li>
                                <li>
                                    <i class="fa fa-envelope-o"></i>
                                    {{ trans('global.emailus') }} <a href="mailto:{{ settings()->get('email') }}" target="_blank" rel="noopener noreferrer">{{ settings()->get('email') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="sub-footer">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="footer-end">
                        <p>
                            {{-- <i class="fa fa-copyright" aria-hidden="true"></i> --}}
                            {!! settings()->get('copyrights') !!}
                        </p>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                    {{-- <div class="payment-card-bottom">
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
                                <a href="#"><img src="../assets/images/icon/american-express.png" alt=""></a>
                            </li>
                            <li>
                                <a href="#"><img src="../assets/images/icon/discover.png" alt=""></a>
                            </li>
                        </ul>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer end -->
