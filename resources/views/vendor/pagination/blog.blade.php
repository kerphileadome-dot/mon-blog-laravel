@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="blog-pagination">
        @if ($paginator->onFirstPage())
            <span class="page-btn disabled">← Précédent</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">← Précédent</a>
        @endif

        <div class="page-numbers">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="page-dots">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-num active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-num">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">Suivant →</a>
        @else
            <span class="page-btn disabled">Suivant →</span>
        @endif
    </nav>
@endif
