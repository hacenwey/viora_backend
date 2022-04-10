{{-- @if ($paginator->hasPages()) --}}
    <div class="col-xl-6 col-md-6 col-sm-12">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <a class="page-link" href="javascript:void(0)">
                            <span aria-hidden="true">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            </span>
                        </a>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                            <span aria-hidden="true">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                            </span>
                            <span class="sr-only">@lang('pagination.previous')</span>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <a href="javascript:void(0)" class="page-link">{{ $page }}</a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                            <span aria-hidden="true">
                                <i class="fa fa-chevron-right" aria-hidden="true"></i>
                            </span>
                            <span class="sr-only">@lang('pagination.next')</span>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <a class="page-link" href="javascript:void(0)">
                            <span aria-hidden="true">
                                <i class="fa fa-chevron-right" aria-hidden="true"></i>
                            </span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12">
        <div class="product-search-count-bottom">
            <h5>@lang('pagination.showing_products') {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} @lang('pagination.of') {{ $paginator->total() }} @lang('pagination.results')</h5>
        </div>
    </div>
{{-- @endif --}}
