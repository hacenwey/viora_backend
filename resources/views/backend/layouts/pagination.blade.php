<div class="row">
    <div class="col-xl-6 col-md-6 col-sm-12">
        <span>@lang('pagination.showing') @lang('pagination.results') {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} @lang('pagination.of') {{ $paginator->total() }} @lang('cruds.'.$name.'.title')</span>
    </div>

    <div class="col-xl-6 col-md-6 col-sm-12 text-end">
        <nav aria-label="Page navigation">
            <?php
                // config
                $link_limit = 7; // maximum number of links (a little bit inaccurate, but will be ok for now)
            ?>

            @if ($paginator->lastPage() > 1)
                <ul class="pagination" style="justify-content: flex-end;">
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <a class="page-link" href="javascript:void(0)">
                                <span aria-hidden="true">
                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                </span>
                            </a>
                        </li>
                    @else
                        <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                                <span aria-hidden="true">
                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                </span>
                                <span class="sr-only">@lang('pagination.previous')</span>
                            </a>
                        </li>
                    @endif
                    @if($paginator->currentPage() > 4)
                        <li class="page-item hidden-xs">
                            <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                        </li>
                    @endif
                    @if($paginator->currentPage() > 5)
                        <li class="page-item hidden-xs">
                            <a class="page-link" href="{{ $paginator->url(2) }}">2</a>
                        </li>
                    @endif
                    @if($paginator->lastPage() > 7)
                        <li class="page-item disabled"><a class="page-link">...</a></li>
                    @endif
                    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                        <?php
                        $half_total_links = floor($link_limit / 2);
                        $from = $paginator->currentPage() - $half_total_links;
                        $to = $paginator->currentPage() + $half_total_links;
                        if ($paginator->currentPage() < $half_total_links) {
                        $to += $half_total_links - $paginator->currentPage();
                        }
                        if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                            $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
                        }
                        ?>
                        @if ($from < $i && $i < $to)
                            <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                                <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor
                    @if($paginator->currentPage() < $paginator->lastPage() - 3)
                        <li class="page-item disabled">
                            <a class="page-link">...</a>
                        </li>
                        <li class="page-item hidden-xs">
                            <a class="page-link" href="{{ $paginator->url($paginator->lastPage() - 1) }}">{{ $paginator->lastPage() -1 }}</a>
                        </li>
                        <li class="page-item hidden-xs">
                            <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                        </li>
                    @endif
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
            @endif
        </nav>
    </div>
</div>
