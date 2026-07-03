@extends('layouts.app')

@section('title', 'Bestelproduct wijzigen')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bestellingen.index') }}">Bestellingen</a></li>
            <li class="breadcrumb-item active" aria-current="page">Producten</li>
        </ol>
    </nav>

    {{-- Alleen het statische tekstgedeelte is rood; de productnaam houdt de standaard donkere tekstkleur --}}
    <h1 class="h3 mb-3"><span class="titel-kniploket">Bestelproduct wijzigen</span> <span>{{ $bestelproduct->ProductNaam }}</span></h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('bestellingen.producten.update', ['bestellingId' => $bestelling->Id, 'id' => $bestelproduct->ProductPerBestellingId]) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Bestelnummer</label>
                        <input type="text" class="form-control bg-light" value="{{ $bestelproduct->BestelNummer }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bestelstatus</label>
                        {{-- Toon het leesbare statuslabel; de databasewaarde blijft intern ongewijzigd --}}
                        <input type="text" class="form-control bg-light" value="{{ $statusLabels[$bestelproduct->Bestelstatus] ?? $bestelproduct->Bestelstatus }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Klant</label>
                        <input type="text" class="form-control bg-light" value="{{ $bestelproduct->KlantNaam }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Relatienummer</label>
                        <input type="text" class="form-control bg-light" value="{{ $bestelproduct->Relatienummer }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Product</label>
                        <input type="text" class="form-control bg-light" value="{{ $bestelproduct->ProductNaam }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Categorie</label>
                        <input type="text" class="form-control bg-light" value="{{ $bestelproduct->CategorieNaam }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Merk</label>
                        <input type="text" class="form-control bg-light" value="{{ $bestelproduct->Merk }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Unitprijs</label>
                        <input type="text" class="form-control bg-light" value="EUR {{ number_format((float) $bestelproduct->UnitPrijs, 2, ',', '.') }}" readonly>
                    </div>
                    <div class="col-12">
                        <label for="aantal" class="form-label">Aantal *</label>
                        <input
                            type="number"
                            id="aantal"
                            name="aantal"
                            class="form-control @error('aantal') is-invalid @enderror"
                            value="{{ old('aantal', $bestelproduct->Aantal) }}"
                            min="1"
                            required
                        >
                        @error('aantal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <p class="mt-3 mb-4">Velden met een * zijn verplicht.</p>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger">Opslaan</button>
                    <a href="{{ route('bestellingen.show', $bestelling->Id) }}" class="btn btn-secondary">Terug</a>
                </div>
            </form>
        </div>
    </div>
@endsection