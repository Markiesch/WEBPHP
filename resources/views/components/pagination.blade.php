{{-- resources/views/components/pagination.blade.php --}}
@if ($paginator->hasPages())
    <nav class="mt-4" aria-label="Pagination">

        <ul class="uk-pgn uk-pgn-default">
            {{--Previous--}}
            @if ($paginator->onFirstPage())
                <li class="uk-disabled">
                    <span data-uk-pgn-previous></span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <span data-uk-pgn-previous></span>
                    </a>
                </li>
            @endif


            @foreach ($paginator->links()->elements as $element)
                {{--Dots--}}
                @if (is_string($element))
                    <li class="uk-disabled"><span>{{ $element }}</span></li>
                @endif

                {{--Links--}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="uk-active"><span aria-current="page">{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <span data-uk-pgn-next></span>
                    </a>
                </li>
            @else
                <li class="uk-disabled">
                    <span data-uk-pgn-next></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
