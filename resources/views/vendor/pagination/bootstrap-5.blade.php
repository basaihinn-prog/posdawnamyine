@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="d-flex justify-content-between flex-fill d-sm-none p-3">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">@lang('pagination.previous')</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}"
                            rel="prev">@lang('pagination.previous')</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}"
                            rel="next">{{ __('Next') }}</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">{{ __('Next') }}</span>
                    </li>
                @endif
            </ul>
        </div>

        <div
            class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-between w-100 pagination-container p-3 flex-wrap gap-2">
            <div>
                <p class="small text-muted pagination-text">
                    {{ __('Showing') }}
                    <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="fw-semibold">{{ $paginator->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div>
                <ul class="pagination d-flex align-items-center">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span class="page-link" aria-hidden="true">{{__('Previous')}}
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                                aria-label="@lang('pagination.previous')">{{__('Previous')}}
                            </a>
                        </li>
                    @endif

                    @php
                        $visiblePages = 5;
                        $totalPages = $paginator->lastPage();
                        $currentPage = $paginator->currentPage();
                    @endphp

                    <li class="page-item {{ $currentPage == 1 ? 'active' : '' }}">
                        @if ($currentPage == 1)
                            <span class="page-link">1</span>
                        @else
                            <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                        @endif
                    </li>

                    @for ($i = 2; $i <= min($totalPages - 1, $visiblePages); $i++)
                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                            @if ($currentPage == $i)
                                <span class="page-link">{{ $i }}</span>
                            @else
                                <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                            @endif
                        </li>
                    @endfor

                    @if ($totalPages > $visiblePages + 1)
                        @if ($currentPage > $visiblePages && $currentPage < $totalPages - 1)
                            {{-- Show current page if it's not in the initial range --}}
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                            <li class="page-item active"><span class="page-link">{{ $currentPage }}</span></li>
                        @elseif ($currentPage <= $totalPages - 2)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                    @endif

                    @if ($totalPages > 1)
                        <li class="page-item {{ $currentPage == $totalPages ? 'active' : '' }}">
                            @if ($currentPage == $totalPages)
                                <span class="page-link">{{ $totalPages }}</span>
                            @else
                                <a class="page-link" href="{{ $paginator->url($totalPages) }}">{{ $totalPages }}</a>
                            @endif
                        </li>
                    @endif

                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                                aria-label="Next">{{__('Next')}}
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="Next">
                            <span class="page-link" aria-hidden="true">{{__('Next')}}
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
