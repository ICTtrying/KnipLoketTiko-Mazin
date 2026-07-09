@extends('layouts.app')

@section('title', 'Overzicht behandelingen')

@section('content')
    {{-- Wireframe-02: breadcrumb Home / Behandelingen --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="flex flex-wrap items-center gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-kniploket-red hover:underline">Home</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li class="text-slate-500" aria-current="page">Behandelingen</li>
        </ol>
    </nav>

    <h1 class="mb-3 text-2xl font-bold titel-kniploket">Overzicht behandelingen</h1>

    {{-- Wireframe-02/03: filterbalk in een whitecard boven de tabel --}}
    <div class="mb-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('behandelingen.index') }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end sm:justify-end sm:gap-2">
                <div class="w-full sm:w-auto">
                    <label for="behandeling" class="mb-1 block text-sm text-slate-700">Behandeling selecteren</label>
                    {{-- Client-side validatie: de select beperkt de invoer tot de geldige opties.
                         'Overig' matcht bewust op geen enkele behandeling (scenario 2). --}}
                    <select id="behandeling" name="behandeling" class="w-full rounded border border-slate-300 px-3 py-2 text-sm sm:w-auto @error('behandeling') border-red-500 @enderror">
                        <option value="Alle behandelingen" @selected($geselecteerdeBehandeling === 'Alle behandelingen')>Alle behandelingen</option>
                        @foreach ($behandelingNamen as $naam)
                            <option value="{{ $naam }}" @selected($geselecteerdeBehandeling === $naam)>{{ $naam }}</option>
                        @endforeach
                        <option value="Overig" @selected($geselecteerdeBehandeling === 'Overig')>Overig</option>
                    </select>
                    @error('behandeling')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center rounded bg-kniploket-danger px-4 py-2 text-sm font-medium text-white hover:bg-kniploket-danger-dark sm:flex-none">Maak selectie</button>
                    <a href="{{ route('behandelingen.index') }}" class="flex-1 inline-flex items-center justify-center rounded bg-kniploket-secondary px-4 py-2 text-sm font-medium text-white hover:bg-kniploket-secondary-dark sm:flex-none">Reset</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Wireframe-02: resultatenblok in een whitecard onder de filterbalk --}}
    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <p class="mb-2 text-sm text-slate-500">Gevonden behandelingen - {{ $behandelingCount }} behandeling(en)</p>

        {{-- Wireframe-02: paginering onder de telregel, boven de tabel (verborgen bij 0 resultaten) --}}
        @if ($behandelingCount > 0)
            <div class="mb-3">
                {{ $behandelingen->links('pagination.kniploket') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="tabel-header-kniploket text-white">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Soort</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Omschrijving</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Duur</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Prijs</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Aantal producten</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Actie</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($behandelingen as $behandeling)
                        <tr class="border-t border-slate-200 hover:bg-slate-50">
                            <td class="px-4 py-3">{{ $behandeling->Naam ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $behandeling->Omschrijving ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $behandeling->DuurMinuten ?? '-' }} min</td>
                            <td class="px-4 py-3">EUR {{ number_format((float) $behandeling->Prijs, 2, ',', '.' ?? '-') }}</td>
                            <td class="px-4 py-3">{{ $behandeling->AantalProducten ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('behandelingen.producten', $behandeling->BehandelingId) }}" class="inline-flex items-center justify-center rounded border border-blue-600 px-2.5 py-1 text-sm font-medium text-blue-600 hover:bg-blue-50">Producten</a>
                            </td>
                        </tr>
                    @empty
                        {{-- Wireframe-04: gecentreerde melding, tekst exact volgens de user story --}}
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-slate-500">Er zijn geen behandelingen bekent met deze naam</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
