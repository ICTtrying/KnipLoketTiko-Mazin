@extends('layouts.app')

@section('title', 'Overzicht producten')

@section('content')
    {{-- Wireframe-02: breadcrumb Home / Producten --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Producten</li>
        </ol>
    </nav>

    <h1 class="h3 text-danger mb-3">Overzicht producten</h1>

    {{-- Wireframe-02/03: filterbalk in een whitecard boven de tabel --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}">
                <div class="d-flex align-items-end gap-2 flex-wrap">
                    <div>
                        <label for="categorie" class="form-label mb-1">Categorie selecteren</label>
                        {{-- Client-side validatie: de select beperkt de invoer tot de geldige categorieën --}}
                        <select id="categorie" name="categorie" class="form-select @error('categorie') is-invalid @enderror">
                            <option value="" @selected($geselecteerdeCategorieId === null)>Alle categorieën</option>
                            @foreach ($categorieen as $categorie)
                                <option value="{{ $categorie->Id }}" @selected($geselecteerdeCategorieId === (int) $categorie->Id)>{{ $categorie->Naam }}</option>
                            @endforeach
                        </select>
                        @error('categorie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-danger">Maak selectie</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Wireframe-02: resultatenblok in een whitecard onder de filterbalk --}}
    <div class="card">
        <div class="card-body">
            <p class="mb-3">Gevonden producten - {{ $producten->total() }} product(en)</p>

            {{-- Wireframe-02: paginering onder de teksregel, boven de tabel --}}
            {{ $producten->links('pagination::bootstrap-5') }}

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="bg-danger text-white">
                        <tr>
                            <th>Product</th>
                            <th>Categorie</th>
                            <th>Merk</th>
                            <th>EAN-code</th>
                            <th>Verkoopprijs</th>
                            <th>Voorraad</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($producten as $product)
                            <tr>
                                <td>{{ $product->Naam }}</td>
                                <td>{{ $product->CategorieNaam }}</td>
                                <td>{{ $product->Merk }}</td>
                                <td>{{ $product->EANcode }}</td>
                                <td>EUR {{ number_format((float) $product->VerkoopPrijs, 2, ',', '.') }}</td>
                                <td>{{ $product->AantalOpVoorraad }}</td>
                                <td>
                                    {{-- Placeholder: detailpagina volgt in een latere user story --}}
                                    <a href="#" class="btn btn-outline-primary btn-sm">Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
