@php
    $after_discount = 0;
@endphp
@if ($product->discount > 0)
    @php
        $after_discount=($product->price - ($product->price * $product->discount)/100);
    @endphp
    <{{ $tag }} style="{{ $style ?? '' }}">
        {{ getFormattedPrice($after_discount) }}
        <del>{{ getFormattedPrice($product->price) }}</del>
    </{{ $tag }}>
@else
    <{{ $tag }}>{{ getFormattedPrice($product->price) }}</{{ $tag }}>
@endif
