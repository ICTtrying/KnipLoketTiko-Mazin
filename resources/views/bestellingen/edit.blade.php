@extends('layouts.app')

@section('title', 'Bestelproduct wijzigen')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="flex flex-wrap items-center gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-kniploket-red hover:underline">Home</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li><a href="{{ route('bestellingen.index') }}" class="text-kniploket-red hover:underline">Bestellingen</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li class="text-slate-500" aria-current="page">Producten</li>
        </ol>
    </nav>

    {{-- Alleen het statische tekstgedeelte is rood; de productnaam houdt de standaard donkere tekstkleur --}}
    <h1 class="mb-3 text-2xl font-bold"><span class="text-kniploket-red">Bestelproduct wijzigen</span> <span class="text-slate-900">{{ $bestelproduct->ProductNaam }}</span></h1>

    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <form method="POST" action="{{ route('bestellingen.producten.update', ['bestellingId' => $bestelling->Id, 'id' => $bestelproduct->ProductPerBestellingId]) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm text-slate-700">Bestelnummer</label>
                    <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-600" value="{{ $bestelproduct->BestelNummer }}" readonly>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700">Bestelstatus</label>
                    {{-- Toon het leesbare statuslabel; de databasewaarde blijft intern ongewijzigd --}}
                    <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-600" value="{{ $statusLabels[$bestelproduct->Bestelstatus] ?? $bestelproduct->Bestelstatus }}" readonly>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700">Klant</label>
                    <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-600" value="{{ $bestelproduct->KlantNaam }}" readonly>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700">Relatienummer</label>
                    <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-600" value="{{ $bestelproduct->Relatienummer }}" readonly>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700">Product</label>
                    <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-600" value="{{ $bestelproduct->ProductNaam }}" readonly>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700">Categorie</label>
                    <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-600" value="{{ $bestelproduct->CategorieNaam }}" readonly>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700">Merk</label>
                    <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-600" value="{{ $bestelproduct->Merk }}" readonly>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-slate-700">Unitprijs</label>
                    <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-600" value="EUR {{ number_format((float) $bestelproduct->UnitPrijs, 2, ',', '.') }}" readonly>
                </div>
                <div class="md:col-span-2">
                    <label for="aantal" class="mb-1 block text-sm text-slate-700">Aantal *</label>
                    <input
                        type="number"
                        id="aantal"
                        name="aantal"
                        @class([
                            'w-full rounded border px-3 py-2 text-sm',
                            'border-red-500 text-red-900 focus:border-red-500 focus:ring-1 focus:ring-red-500' => $errors->has('aantal'),
                            'border-slate-300 focus:border-kniploket-red focus:ring-1 focus:ring-kniploket-red' => ! $errors->has('aantal'),
                        ])
                        value="{{ old('aantal', $bestelproduct->Aantal) }}"
                        min="1"
                        required
                    >
                    @error('aantal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <p class="mt-3 mb-4 text-sm text-slate-500">Velden met een * zijn verplicht.</p>

            {{-- Onder sm: knoppen full-width en gestapeld voor makkelijk tikken; vanaf sm naast elkaar --}}
            <div class="flex flex-col gap-2 sm:flex-row">
                <button type="submit" class="inline-flex items-center justify-center rounded bg-kniploket-danger px-4 py-2 text-sm font-medium text-white hover:bg-kniploket-danger-dark">Opslaan</button>
                <a href="{{ route('bestellingen.show', $bestelling->Id) }}" class="inline-flex items-center justify-center rounded bg-kniploket-secondary px-4 py-2 text-sm font-medium text-white hover:bg-kniploket-secondary-dark">Terug</a>
            </div>
        </form>
    </div>
@endsection
