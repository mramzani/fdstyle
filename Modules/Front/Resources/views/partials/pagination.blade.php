@if ($paginator->hasPages())
    <div class="pagination">
        @if ($paginator->onFirstPage())
            <a href="#" class="prev d-none"><i class="far fa-arrow-from-left"></i></a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="prev"><i class="far fa-arrow-from-left"></i></a>
        @endif
        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <a href="{{ $url }}" class="d-none">{{ $page }}</a>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a href="{{ $url }}" class="current">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach


        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="next"><i class="far fa-arrow-to-left"></i></a>
        @else
            <a href="#" class="next d-none"><i class="far fa-arrow-to-left"></i></a>
        @endif
    </div>
@endif
