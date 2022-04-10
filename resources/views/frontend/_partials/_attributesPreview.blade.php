@foreach($attributes as $attribute)
    @php $attributeCheck = in_array($attribute->id, $product->attributes()->distinct()->pluck('attribute_id')->toArray()) @endphp
    @if ($attributeCheck)
        @php
            $exist = false;
        @endphp
        @if ($attribute->code == 'color')
            <ul class="color-variant">
                @foreach ($product->attributes as $attributeValue)
                    @if ($attributeValue->attribute_id == $attribute->id)
                        <li class="bg-{{ $attributeValue->value }}"></li>
                        @php
                            $exist = true;
                        @endphp
                    @endif
                @endforeach
            </ul>
        @elseif($attribute->code == 'size')
            <div class="product-right">
                <div class="size-box">
                    <ul>
                        @foreach ($product->attributes as $attributeValue)
                            @if ($attributeValue->attribute_id == $attribute->id)
                                <li><a href="javascript:void(0)">{{ $attributeValue->value }}</a></li>
                                @php
                                    $exist = true;
                                @endphp
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        @if ($exist)
            @php
                break;
            @endphp
        @endif
    @endif
@endforeach
