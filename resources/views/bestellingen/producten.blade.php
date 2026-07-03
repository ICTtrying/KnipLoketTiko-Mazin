@extends('layouts.app')

@section('title', 'Producten per bestelling')

@section('content')
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bestellingen.index') }}">Bestellingen</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    {{-- Alleen het statische tekstgedeelte is rood; het bestelnummer houdt de standaard donkere tekstkleur (wireframe-04) --}}
    <h1 class="h3 mb-3"><span class="titel-kniploket">Producten per bestelling</span> <span>{{ $bestelling->BestelNummer }}</span></h1>

    {{-- Tabel en Terug-knop zitten samen in dezelfde witte kaart, knop direct onder de laatste rij (wireframe-04) --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="tabel-header-kniploket">
                        <tr>
                            <th>Product</th>
                            <th>Categorie</th>
                            <th>Merk</th>
                            <th>Aantal</th>
                            <th>Prijs per stuk</th>
                            <th>BTW</th>
                            <th>Korting</th>
                            <th>Totaal (Kort. + BTW)</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($producten as $product)
                            <tr>
                                <td>{{ $product->ProductNaam }}</td>
                                <td>{{ $product->CategorieNaam }}</td>
                                <td>{{ $product->Merk }}</td>
                                <td>{{ $product->Aantal }}</td>
                                <td>EUR {{ number_format((float) $product->UnitPrijs, 2, ',', '.') }}</td>
                                <td>{{ number_format((float) $product->BTWPercentage, 2, ',', '.') }}%</td>
                                <td>{{ number_format((float) $product->Korting, 2, ',', '.') }}%</td>
                                <td>EUR {{ number_format((float) $product->RegelTotaal, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('bestellingen.producten.wijzigen', ['bestellingId' => $bestelling->Id, 'id' => $product->ProductPerBestellingId]) }}" class="btn btn-danger btn-sm">Wijzigen</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">Er zijn geen producten bekend voor deze bestelling</td>
                            </tr>
                        @endforelse
                        {{-- Terug-knop als laatste tabelrij: cellen 1 t/m 8 leeg via colspan,
                             de knop in de negende cel zodat hij exact onder de Actie-kolom
                             met de Wijzigen-knopjes valt (wireframe-04/06/08) --}}
                        <tr>
                            <td colspan="8"></td>
                            <td>
                                <a href="{{ route('bestellingen.index') }}" class="btn btn-outline-primary btn-sm">Terug</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
