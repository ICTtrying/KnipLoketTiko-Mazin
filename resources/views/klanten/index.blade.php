@extends('layouts.app')

@section('title', 'Overzicht klanten')

@section('content')
    {{-- Breadcrumb en titel staan boven de witte kaarten op de pagina-achtergrond (Wireframe-02) --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="flex flex-wrap items-center gap-2 text-sm">
            <li><a href="{{ url('/') }}" class="text-kniploket-red hover:underline">Home</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li class="text-slate-500" aria-current="page">Klanten</li>
        </ol>
    </nav>

    <h1 class="mb-3 text-2xl font-bold titel-kniploket">Overzicht klanten</h1>

    {{-- Witte kaart met de zoekbalk, rechts uitgelijnd (Wireframe-02) --}}
    <div class="mb-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('klanten.index') }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end sm:justify-end sm:gap-2">
                <div class="w-full sm:w-auto sm:min-w-64">
                    <label for="postcode" class="mb-1 block text-sm text-slate-700">Postcode zoeken</label>
                    <input
                        type="text"
                        id="postcode"
                        name="postcode"
                        value="{{ $postcode ?? '' }}"
                        placeholder="Bijv. 3512AB"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm sm:w-auto"
                    />
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center rounded bg-kniploket-danger px-4 py-2 text-sm font-medium text-white hover:bg-kniploket-danger-dark sm:flex-none">Toon klanten</button>
                    <a href="{{ route('klanten.index') }}" class="flex-1 inline-flex items-center justify-center rounded bg-kniploket-secondary px-4 py-2 text-sm font-medium text-white hover:bg-kniploket-secondary-dark sm:flex-none">Reset</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Witte kaart met teltekst, gecentreerde paginering en de tabel (Wireframe-02) --}}
    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="p-4">
            {{-- Teltekst op een eigen regel --}}
            <p class="mb-2 text-sm text-slate-500">Gevonden klanten - {{ count($klanten ?? []) }} klant(en)</p>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead class="tabel-header-kniploket">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-white">Naam</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">Relatienummer</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">Adres</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">Postcode</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">Woonplaats</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">Mobiel</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">Contact e-mail</th>
                            <th class="px-4 py-3 text-center font-semibold text-white">Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(empty($klanten))
                            {{-- Wireframe-04: Header blijft, tabel toont foutmelding --}}
                            <tr>
                                <td colspan="8" class="px-4 py-4 text-center text-slate-500">
                                    {{ $message ?? 'Er zijn geen klanten bekent die de geselecteerde postcode hebben' }}
                                </td>
                            </tr>
                        @else
                            @foreach ($klanten as $klant)
                            <tr class="border-t border-slate-200 hover:bg-slate-50">
                                <td class="px-4 py-3">{{ $klant->Voornaam }} {{ $klant->Achternaam }}</td>
                                <td class="px-4 py-3">{{ $klant->Relatienummer }}</td>
                                <td class="px-4 py-3">{{ $klant->Straatnaam }} {{ $klant->Huisnummer }}</td>
                                <td class="px-4 py-3">{{ $klant->Postcode }}</td>
                                <td class="px-4 py-3">{{ $klant->Plaats }}</td>
                                <td class="px-4 py-3">{{ $klant->Mobiel }}</td>
                                <td class="px-4 py-3">{{ $klant->ContactEmail }}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('klanten.show', $klant->Id) }}" class="inline-flex items-center justify-center rounded border border-blue-600 px-3 py-1.5 text-sm font-medium text-blue-600 hover:bg-blue-50">Details</a>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection