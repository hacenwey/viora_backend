@extends('frontend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.products'))

@section('main-content')

@include('frontend.layouts.loader', ['page' => 'shop'])

<!-- section start -->
<section class="section-b-space ratio_asos">
    <form action="{{ route('backend.shop.filter') }}" method="POST">
        @csrf
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3 collection-filter">
                        <!-- side-bar colleps block stat -->
                        <div class="collection-filter-block">
                            <!-- brand filter start -->
                            <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i> @lang('global.back')</span></div>
                            <div class="collection-collapse-block">
                                <h3 class="collapse-block-title">{{ trans('global.brands') }}</h3>
                                <div class="collection-collapse-block-content">
                                    <div class="collection-brand-filter" style="max-height: 700px; overflow:scroll">
                                        @foreach ($brands as $key => $brand)
                                            @php
                                                $brands_ids = [];
                                                if (!empty($_GET['brands'])) {
                                                    $brands_ids = explode(',',$_GET['brands']);
                                                }
                                            @endphp
                                            <div class="custom-control custom-checkbox collection-filter-checkbox">
                                                <input type="checkbox" name="brands[]" class="custom-control-input" id="brand{{ $brand->id }}" value="{{ $brand->slug }}" {{ (in_array($brand->slug, $brands_ids)) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="brand{{ $brand->id }}">
                                                    {{ $brand->title }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- price filter start here -->
                            <div class="collection-collapse-block border-0 open">
                                <h3 class="collapse-block-title">{{ trans('global.price') }}</h3>
                                <div class="collection-collapse-block-content">
                                    <div class="wrapper mt-3">
                                        <div class="range-slider">
                                            <input type="text" class="js-range-slider" value="" />
                                            <input type="hidden" name="price_range" id="price_range" value="@if(!empty($_GET['price'])){{$_GET['price']}}@endif"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 text-center d-flex">
                                <button type="submit" class="btn btn-solid mr-2">
                                    @lang('global.filter')
                                </button>
                                <a href="{{ route('backend.product-grids') }}" class="btn btn-outline">
                                    @lang('global.clear')
                                </a>
                            </div>
                        </div>
                        <!-- silde-bar colleps block end here -->
                        <!-- side-bar single product slider start -->
                        <div class="theme-card">
                            <h5 class="title-border">{{ trans('global.new_products') }}</h5>
                            <div class="offer-slider slide-1">
                                @foreach ($newproducts->chunk(3) as $groups)
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
                                                    @include('frontend._partials._discountWrapper', ['product' => $nprod, 'tag' => 'h5', 'style' => 'display:grid;margin-top:10px'])
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- side-bar single product slider end -->
                        @if(settings()->has('product_left_banner'))
                            <!-- side-bar banner start here -->
                            <div class="collection-sidebar-banner">
                                <a href="#">
                                    <img src="{{ settings()->get('product_left_banner', '../assets/images/side-banner.png') }}" class="img-fluid blur-up lazyload" alt="">
                                </a>
                            </div>
                            <!-- side-bar banner end here -->
                        @endif
                    </div>
                    <div class="collection-content col-sm-9">
                        <div class="page-main-content">
                            <div class="row">
                                <div class="col-sm-12">
                                    {{-- <div class="top-banner-wrapper">
                                        <a href="#"><img src="../assets/images/mega-menu/2.jpg" class="img-fluid blur-up lazyload" alt=""></a>
                                        <div class="top-banner-content small-section">
                                            <h4>fashion</h4>
                                            <h5>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                            </h5>
                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled
                                                it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release
                                                of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                        </div>
                                    </div> --}}
                                    <div class="collection-product-wrapper">
                                        <div class="product-top-filter">
                                            <div class="row">
                                                <div class="col-xl-12">
                                                    <div class="filter-main-btn">
                                                        <span class="filter-btn btn btn-theme">
                                                            <i class="fa fa-filter" aria-hidden="true"></i>
                                                            @lang('global.filter')
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="product-filter-content">
                                                        <div class="search-count">
                                                            <h5>@lang('pagination.showing_products') {{ $products->firstItem() }}-{{ $products->lastItem() }} @lang('pagination.of') {{ $products->total() }} @lang('pagination.results')</h5>
                                                        </div>
                                                        <div class="collection-view">
                                                            <ul>
                                                                <li><i class="fa fa-th grid-layout-view"></i></li>
                                                                <li><i class="fa fa-list-ul list-layout-view"></i></li>
                                                            </ul>
                                                        </div>
                                                        <div class="collection-grid-view">
                                                            <ul>
                                                                <li><img src="../assets/images/icon/2.png" alt="" class="product-2-layout-view"></li>
                                                                <li><img src="../assets/images/icon/3.png" alt="" class="product-3-layout-view"></li>
                                                                <li><img src="../assets/images/icon/4.png" alt="" class="product-4-layout-view"></li>
                                                                <li><img src="../assets/images/icon/6.png" alt="" class="product-6-layout-view"></li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-page-per-view">
                                                            <select class="show" name="show" onchange="this.form.submit();">
                                                                <option value="" disabled selected>Products par page</option>
                                                                <option value="9" @if(!empty($_GET['show']) && $_GET['show']=='9') selected @endif>09</option>
                                                                <option value="15" @if(!empty($_GET['show']) && $_GET['show']=='15') selected @endif>15</option>
                                                                <option value="21" @if(!empty($_GET['show']) && $_GET['show']=='21') selected @endif>21</option>
                                                                <option value="30" @if(!empty($_GET['show']) && $_GET['show']=='30') selected @endif>30</option>
                                                            </select>
                                                        </div>
                                                        <div class="product-page-filter">
                                                            <select class='sortBy' name='sortBy' onchange="this.form.submit();">
                                                                <option value="" disabled selected>@lang('global.sort_by')</option>
                                                                <option value="title" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='title') selected @endif>@lang('global.name')</option>
                                                                <option value="price" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='price') selected @endif>@lang('global.price')</option>
                                                                <option value="category" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='category') selected @endif>@lang('global.category')</option>
                                                                <option value="brand" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='brand') selected @endif>@lang('global.brand')</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-wrapper-grid">
                                            <div class="row margin-res">
                                                @foreach($products as $product)
                                                    <div class="col-xl-3 col-6 col-grid-box">
                                                        <div class="product-box">
                                                            <div class="img-wrapper">
                                                                @php
                                                                    $photo = explode(',', $product->photo);
                                                                @endphp
                                                                <div class="front">
                                                                    <a href="{{ route('backend.product-detail',$product->slug) }}">
                                                                        <img src="{{ $photo[0] }}" class="img-fluid blur-up lazyload bg-img" alt="{{ $product->title }}">
                                                                    </a>
                                                                </div>
                                                                <div class="back">
                                                                    <a href="{{ route('backend.product-detail',$product->slug) }}">
                                                                        <img src="{{ count($photo) > 1 ? $photo[1] : $photo[0] }}" class="img-fluid blur-up lazyload bg-img" alt="{{ $product->title }}">
                                                                    </a>
                                                                </div>
                                                                <div class="cart-info cart-wrap">
                                                                    <button data-toggle="modal" type="button" data-target="#modal{{ $product->id }}" title="{{ trans('global.add_to_cart') }}">
                                                                        <i class="ti-shopping-cart"></i>
                                                                    </button>
                                                                    <a href="{{ route('backend.add-to-wishlist', ['slug' => $product->slug]) }}" title="{{ trans('global.add_to_wishlist') }}">
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
                                                                <div>
                                                                    @include('frontend._partials._productRates', ['product' => $product])
                                                                    <a href="{{ route('backend.product-detail', ['slug' => $product->slug]) }}">
                                                                        <h6>{{ $product->title }}</h6>
                                                                    </a>
                                                                    <p>
                                                                        {!! $product->summary !!}
                                                                    </p>
                                                                    @include('frontend._partials._discountWrapper', ['product' => $product, 'tag' => 'h4'])
                                                                    @include('frontend._partials._attributesPreview', ['product' => $product])
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="product-pagination">
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
            </div>
        </div>
    </form>
