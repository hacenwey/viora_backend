
<!-- header start -->
<header class="header-tools marketplace">
    <div class="mobile-fix-option"></div>
    <div class="top-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="header-contact">
                        <ul>
                            <li>{{ trans('global.welcome_to_our_store', ['name' => settings()->get('app_name')]) }}</li>
                            <li><i class="fa fa-phone" aria-hidden="true"></i>{{ trans('global.call_us') }}: {{ settings()->get('phone') }}</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 text-right">
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{ route('backend.contact') }}">{{ trans('global.contact_us') }}</a>
                        </li>
                        <li>
                            <a href="#">{{ trans('global.about_us') }}</a>
                        </li>
                        <li class="mobile-wishlist">
                            <a href="{{route('backend.wishlist')}}"><i class="fa fa-heart" aria-hidden="true"></i></a>
                        </li>
                        <li class="mobile-wishlist">
                            <a href="{{route('backend.product-compare')}}"><i class="ti-reload" aria-hidden="true"></i></a>
                        </li>
                        @if(Auth::guard()->check())
                            <li class="onhover-dropdown mobile-account">
                                <i class="fa fa-user" aria-hidden="true"></i> {{ trans('global.myaccount') }}
                                <ul class="onhover-show-div">
                                    <li>
                                        @if(Auth::guard()->user() && Auth::guard()->user()->is_admin)
                                            <a href="{{ route('backend.admin') }}" data-lng="{{ app()->getLocale() }}" target="_blank">{{ trans('global.dashboard') }}</a></li>
                                        @else
                                            <a href="{{ route('users.user') }}" data-lng="{{ app()->getLocale() }}" target="_blank">{{ trans('global.dashboard') }}</a></li>
                                        @endif
                                    <li><a href="{{ route('backend.logout') }}" data-lng="{{ app()->getLocale() }}">{{ trans('global.logout') }}</a></li>
                                </ul>
                            </li>
                        @else
                        <li class="mobile-account">
                            <a href="{{route('backend.login')}}">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                {{ trans('global.login') }}
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="main-menu">
                    <div class="menu-left">
                        <div class="brand-logo">
                            <a href="{{route('backend.home')}}">
                                <img src="{{ settings()->get('logo') }}" class="img-fluid blur-up lazyload" alt="" style="max-height: 34px">
                            </a>
                        </div>
                    </div>
                    <div class="menu-right pull-right">
                        <div>
                            <nav id="main-nav">
                                <div class="toggle-nav"><i class="fa fa-bars sidebar-bar"></i></div>
                                <ul id="main-menu" class="sm pixelstrap sm-horizontal">
                                    <li>
                                        <div class="mobile-back text-right">{{ trans('global.back') }}<i class="fa fa-angle-right pl-2"
                                                aria-hidden="true"></i></div>
                                    </li>
                                    <li>
                                        <a href="{{ route('backend.home') }}">{{ trans('global.home') }}</a>
                                    </li>
                                    @foreach (getAllCategory() as $category)
                                        @if ($category->children->count() > 0)
                                            <li class="mega" id="hover-cls">
                                                <a href="{{ route('backend.product-cat', $category->slug) }}">{{ $category->title }}</a>
                                                <ul class="mega-menu full-mega-menu">
                                                    <li>
                                                        <div class="container">
                                                            <div class="row">
                                                                @foreach ($category->children as $children)
                                                                    <div class="col mega-box">
                                                                        <div class="link-section">
                                                                            <div class="menu-title">
                                                                                <a href="{{ route('backend.product-cat', $children->slug) }}">
                                                                                    <h5>
                                                                                        {{ $children->title }}
                                                                                    </h5>
                                                                                </a>
                                                                            </div>
                                                                            <div class="menu-content">
                                                                                <ul>
                                                                                    @php
                                                                                        $level = 20;
                                                                                    @endphp
                                                                                    @foreach ($children->children as $child)
                                                                                        <li>
                                                                                            <a href="{{ route('backend.product-cat', $child->slug) }}">
                                                                                                {{ $child->title }}
                                                                                            </a>
                                                                                            {{-- @include('frontend._partials.category', ['category' => $child, 'level' => $level]) --}}
                                                                                        </li>
                                                                                        @php
                                                                                            $level =+ 20;
                                                                                        @endphp
                                                                                    @endforeach
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                        <div>
                            <div class="icon-nav">
                                <ul>
                                    <li class="onhover-div mobile-search">
                                        <div>
                                            <img src="{{ asset('assets/images/icon/search.png') }}" onclick="openSearch()"
                                                class="img-fluid blur-up lazyload" alt="">
                                                <i class="ti-search" onclick="openSearch()"></i>
                                        </div>
                                        <div id="search-overlay" class="search-overlay">
                                            <div>
                                                <span class="closebtn" onclick="closeSearch()" title="Close Overlay">×</span>
                                                <div class="overlay-content">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-xl-12">
                                                                <form method="POST" action="{{route('backend.product.search')}}">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <input type="search"
                                                                            class="form-control"
                                                                            name="search"
                                                                            id="exampleInputPassword1"
                                                                            placeholder="Search a Product">
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary">
                                                                        <i class="fa fa-search"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="onhover-div mobile-setting">
                                        <div>
                                            <img src="{{ asset('assets/images/icon/setting.png') }}"
                                                class="img-fluid blur-up lazyload" alt="">
                                                <i class="ti-settings"></i>
                                        </div>
                                        <div class="show-div setting">
                                            <h6>language</h6>
                                            <ul>
                                                @foreach (config('panel.available_languages') as $code => $lang)
                                                    <li class="{{ app()->getLocale() == $lang['short_code'] ? 'active' : '' }}">
                                                        <a href="{{ route('backend.locale', ['locale' => $lang['short_code']]) }}">
                                                            {{ $lang['title'] }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <h6>currency</h6>
                                            <ul class="list-inline">
                                                <li><a href="#">{{ settings()->get('currency_code') }}</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="onhover-div mobile-cart">
                                        <div>
                                            <a href="{{ route('backend.cart') }}">
                                                <img src="{{ asset('assets/images/icon/cart.png') }}" class="img-fluid blur-up lazyload" alt="">
                                                <i class="ti-shopping-cart"></i>
                                            </a>
                                            {{-- <div class="lable-nav">{{ cartCount() }}</div> --}}
                                        </div>
                                        @if (cartCount() > 0)
                                            @if(Auth::guard()->check())
                                                <ul class="show-div shopping-cart">
                                                    @foreach(getAllProductFromCart() as $data)
                                                        @php
                                                            $photo=explode(',',$data->product['photo']);
                                                        @endphp
                                                        <li>
                                                            <div class="media">
                                                                <a href="{{ route('backend.product-detail',$data->product['slug']) }}">
                                                                    <img class="mr-3" src="{{ $photo[0]}}" alt="{{$photo[0] }}" alt="Generic placeholder image">
                                                                </a>
                                                                <div class="media-body">
                                                                    <a href="{{ route('backend.product-detail',$data->product['slug']) }}">
                                                                        <h4>{{ $data->product['title'] }}</h4>
                                                                    </a>
                                                                    <h4><span>{{$data->quantity}} x {{ getFormattedPrice($data->price) }}</span></h4>
                                                                </div>
                                                            </div>
                                                            <div class="close-circle">
                                                                <a href="{{ route('backend.cart-delete',$data->id) }}" title="Remove this item">
                                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                                </a>
                                                            </div>
                                                        </li>
                                                    @endforeach

                                                    <li>
                                                        <div class="total">
                                                            <h5>{{ trans('global.total') }} : <span>{{ getFormattedPrice(totalCartPrice()) }}</span></h5>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="buttons">
                                                            <a href="{{ route('backend.cart') }}" class="view-cart">{{ trans('global.view_cart') }}</a>
                                                            <a href="{{ route('backend.checkout') }}" class="checkout">{{ trans('global.checkout') }}</a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            @else
                                                <ul class="show-div shopping-cart">
                                                    @foreach(getAllProductFromCart() as $key => $data)
                                                        @php
                                                            $photo=explode(',',$data['product']['photo']);
                                                        @endphp
                                                        <li>
                                                            <div class="media">
                                                                <a href="{{ route('backend.product-detail',$data['product']['slug']) }}">
                                                                    <img class="mr-3" src="{{ $photo[0]}}" alt="{{$photo[0] }}" alt="Generic placeholder image">
                                                                </a>
                                                                <div class="media-body">
                                                                    <a href="{{ route('backend.product-detail',$data['product']['slug']) }}">
                                                                        <h4>{{ $data['product']['title'] }}</h4>
                                                                    </a>
                                                                    <h4><span>{{$data['quantity']}} x {{ getFormattedPrice($data['price']) }}</span></h4>
                                                                </div>
                                                            </div>
                                                            <div class="close-circle">
                                                                <a href="{{ route('backend.cart-delete', $key) }}" title="Remove this item">
                                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                                </a>
                                                            </div>
                                                        </li>
                                                    @endforeach

                                                    <li>
                                                        <div class="total">
                                                            <h5>{{ trans('global.total') }} : <span>{{ getFormattedPrice(totalCartPrice()) }}</span></h5>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="buttons">
                                                            <a href="{{ route('backend.cart') }}" class="view-cart">{{ trans('global.view_cart') }}</a>
                                                            <a href="{{ route('backend.checkout') }}" class="checkout">{{ trans('global.checkout') }}</a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            @endif
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header end -->
