@extends('frontend.layouts.master')

@section('title',settings()->get('app_name').' | '. trans('global.compare'))

@section('main-content')

{{--@include('frontend.layouts.loader', ['page' => 'collection'])--}}


<!-- section start -->
<section class="compare-section section-b-space ratio_asos">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="slide-4">
                    @foreach($products as $product)
                        <div>
                            @php
                                $photo = explode(',',$product->photo);
                            @endphp
                            <div class="compare-part">
                                <a href="{{ route('backend.remove-product-compare', ['slug' => $product->slug]) }}" class="close-btn mr-2">
                                    <span aria-hidden="true">×</span>
                                </a>
                                <div class="img-secton">
                                    <div>
                                        <img src="{{ $photo[0] }}" class="img-fluid blur-up lazyload bg-img" alt="">
                                    </div>
                                    <a href="{{ route('backend.product-detail', ['slug' => $product->slug]) }}">
                                        <h5>{{ $product->title }}</h5>
                                    </a>
                                    @include('frontend._partials._discountWrapper', ['product' => $product, 'tag' => 'h5'])
                                </div>
                                <div class="detail-part">
                                    <div class="title-detail">
                                        <h5>@lang('cruds.product.fields.description')</h5>
                                    </div>
                                    <div class="inner-detail" style="max-height: 78px; overflow: scroll;">
                                        <p>
                                            {!! $product->summary !!}
                                        </p>
                                    </div>
                                </div>
                                <div class="detail-part">
                                    <div class="title-detail">
                                        <h5>@lang('cruds.product.fields.brand')</h5>
                                    </div>
                                    <div class="inner-detail">
                                        <p>{{ $product->brand ? $product->brand->title : '' }}</p>
                                    </div>
                                </div>
                                @if($product->attributes)
                                    @foreach($attributes as $attribute)
{{--                                        @php $attributeCheck = in_array($attribute->id, $product->attributes->pluck('attribute_id')->toArray()) @endphp--}}
{{--                                        @if ($attributeCheck)--}}
                                            <div class="detail-part">
                                                <div class="title-detail">
                                                    <h5>{{ $attribute->name }}</h5>
                                                </div>
                                                <div class="inner-detail" style="height: 46px;">
                                                    <p style="text-transform: uppercase">
                                                        @foreach($product->attributes as $attributeValue)
                                                            @if ($attributeValue->attribute_id == $attribute->id)
                                                                {{ $attributeValue->value }},
                                                            @endif
                                                        @endforeach
                                                    </p>
                                                </div>
                                            </div>
{{--                                        @endif--}}
                                    @endforeach
                                @endif
                                <div class="detail-part">
                                    <div class="title-detail">
                                        <h5>@lang('global.availability')</h5>
                                    </div>
                                    <div class="inner-detail">
                                        <p>
                                            {{ $product->stock != 0 ? trans('global.in_stock') : trans('global.out_of_stock') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="btn-part">
                                    @if (count($product->attributes) > 0)
                                        <a href="#" data-toggle="modal" data-target="#modal{{ $product->id }}" class="btn btn-solid">@lang('global.add_to_cart')</a>
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
                                            <div class="product-buttons">
                                                <button type="submit" {{ $product->stock == 0 ? 'disabled' : '' }} class="btn btn-solid">{{ trans('global.add_to_cart') }}</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Section ends -->

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