</section>
<!-- section End -->

    <!-- Modal -->
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
							document.location.href=document.location.href;
						});
					}
                    else{
                        swal('error',response.msg,'error').then(function(){
							// document.location.href=document.location.href;
						});
                    }
                }
            })
        });
    </script> --}}
    <script>
        // $(document).ready(function(){
        // /*----------------------------------------------------*/
        // /*  Jquery Ui slider js
        // /*----------------------------------------------------*/
        // if ($("#slider-range").length > 0) {
        //     const max_value = parseInt( $("#slider-range").data('max') ) || 500;
        //     const min_value = parseInt($("#slider-range").data('min')) || 0;
        //     const currency = $("#slider-range").data('currency') || '';
        //     let price_range = min_value+'-'+max_value;
        //     if($("#price_range").length > 0 && $("#price_range").val()){
        //         price_range = $("#price_range").val().trim();
        //     }

        //     let price = price_range.split('-');
        //     $("#slider-range").slider({
        //         range: true,
        //         min: min_value,
        //         max: max_value,
        //         values: price,
        //         slide: function (event, ui) {
        //             $("#amount").val(currency + ui.values[0] + " -  "+currency+ ui.values[1]);
        //             $("#price_range").val(ui.values[0] + "-" + ui.values[1]);
        //         }
        //     });
        //     }
        // if ($("#amount").length > 0) {
        //     const m_currency = $("#slider-range").data('currency') || '';
        //     $("#amount").val(m_currency + $("#slider-range").slider("values", 0) +
        //         "  -  "+m_currency + $("#slider-range").slider("values", 1));
        //     }
        // })
        $(function() {
            var $range = $(".js-range-slider"),
                $inputFrom = $(".js-input-from"),
                $inputTo = $(".js-input-to"),
                instance,
                min = 0,
                max = {!! $max_price !!},
                from = 0,
                to = 0;

            let price_range = min+'-'+max;
            if($("#price_range").length > 0 && $("#price_range").val()){
                price_range = $("#price_range").val().trim();
            }
            let price = price_range.split('-');

            $range.ionRangeSlider({
                type: "double",
                min: min,
                max: max,
                from: price[0],
                to: price[1],
                prefix: '',
                postfix: ' MRU',
                onStart: updateInputs,
                onChange: updateInputs,
                step: 100,
                prettify_enabled: true,
                prettify_separator: ".",
                values_separator: " - ",
                force_edges: true
            });

            instance = $range.data("ionRangeSlider");

            function updateInputs(data) {
                from = data.from;
                to = data.to;

                price_range = from+'-'+to;
                $('#price_range').val(price_range)

                $inputFrom.prop("value", from);
                $inputTo.prop("value", to);
            }

            $inputFrom.on("input", function() {
                var val = $(this).prop("value");

                // validate
                if (val < min) {
                    val = min;
                } else if (val > to) {
                    val = to;
                }

                instance.update({
                    from: val
                });
            });

            $inputTo.on("input", function() {
                var val = $(this).prop("value");

                // validate
                if (val < from) {
                    val = from;
                } else if (val > max) {
                    val = max;
                }

                instance.update({
                    to: val
                });
            });

            });
    </script>
@endpush
