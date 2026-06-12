@if ($paginator->hasPages())
    <nav class="p-3">
        <div class="d-flex align-items-center justify-content-center justify-content-sm-center gap-2 flex-wrap">
            <ul class="pagination d-flex align-items-center gap-2">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled"><span class="page-link previous-page-item"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 12L6 8L10 4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    </span></li>
                @else
                    <li class="page-item"><a class="page-link previous-page-item" href="{{ $paginator->previousPageUrl() }}">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 12L6 8L10 4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    </a></li>
                @endif

                {{-- Pagination Elements --}}
                @php
                    $currentPage = $paginator->currentPage();
                    $lastPage = $paginator->lastPage();
                @endphp

                @foreach ($elements as $element)
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $currentPage)
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @elseif ($page <= 2 || $page > $lastPage - 2 || abs($page - $currentPage) <= 1)
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @elseif ($page == 3 && $currentPage > 5)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @elseif ($page == $currentPage + 2 && $page < $lastPage - 2)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item"><a class="page-link next-page-item" href="{{ $paginator->nextPageUrl() }}">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12L10 8L6 4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    </a></li>
                @else
                    <li class="page-item disabled"><span class="page-link next-page-item">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 12L10 8L6 4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                    </span></li>
                @endif
            </ul>
        </div>
    </nav>
@endif
