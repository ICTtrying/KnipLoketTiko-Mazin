{{-- Eigen paginatie-view in Kniploket Tiko-huisstijl: geen "Showing X to Y of Z results",
     gecentreerde pill-knopjes, actieve pagina rood met witte tekst (zie wireframe-02). --}}
@php
    $pillBase = 'flex h-9 min-w-9 items-center justify-center rounded-lg border px-3 text-sm';
@endphp
@if ($paginator->hasPages())
    <nav aria-label="Paginering" class="flex justify-center">
        <ul class="flex flex-wrap items-center justify-center gap-1.5">
            {{-- Vorige --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true"><span class="{{ $pillBase }} border-slate-200 text-slate-300">&lsaquo;</span></li>
            @else
                <li><a class="{{ $pillBase }} border-slate-200 text-slate-700 hover:bg-slate-100" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Vorige">&lsaquo;</a></li>
            @endif

            {{-- Paginanummers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li aria-disabled="true"><span class="{{ $pillBase }} border-slate-200 text-slate-400">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $pagina => $url)
                        @if ($pagina == $paginator->currentPage())
                            <li aria-current="page"><span class="{{ $pillBase }} border-kniploket-red bg-kniploket-red font-semibold text-white">{{ $pagina }}</span></li>
                        @else
                            <li><a class="{{ $pillBase }} border-slate-200 text-slate-700 hover:bg-slate-100" href="{{ $url }}">{{ $pagina }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Volgende --}}
            @if ($paginator->hasMorePages())
                <li><a class="{{ $pillBase }} border-slate-200 text-slate-700 hover:bg-slate-100" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Volgende">&rsaquo;</a></li>
            @else
                <li aria-disabled="true"><span class="{{ $pillBase }} border-slate-200 text-slate-300">&rsaquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
