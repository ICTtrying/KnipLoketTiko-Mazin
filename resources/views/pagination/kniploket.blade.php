{{-- Eigen paginatie-view in Kniploket Tiko-huisstijl: geen "Showing X to Y of Z results",
     gecentreerde pill-knopjes, actieve pagina rood met witte tekst (zie wireframe-02). --}}
@if ($paginator->hasPages())
    <nav aria-label="Paginering" class="d-flex justify-content-center">
        <ul class="pagination pagination-kniploket">
            {{-- Vorige --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">&lsaquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Vorige">&lsaquo;</a></li>
            @endif

            {{-- Paginanummers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $pagina => $url)
                        @if ($pagina == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $pagina }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $pagina }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Volgende --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Volgende">&rsaquo;</a></li>
            @else
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">&rsaquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
