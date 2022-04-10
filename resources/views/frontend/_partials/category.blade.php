{{-- @if (count($category->children) > 0)
    @foreach ($category->children as $category)
        <li class="breadcrumb-item {{ count($category->children) < 1 ? 'active' : '' }}" @if(count($category->children) < 1) aria-current="page" @endif>
            <a href="{{ route('backend.product-cat',$category->slug) }}">
                {{ $category->title }}
            </a>
        </li>
        @include('frontend._partials.category', $category)
    @endforeach
@endif --}}

@if (count($category->children) > 0)
    <ul>
        @foreach ($category->children as $category)
            <li style="margin-left: 20px">
                <a href="{{ route('backend.product-cat', $category->slug) }}">
                    {{ $category->title }}
                </a>
                @include('frontend._partials.category', $category)
            </li>
        @endforeach
    </ul>
@endif
