<!-- loader start -->
<div class="loader_skeleton">
    <header class="header-tools marketplace">
        <div class="top-header d-none d-sm-block">
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
                                <a href="{{route('backend.wishlist')}}">
                                    <i class="fa fa-heart" aria-hidden="true"></i>
                                </a>
                            </li>
                            <li class="mobile-account">
                                <i class="fa fa-user" aria-hidden="true"></i> {{ trans('global.myaccount') }}
                            </li>
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
                            {{-- <div class="navbar">
                                <a href="javascript:void(0)">
                                    <div class="bar-style"><i class="fa fa-bars sidebar-bar" aria-hidden="true"></i>
                                    </div>
                                </a>
                            </div> --}}
                            <div class="brand-logo">
                                <a href="{{route('backend.home')}}">
                                    <img src="{{ settings()->get('logo') }}" class="img-fluid blur-up lazyload" alt="" style="max-height: 34px">
                                </a>
                            </div>
                        </div>
                        <div class="menu-right pull-right">
                            <div>
                                <nav>
                                    <div class="toggle-nav"><i class="fa fa-bars sidebar-bar"></i></div>
                                    <ul class="sm pixelstrap sm-horizontal">
                                        <li>
                                            <div class="mobile-back text-right">{{ trans('global.back') }}<i
                                                    class="fa fa-angle-right pl-2" aria-hidden="true"></i></div>
                                        </li>
                                        <li>
                                            <a href="{{ route('backend.home') }}">{{ trans('global.home') }}</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('backend.product-grids') }}">{{ trans('global.shop') }}</a>
                                        </li>
                                        <li class="mega">
                                            <a href="#">
                                                {{ trans('global.products') }}
                                                @if (getIsNewProducts())
                                                    <div class="lable-nav">new</div>
                                                @endif
                                            </a>
                                        </li>
                                        <li><a href="{{ route('backend.contact') }}">{{ trans('global.contact_us') }}</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('backend.about-us') }}">{{ trans('global.about_us') }}</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div>
                                <div class="icon-nav d-none d-sm-block">
                                    <ul>
                                        <li class="onhover-div mobile-search">
                                            <div><img src="{{ asset('assets/images/icon/search.png') }}" onclick="openSearch()"
                                                    class="img-fluid blur-up lazyload" alt=""> <i class="ti-search"
                                                    onclick="openSearch()"></i></div>
                                        </li>
                                        <li class="onhover-div mobile-setting">
                                            <div><img src="{{ asset('assets/images/icon/setting.png') }}"
                                                    class="img-fluid blur-up lazyload" alt=""> <i
                                                    class="ti-settings"></i></div>
                                        </li>
                                        <li class="onhover-div mobile-cart">
                                            <div><img src="{{ asset('assets/images/icon/cart.png') }}"
                                                    class="img-fluid blur-up lazyload" alt=""> <i
                                                    class="ti-shopping-cart"></i></div>
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
    @if ($page == 'index')
        <div class="home-slider">
            <div class="home"></div>
        </div>
        <section class="collection-banner banner-padding banner-furniture">
            <div class="container-fluid">
                <div class="row partition4">
                    <div class="col-lg-3 col-md-6">
                        <div class="ldr-bg">
                            <div class="contain-banner banner-4">
                                <div>
                                    <h4></h4>
                                    <h2></h2>
                                    <h6></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="ldr-bg">
                            <div class="contain-banner banner-4">
                                <div>
                                    <h4></h4>
                                    <h2></h2>
                                    <h6></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="ldr-bg">
                            <div class="contain-banner banner-4">
                                <div>
                                    <h4></h4>
                                    <h2></h2>
                                    <h6></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="ldr-bg">
                            <div class="contain-banner banner-4">
                                <div>
                                    <h4></h4>
                                    <h2></h2>
                                    <h6></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="container section-b-space">
            <div class="row section-t-space">
                <div class="col-lg-6 offset-lg-3">
                    <div class="product-para">
                        <p class="first"></p>
                        <p class="second"></p>
                    </div>
                </div>
                <div class="col-12">
                    <div class=" grid-products center-detail row">
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-sm-4 col-6 product-box">
                            <div class="img-wrapper"></div>
                            <div class="product-detail">
                                <h4></h4>
                                <h5></h5>
                                <h5 class="second"></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif($page == 'product')
        <div class="breadcrumb-section">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h2>{{ trans('global.product') }}</h2>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <nav aria-label="breadcrumb" class="theme-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('backend.home') }}">{{ trans('global.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('global.product') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <section class="section-b-space">
            <div class="collection-wrapper product-page">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-1 col-sm-2 col-xs-12">
                                        <div class="row vertical-product">
                                            <div class="col-sm-12 col-4">
                                                <div class="sm-product"></div>
                                            </div>
                                            <div class="col-sm-12 col-4">
                                                <div class="sm-product"></div>
                                            </div>
                                            <div class="col-sm-12 col-4">
                                                <div class="sm-product"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-10 col-xs-12 order-up">
                                        <div class="main-product sm-img"></div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="product-right">
                                            <h2></h2>
                                            <h4></h4>
                                            <h3></h3>
                                            <ul>
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                            </ul>
                                            <div class="btn-group">
                                                <div class="btn-ldr"></div>
                                                <div class="btn-ldr"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="product-right product-form-box">
                                            <h2></h2>
                                            <h4></h4>
                                            <h3></h3>
                                            <ul>
                                                <li></li>
                                                <li></li>
                                                <li></li>
                                            </ul>
                                            <div class="btn-group">
                                                <div class="btn-ldr"></div>
                                                <div class="btn-ldr"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <section class="tab-product m-0">
                                <div class="row">
                                    <div class="col-sm-12 col-lg-12">
                                        <ul>
                                            <li></li>
                                            <li></li>
                                            <li></li>
                                            <li></li>
                                        </ul>
                                        <p></p>
                                        <p></p>
                                        <p></p>
                                        <p></p>
                                        <p></p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    @elseif($page == 'shop')
        <div class="breadcrumb-section">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h2>@lang('global.products')</h2>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <nav aria-label="breadcrumb" class="theme-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('backend.home') }}">{{ trans('global.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('global.products') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <section class="section-b-space ratio_asos">
            <div class="collection-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-3 collection-filter">
                            <!-- side-bar colleps block stat -->
                            <div class="collection-filter-block">
                                <div class="filter-block">
                                    <h4 class="title-ldr"></h4>
                                    <ul>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                    </ul>
                                </div>
                                <div class="filter-block">
                                    <h4 class="title-ldr"></h4>
                                    <ul>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                    </ul>
                                </div>
                                <div class="filter-block">
                                    <h4 class="title-ldr"></h4>
                                    <ul>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- silde-bar colleps block end here -->
                            <!-- side-bar single product slider start -->
                            <div class="theme-card">
                                <h5 class="title-border"></h5>
                                <div>
                                    <div class="product-box">
                                        <div class="media">
                                            <div class="img-wrapper"></div>
                                            <div class="media-body align-self-center">
                                                <div class="product-detail">
                                                    <h4></h4>
                                                    <h6></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-box">
                                        <div class="media">
                                            <div class="img-wrapper"></div>
                                            <div class="media-body align-self-center">
                                                <div class="product-detail">
                                                    <h4></h4>
                                                    <h6></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-box">
                                        <div class="media">
                                            <div class="img-wrapper"></div>
                                            <div class="media-body align-self-center">
                                                <div class="product-detail">
                                                    <h4></h4>
                                                    <h6></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- side-bar single product slider end -->
                            <!-- side-bar banner start here -->
                            <div class="collection-sidebar-banner"></div>
                            <!-- side-bar banner end here -->
                        </div>
                        <div class="collection-content col">
                            <div class="page-main-content">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="top-banner-wrapper">
                                            <div class="img-ldr-top"></div>
                                            <div class="top-banner-content small-section">
                                                <h4></h4>
                                                <h5></h5>
                                                <p></p>
                                                <p></p>
                                            </div>
                                        </div>
                                        <div class="collection-product-wrapper">
                                            <div class="product-top-filter">
                                                <div class="row m-0 w-100">
                                                    <div class="col-xl-4">
                                                        <div class="filter-panel">
                                                            <h6 class="ldr-text"></h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-4 col-6">
                                                        <div class="filter-panel">
                                                            <h6 class="ldr-text"></h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-4 col-6">
                                                        <div class="filter-panel">
                                                            <h6 class="ldr-text"></h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2 col-lg-4 d-none d-lg-block">
                                                        <div class="filter-panel">
                                                            <h6 class="ldr-text"></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-wrapper-grid">
                                                <div class="row">
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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

    @elseif($page == 'collection')
        <div class="breadcrumb-section">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="page-title">
                            <h2>@lang('global.collection')</h2>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <nav aria-label="breadcrumb" class="theme-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('backend.home') }}">{{ trans('global.home') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('global.collection') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <section class="section-b-space">
            <div class="collection-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="collection-content col">
                            <div class="page-main-content">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="top-banner-wrapper">
                                            <div class="img-ldr-top"></div>
                                            <div class="top-banner-content small-section">
                                                <h4></h4>
                                                <h5></h5>
                                                <p></p>
                                                <p></p>
                                            </div>
                                        </div>
                                        <div class="collection-product-wrapper">
                                            <div class="product-wrapper-grid">
                                                <div class="row">
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper"></div>
                                                            <div class="product-detail">
                                                                <h4></h4>
                                                                <h5></h5>
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
    @endif
</div>
<!-- loader end -->
