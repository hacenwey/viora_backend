<!-- Quick-view modal popup start-->
<div class="modal fade bd-example-modal-lg product-quick-view theme-modal" id="modal{{$product->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content quick-view-modal">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        @php
                            $photo = explode(',',$product->photo);
                        @endphp
                        <div class="quick-view-img">
                            <img src="{{ $photo[0] }}" alt="{{ $product->title }}" class="img-fluid blur-up lazyload">
                        </div>
                    </div>
                    <div class="col-lg-6 rtl-text">
                        <div class="product-right">
                            <h2>{{ $product->title }}</h2>
                            @include('frontend._partials._discountWrapper', ['product' => $product, 'tag' => 'h3'])
                            {{-- <ul class="color-variant">
                                <li class="bg-light0"></li>
                                <li class="bg-light1"></li>
                                <li class="bg-light2"></li>
                            </ul> --}}
                            <div class="border-product">
                                <h6 class="product-title">{{ trans('global.product_details') }}</h6>
                                <p>{!! html_entity_decode($product->summary) !!}</p>
                            </div>
                            <form action="{{ route('backend.single-add-to-cart', ['slug' => $product->slug]) }}" method="POST">
                                @csrf
                                <div class="product-description border-product">
                                    @include('frontend._partials._attributes', ['product' => $product])
                                    <h6 class="product-title">{{ trans('global.quantity') }}</h6>
                                    <div class="qty-box">
                                        <div class="input-group">
                                            <span class="text-danger stock-alert" style="display: none">@lang('global.stock_errors', ['stock' => $product->stock])</span>
                                            <span class="input-group-prepend">
                                                <button type="button" class="btn quantity-left-minus" data-type="minus" data-field="">
                                                    <i class="ti-angle-left"></i>
                                                </button>
                                            </span>
                                            <input type="hidden" name="productId" value="{{ $product->id }}">
                                            <input type="hidden" name="stock" class="stock-input" value="{{ $product->stock }}">
                                            <input type="text" name="quantity" class="form-control input-number qty-input" value="{{ $product->stock =! 0 ? '1' : '0' }}">
                                            <span class="input-group-prepend">
                                                <button type="button" class="btn quantity-right-plus" data-type="plus" data-field="" {{ $product->stock == 0 ? 'disabled' : '' }}>
                                                    <i class="ti-angle-right"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-buttons">
                                    <button type="submit" {{ $product->stock == 0 ? 'disabled' : '' }} class="btn btn-solid">{{ trans('global.add_to_cart') }}</button>
                                    <a href="{{ route('backend.product-detail', ['slug' => $product->slug]) }}" class="btn btn-solid">{{ trans('global.view_details') }}</a>
                                </div>
                            </form>
                            @if ($product->stock == 0)
                                <span class="text-danger stock-alert{{ $product->id }}">@lang('global.out_of_stock')</span>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Quick-view modal popup end-->





@push('scripts')
<script>
    $(document).ready(function () {
            var $input = $('.input-qty'+{{ $product->id }});
                $input.val(1);

        $('.btn-number'+{{ $product->id }}).on('click', function (e) {
            e.preventDefault();

            if($(this).data('type') === 'plus'){
                if({!! $product->stock !!} != 0){
                    $input.val(parseInt($input.val())+1);
                    $('.btn-minus'+{{ $product->id }}).attr('disabled', false);
                } else {
                    if($input.val() < {!! $product->stock !!}){
                        $input.val(parseInt($input.val())+1);
                        $('.btn-minus'+{{ $product->id }}).attr('disabled', false);
                    } else {
                        $('.stock-alert'+{{ $product->id }}).show().delay(3000).fadeOut();
                    }
                }

            } else if($input.val() <= 1){
                $('.btn-minus'+{{ $product->id }}).attr('disabled', true);
            } else if($input.val() > 1){
                $input.val(parseInt($input.val())-1);
            }
        });
    });
</script>
@endpush
