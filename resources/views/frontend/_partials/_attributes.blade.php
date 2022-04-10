@if($product->attributes)
    <div class="size">
        <div class="row">
            @foreach($attributes as $attribute)
                @php $attributeCheck = in_array($attribute->id, $product->attributes->pluck('attribute_id')->toArray()) @endphp
                @if ($attributeCheck)
                    <div class="col-lg-12 col-12 mt-2 attributes-wrap">
                        <h5 class="title mb-2">{{ ucwords($attribute->name) }}</h5>
                        @if ($attribute->frontend_type == 'select')
                            <select class="attr-select{{ $product->id }}" name="{{ $attribute->code }}" required>
                                {{-- <option data-price="0" value="0"> Select a {{ $attribute->name }}</option> --}}
                                @foreach($product->attributes as $attributeValue)
                                    @if ($attributeValue->attribute_id == $attribute->id)
                                        <option data-price="{{ $attributeValue->price }}" value="{{ $attributeValue->value }}">
                                            {{ ucwords($attributeValue->value) }} +{{ getFormattedPrice($attributeValue->price) }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        @endif
                        @if($attribute->frontend_type == 'radio')
                            <div class="d-inline-block" style="position: relative">
                                @foreach ($product->attributes as $attributeValue)
                                    @if ($attributeValue->attribute_id == $attribute->id)
                                        @if ($attribute->code == 'color')
                                            <input type="radio" class="attr-radio attr" name="{{ $attribute->code }}" data-price="{{ $attributeValue->price }}" id="{{ $attributeValue->value }}_{{ $product->id }}" value="{{ $attributeValue->value }}" checked/>
                                            <label class="attr" for="{{ $attributeValue->value }}_{{ $product->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $attributeValue->price > 0 ? '+'.$attributeValue->price .' MRU' : '' }}">
                                                <span class="{{ $attributeValue->value }} attr"></span>
                                            </label>
                                        @elseif($attribute->code == 'size')
                                        <input type="radio" class="attr-radio attr" name="{{ $attribute->code }}" data-price="{{ $attributeValue->price }}" id="{{ $attributeValue->value }}_{{ $product->id }}" value="{{ $attributeValue->value }}" checked/>
                                            <label class="attr size" for="{{ $attributeValue->value }}_{{ $product->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $attributeValue->price > 0 ? '+'.$attributeValue->price .' MRU' : '' }}">
                                                <span class="gray attr">
                                                    {{ $attributeValue->value }}
                                                </span>
                                            </label>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
            <div class="col-lg-12 col-12 mt-2">
                <button class="btn btn-block {{ count($product->attributes) > 0 ? '' : 'd-none' }} clearBtn" data-toggle="tooltip" data-placement="left" title="{{ trans('global.clear_all_selection') }}">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
    $(document).ready(function () {
        function getFormattedPrice(prodId, cPrice, fprice){
            // console.log(prodId)
            $.ajax({
                type: 'GET',
                url: '{!! route('backend.get-formatted-price') !!}',
                data: {
                    id: prodId,
                    current_price: cPrice,
                    extra_price: fprice,
                    },
                success: function (response) {
                    return response;
                }
            });
        }
        $('.attr-select').change(function () {
            let prodId = {!! $product->id !!};
            // $('.productPrice'+prodId).html(updatedPrice);
            let extraPrice = $(this).find(':selected').data('price');
            let price = parseFloat(updatedPrice - lastSelected);
            let finalPrice = (Number(extraPrice) + price).toFixed(2);
            updatedPrice = finalPrice
            $('#finalPrice'+prodId).val(updatedPrice);
            console.log("Updated : " + updatedPrice)
            getFormattedPrice(prodId, updatedPrice);
            // lastSelected = Number(extraPrice);
        });
        $('.attr-radio').change(function () {
            $(this).parent().parent().siblings().find('.clearBtn').removeClass('d-none');
            // let prodId = $(this).siblings('.product-id').val();
            // let currPrice = $(this).siblings('.product-price');
            // let extraPrice = $(this).data('price');
            // if(extraPrice > 0){
            //     $.ajax({
            //         type: 'GET',
            //         url: '{!! route('backend.get-formatted-price') !!}',
            //         data: {
            //             id: prodId,
            //             current_price: currPrice.val(),
            //             extra_price: extraPrice,
            //             },
            //         success: function (response) {
            //             attr.siblings('.product-price').val(response.price);
            //             attr.parent().parent().siblings().find('.extra-price').html("+ "+response.formatted_price)
            //         }
            //     });
            // }
            // let price = parseFloat(updatedPrice);
            // let finalPrice = (Number(extraPrice) + price).toFixed(2);
            // updatedPrice = finalPrice
            // $('#finalPrice'+prodId).val(updatedPrice);
            // console.log("Updated : " + updatedPrice)
        });
        $('.clearBtn').on('click', function(e) {
            e.preventDefault();
            $(this).parent().siblings().find('.attr-radio').prop('checked', false);
            $(this).addClass('d-none')
            // $(this).closest('.extra-price').empty()
            // let prodId = {!! $product->id !!};
            // let extraPrice = 0;
            // let price = parseFloat(realPrice);
            // let finalPrice = (Number(extraPrice) + price).toFixed(2);
            // updatedPrice = finalPrice
            // $('#finalPrice'+prodId).val(finalPrice);
            // console.log("Updated : " + finalPrice)
            // getFormattedPrice(prodId, finalPrice);
        });
    });
</script>
@endpush
