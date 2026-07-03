@extends('layouts.app')

@section('title', 'Overzicht bestellingen')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bestellingen</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
        <h1 class="h3 mb-0">Overzicht bestellingen</h1>

        <form method="GET" action="{{ route('bestellingen.index') }}" class="ms-auto">
            <div class="d-flex align-items-end gap-2 flex-wrap justify-content-end">
                <div>
                    <label for="status" class="form-label mb-1">Status selecteren</label>
                    <select id="status" name="status" class="form-select">
                        @php
                            $statusOpties = ['Alle statussen', 'Ontvangen', 'Bevestigd', 'In verwerking', 'Verzonden', 'Afgeleverd', 'Geannuleerd'];
                        @endphp
                        @foreach ($statusOpties as $optie)
                            <option value="{{ $optie }}" @selected($geselecteerdeStatus === $optie)>{{ $optie }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-danger">Maak selectie</button>
                <a href="{{ route('bestellingen.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <p class="mb-3">Gevonden bestellingen - {{ $bestellingen->total() }} bestelling(en)</p>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="bg-danger text-white">
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
                        <td><a href="{{ route('bestellingen.show', $bestelling->BestellingId) }}">{{ $bestelling->KlantNaam }}</a></td>
                        <td>{{ $bestelling->Relatienummer }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($bestelling->Datum)->format('d-m-Y') }}</td>
                        <td>{{ substr((string) $bestelling->Tijd, 0, 8) }}</td>
                        <td>{{ $bestelling->Bestelstatus }}</td>
                        <td>{{ $bestelling->AantalProducten }}</td>
                        <td>EUR {{ number_format((float) $bestelling->Totaal, 2, ',', '.') }}</td>
                        <td>
                            @if ($bestelling->Bestelstatus === 'Afgeleverd')
                                <a href="{{ route('bestellingen.show', $bestelling->BestellingId) }}" class="btn btn-outline-primary btn-sm">Producten</a>
                            @else
                                <a href="{{ route('bestellingen.show', $bestelling->BestellingId) }}" class="btn btn-primary btn-sm">Producten</a>
                            @endif
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

    <div class="d-flex justify-content-end">
        {{ $bestellingen->links('pagination::bootstrap-5') }}
    </div>
@endsection