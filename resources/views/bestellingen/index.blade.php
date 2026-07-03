@extends('layouts.app')

@section('title', 'Overzicht bestellingen')

@section('content')
    {{-- Breadcrumb en titel staan bewust boven de witte kaarten, op de grijze pagina-achtergrond (wireframe-02) --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="flex flex-wrap items-center gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-kniploket-red hover:underline">Home</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li class="text-slate-500" aria-current="page">Bestellingen</li>
        </ol>
    </nav>

    <h1 class="mb-3 text-2xl font-bold text-kniploket-red">Overzicht bestellingen</h1>

    {{-- Witte kaart met het statusfilter, rechts uitgelijnd (wireframe-02) --}}
    <div class="mb-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        {{-- Onder sm (<640px): filter stapelt verticaal en velden zijn full-width voor makkelijk tikken.
             Vanaf sm: terug naar de oorspronkelijke rechts-uitgelijnde rij (wireframe-02). --}}
        <form method="GET" action="{{ route('bestellingen.index') }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end sm:justify-end sm:gap-2">
                <div class="w-full sm:w-auto">
                    <label for="status" class="mb-1 block text-sm text-slate-700">Status selecteren</label>
                    {{-- De option-values zijn de exacte databasewaarden (bijv. Inverwerking);
                         alleen de zichtbare labels tonen de leesbare tekst (bijv. In verwerking). --}}
                    <select id="status" name="status" class="w-full rounded border border-slate-300 px-3 py-2 text-sm sm:w-auto">
                        <option value="Alle statussen" @selected($geselecteerdeStatus === 'Alle statussen')>Alle statussen</option>
                        @foreach ($statusLabels as $statusWaarde => $statusLabel)
                            <option value="{{ $statusWaarde }}" @selected($geselecteerdeStatus === $statusWaarde)>{{ $statusLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center rounded bg-kniploket-danger px-4 py-2 text-sm font-medium text-white hover:bg-kniploket-danger-dark sm:flex-none">Maak selectie</button>
                    <a href="{{ route('bestellingen.index') }}" class="flex-1 inline-flex items-center justify-center rounded bg-kniploket-secondary px-4 py-2 text-sm font-medium text-white hover:bg-kniploket-secondary-dark sm:flex-none">Reset</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Witte kaart met teltekst, gecentreerde paginering en de tabel (wireframe-02) --}}
    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <p class="mb-2 text-sm text-slate-500">Gevonden bestellingen - {{ $bestellingen->total() }} bestelling(en)</p>

        <div class="mb-3">
            {{ $bestellingen->links('pagination.kniploket') }}
        </div>

        {{-- overflow-x-auto + min-w-full: de tabel scrolt netjes horizontaal binnen de kaart op smalle
             schermen in plaats van kolommen onleesbaar samen te persen; whitespace-nowrap per cel
             voorkomt lelijke midden-woord-afbrekingen (bijv. "Marieke van den Berg"). --}}
        <div class="overflow-x-auto">
            {{-- hover:bg-kniploket-hover-row op de rijen: lichtgrijze achtergrond bij hover, alleen op tbody-rijen (wireframe-03) --}}
            <table class="min-w-full border-collapse align-middle">
                <thead class="bg-kniploket-red text-white">
                    <tr>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Bestelnr.</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Klant</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Relatienr.</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Datum</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Tijd</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Status</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Producten</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Totaal</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Actie</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bestellingen as $bestelling)
                        <tr class="border-b border-slate-200 last:border-0 hover:bg-kniploket-hover-row">
                            <td class="px-3 py-2 text-sm whitespace-nowrap">{{ $bestelling->BestelNummer }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">{{ $bestelling->KlantNaam }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">{{ $bestelling->Relatienummer }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">{{ \Illuminate\Support\Carbon::parse($bestelling->Datum)->format('d-m-Y') }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">{{ substr((string) $bestelling->Tijd, 0, 5) }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">{{ $statusLabels[$bestelling->Bestelstatus] ?? $bestelling->Bestelstatus }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">{{ $bestelling->AantalProducten }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">EUR {{ number_format((float) $bestelling->Totaal, 2, ',', '.') }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">
                                <a href="{{ route('bestellingen.show', $bestelling->BestellingId) }}" class="inline-flex items-center justify-center rounded border border-blue-600 px-2.5 py-1 text-sm font-medium text-blue-600 hover:bg-blue-50">Producten</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-3 py-4 text-center text-sm text-slate-500">Er zijn geen bestellingen bekend met deze status</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
