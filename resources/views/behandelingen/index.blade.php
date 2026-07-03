@extends('layouts.app')

@section('title', 'Overzicht behandelingen')

@section('content')
    {{-- Wireframe-02: breadcrumb Home / Behandelingen --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Behandelingen</li>
        </ol>
    </nav>

    <h1 class="h3 titel-kniploket mb-3">Overzicht behandelingen</h1>

    {{-- Wireframe-02/03: filterbalk in een whitecard boven de tabel --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('behandelingen.index') }}">
                <div class="d-flex align-items-end gap-2 flex-wrap justify-content-end">
                    <div>
                        <label for="behandeling" class="form-label mb-1">Behandeling selecteren</label>
                        {{-- Client-side validatie: de select beperkt de invoer tot de geldige opties.
                             'Overig' matcht bewust op geen enkele behandeling (scenario 2). --}}
                        <select id="behandeling" name="behandeling" class="form-select @error('behandeling') is-invalid @enderror">
                            <option value="Alle behandelingen" @selected($geselecteerdeBehandeling === 'Alle behandelingen')>Alle behandelingen</option>
                            @foreach ($behandelingNamen as $naam)
                                <option value="{{ $naam }}" @selected($geselecteerdeBehandeling === $naam)>{{ $naam }}</option>
                            @endforeach
                            <option value="Overig" @selected($geselecteerdeBehandeling === 'Overig')>Overig</option>
                        </select>
                        @error('behandeling')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-danger">Maak selectie</button>
                    <a href="{{ route('behandelingen.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Wireframe-02: resultatenblok in een whitecard onder de filterbalk --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <p class="text-muted small mb-2">Gevonden behandelingen - {{ $behandelingen->total() }} behandeling(en)</p>

            {{-- Wireframe-02: paginering onder de telregel, boven de tabel (verborgen bij 0 resultaten) --}}
            @if ($behandelingen->total() > 0)
                {{ $behandelingen->links('pagination.kniploket') }}
            @endif

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="tabel-header-kniploket">
                        <tr>
                            <th>Soort</th>
                            <th>Omschrijving</th>
                            <th>Duur</th>
                            <th>Prijs</th>
                            <th>Aantal producten</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($behandelingen as $behandeling)
                            <tr>
                                <td>{{ $behandeling->Naam }}</td>
                                <td>{{ $behandeling->Omschrijving }}</td>
                                <td>{{ $behandeling->DuurMinuten }} min</td>
                                <td>EUR {{ number_format((float) $behandeling->Prijs, 2, ',', '.') }}</td>
                                <td>{{ $behandeling->AantalProducten }}</td>
                                <td>
                                    <a href="{{ route('behandelingen.producten', $behandeling->BehandelingId) }}" class="btn btn-outline-primary btn-sm">Producten</a>
                                </td>
                            </tr>
                        @empty
                            {{-- Wireframe-04: gecentreerde melding, tekst exact volgens de user story --}}
                            <tr>
                                <td colspan="6" class="text-center py-4">{{ $legeMelding }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
