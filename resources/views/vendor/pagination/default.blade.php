@if ($paginator->hasPages())
    <nav>
        <ul>
            {{-- Flèche précédente --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span class="page-link">&lsaquo;</span></li>
            @else
                <li><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a></li>
            @endif

            {{-- Numéros de pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Flèche suivante --}}
            @if ($paginator->hasMorePages())
                <li><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a></li>
            @else
                <li class="disabled"><span class="page-link">&rsaquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
