@extends('layouts.app')

@section('title', 'Overzicht producten')

@section('content')
    {{-- Wireframe-02: breadcrumb Home / Producten --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="flex gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Home</a></li>
            <li class="text-slate-500">/</li>
            <li class="text-slate-700" aria-current="page">Producten</li>
        </ol>
    </nav>

    <h1 class="mb-4 text-2xl font-bold text-slate-900">Overzicht producten</h1>

    {{-- Wireframe-02/03: filterbalk in een witte kaart boven de tabel --}}
    <div class="mb-4 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('products.index') }}">
            {{-- Wireframe-02: filter rechts uitgelijnd in de witte kaart --}}
            <div class="flex flex-wrap items-end justify-end gap-3">
                <div>
                    <label for="categorie" class="mb-1 block text-sm font-medium text-slate-700">Categorie selecteren</label>
                    {{-- Client-side validatie: de select beperkt de invoer tot de geldige categorieën --}}
                    <select id="categorie" name="categorie" class="rounded border border-slate-300 bg-white px-3 py-2 text-sm @error('categorie') border-red-500 @enderror">
                        <option value="" @selected($geselecteerdeCategorieId === null)>Alle categorieën</option>
                        @foreach ($categorieen as $categorie)
                            <option value="{{ $categorie->Id }}" @selected($geselecteerdeCategorieId === (int) $categorie->Id)>{{ $categorie->Naam }}</option>
                        @endforeach
                    </select>
                    @error('categorie')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="inline-flex items-center rounded bg-kniploket-red px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                    Maak selectie
                </button>
                <a href="{{ route('products.index') }}" class="inline-flex items-center rounded border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Wireframe-02: resultatenblok in een witte kaart onder de filterbalk --}}
    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="p-4">
            <p class="mb-3 text-sm text-slate-600">Gevonden producten - {{ $producten->total() }} product(en)</p>

            {{-- Wireframe-02: paginering onder de teksregel, boven de tabel (verborgen bij 0 resultaten) --}}
            @if ($producten->total() > 0)
                {{ $producten->links('pagination.kniploket') }}
            @endif

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead class="bg-kniploket-danger">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-white">Product</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">Categorie</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">Merk</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">EAN-code</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">Verkoopprijs</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">Voorraad</th>
                            <th class="px-4 py-3 text-left font-semibold text-white">Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($producten as $product)
                            <tr class="border-b border-slate-200 hover:bg-slate-50">
                                <td class="px-4 py-3">{{ $product->Naam }}</td>
                                <td class="px-4 py-3">{{ $product->CategorieNaam }}</td>
                                <td class="px-4 py-3">{{ $product->Merk }}</td>
                                <td class="px-4 py-3">{{ $product->EANcode }}</td>
                                <td class="px-4 py-3">EUR {{ number_format((float) $product->VerkoopPrijs, 2, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ $product->AantalOpVoorraad }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('products.show', $product->Id) }}" class="inline-flex items-center rounded border border-blue-300 bg-white px-3 py-1.5 text-sm font-medium text-blue-600 hover:bg-blue-50">
                                        Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            {{-- Wireframe-04: gecentreerde melding wanneer het filter geen producten oplevert --}}
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-600">{{ $legeMelding }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
