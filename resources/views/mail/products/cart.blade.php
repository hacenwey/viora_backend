@component('mail::message')
# @lang('global.hello')

<p>@lang('global.you_have_products_in_cart')</p>

<div>
    @foreach ($products as $product)
        Product: {{$product->title}}<br>
        Price: {{$product->price}}<br>

        @unless ($loop->last)
        --------------------<br>
        @endunless
    @endforeach
</div>

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
