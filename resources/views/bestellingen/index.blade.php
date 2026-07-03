@extends('layouts.app')

@section('title', 'Overzicht bestellingen')

@section('content')
    {{-- Breadcrumb en titel staan bewust boven de witte kaarten, op de grijze pagina-achtergrond (wireframe-02) --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bestellingen</li>
        </ol>
    </nav>

    <h1 class="h3 titel-kniploket mb-3">Overzicht bestellingen</h1>

    {{-- Witte kaart met het statusfilter, rechts uitgelijnd (wireframe-02) --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('bestellingen.index') }}">
                <div class="d-flex align-items-end gap-2 flex-wrap justify-content-end">
                    <div>
                        <label for="status" class="form-label mb-1">Status selecteren</label>
                        {{-- De option-values zijn de exacte databasewaarden (bijv. Inverwerking);
                             alleen de zichtbare labels tonen de leesbare tekst (bijv. In verwerking). --}}
                        <select id="status" name="status" class="form-select">
                            <option value="Alle statussen" @selected($geselecteerdeStatus === 'Alle statussen')>Alle statussen</option>
                            @foreach ($statusLabels as $statusWaarde => $statusLabel)
                                <option value="{{ $statusWaarde }}" @selected($geselecteerdeStatus === $statusWaarde)>{{ $statusLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger">Maak selectie</button>
                    <a href="{{ route('bestellingen.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Witte kaart met teltekst, gecentreerde paginering en de tabel (wireframe-02) --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <p class="text-muted small mb-2">Gevonden bestellingen - {{ $bestellingen->total() }} bestelling(en)</p>

            {{ $bestellingen->links('pagination.kniploket') }}

            <div class="table-responsive">
                {{-- table-hover: lichtgrijze rij-achtergrond bij hover, alleen op tbody-rijen (wireframe-03);
                     Bootstraps hover-tint (rgba(0,0,0,.075) op wit) is exact de wireframe-kleur #ECECEC --}}
                <table class="table table-hover align-middle mb-0">
                    <thead class="tabel-header-kniploket">
                        <tr>
                            <th>Bestelnr.</th>
                            <th>Klant</th>
                            <th>Relatienr.</th>
                            <th>Datum</th>
                            <th>Tijd</th>
                            <th>Status</th>
                            <th>Producten</th>
                            <th>Totaal</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bestellingen as $bestelling)
                            <tr>
                                <td>{{ $bestelling->BestelNummer }}</td>
                                <td>{{ $bestelling->KlantNaam }}</td>
                                <td>{{ $bestelling->Relatienummer }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($bestelling->Datum)->format('d-m-Y') }}</td>
                                <td>{{ substr((string) $bestelling->Tijd, 0, 5) }}</td>
                                <td>{{ $statusLabels[$bestelling->Bestelstatus] ?? $bestelling->Bestelstatus }}</td>
                                <td>{{ $bestelling->AantalProducten }}</td>
                                <td>EUR {{ number_format((float) $bestelling->Totaal, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('bestellingen.show', $bestelling->BestellingId) }}" class="btn btn-outline-primary btn-sm">Producten</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">Er zijn geen bestellingen bekend met deze status</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
