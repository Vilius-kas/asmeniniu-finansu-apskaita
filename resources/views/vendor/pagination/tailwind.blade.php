@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center mt-4">
        <ul class="inline-flex items-center space-x-1 rounded border border-gray-300 bg-white px-2 py-1 shadow-sm">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="px-3 py-1 text-gray-400 cursor-default">&laquo;</li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 hover:bg-gray-100 rounded">&laquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="px-3 py-1 text-gray-400">{{ $element }}</li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="px-3 py-1 bg-blue-500 text-white font-semibold rounded">{{ $page }}</li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="px-3 py-1 hover:bg-gray-100 rounded">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 hover:bg-gray-100 rounded">&raquo;</a>
                </li>
            @else
                <li class="px-3 py-1 text-gray-400 cursor-default">&raquo;</li>
            @endif
        </ul>
    </nav>
@endif
