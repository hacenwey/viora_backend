<div class="rating">
    @php
        $rate = ceil($product->getReview->avg('rate'))
    @endphp
    @for ($i = 1; $i <= 5; $i++)
        @if ($rate >= $i)
            <i class="fa fa-star" style="color: #ffa200"></i>
        @else
            <i class="fa fa-star" style="color: #dddddd"></i>
        @endif
    @endfor
</div>
