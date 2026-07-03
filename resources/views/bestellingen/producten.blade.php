@extends('layouts.app')

@section('title', 'Producten per bestelling')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bestellingen.index') }}">Bestellingen</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    <h1 class="h3 mb-3">Producten per bestelling {{ $bestelling->BestelNummer }}</h1>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="bg-danger text-white">
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
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('bestellingen.index') }}" class="btn btn-outline-secondary">Terug</a>
    </div>
@endsection