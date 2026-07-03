@extends('layouts.app')

@section('title', 'Overzicht behandelingen')

@section('content')
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Behandelingen</li>
        </ol>
    </nav>

    <h1 class="h3 titel-kniploket mb-3">Overzicht behandelingen</h1>

    {{-- Filterkaart --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('behandelingen.index') }}">
                <div class="d-flex align-items-end gap-2 flex-wrap justify-content-end">
                    <div>
                        <label for="status" class="form-label mb-1">Status selecteren</label>
                        <select id="status" name="status" class="form-select">
                            <option value="Alle behandelingen" @selected($geselecteerdeStatus === 'Alle behandelingen')>Alle
                                behandelingen</option>
                            @foreach ($statusLabels as $statusWaarde => $statusLabel)
                                <option value="{{ $statusWaarde }}" @selected($geselecteerdeStatus == $statusWaarde)>
                                    {{ $statusLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger">Maak selectie</button>
                    <a href="{{ route('behandelingen.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabelkaart --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <p class="text-muted small mb-2">Gevonden behandelingen - {{ $bestellingen->total() }} behandeling(en)</p>

            {{ $bestellingen->links('pagination.kniploket') }}

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="tabel-header-kniploket">
                        <tr>
                            <th>Behandeling</th>
                            <th>Omschrijving</th>
                            <th>Duur (Min)</th>
                            <th>Datum Aangemaakt</th>
                            <th>Status</th>
                            <th>Prijs</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bestellingen as $behandeling)
                            <tr>
                                <td><strong>{{ $behandeling->BestelNummer }}</strong></td>
                                <td>{{ $behandeling->KlantNaam }}</td>
                                <td>{{ $behandeling->Relatienummer }} min</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($behandeling->Datum)->format('d-m-Y') }}</td>
                                <td>
                                    <span class="badge {{ $behandeling->Bestelstatus ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $statusLabels[$behandeling->Bestelstatus] ?? 'Onbekend' }}
                                    </span>
                                </td>
                                <td>EUR {{ number_format((float) $behandeling->Totaal, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('behandelingen.show', $behandeling->BestellingId) }}"
                                        class="btn btn-outline-primary btn-sm">Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Er zijn geen behandelingen bekend met deze status</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection