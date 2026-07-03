@extends('layouts.app')

@section('title', 'Producten per behandeling')

@section('content')
    {{-- Wireframe-03: breadcrumb Home / Behandelingen / Detail --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('behandelingen.index') }}">Behandelingen</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    {{-- Alleen het statische tekstgedeelte is rood; de behandelnaam is grijs --}}
    <h1 class="h3 mb-3"><span class="titel-kniploket">Producten per behandeling</span> <span class="text-muted">{{ $behandeling->Naam }}</span></h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-3">
                    <thead class="tabel-header-kniploket">
                        <tr>
                            <th>Product</th>
                            <th>Merk</th>
                            <th>Omschrijving</th>
                            <th>EAN-code</th>
                            <th>Aantal op voorraad</th>
                            <th>Verkoopprijs</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($producten as $product)
                            <tr>
                                <td>{{ $product->Naam }}</td>
                                <td>{{ $product->Merk }}</td>
                                <td>{{ $product->Omschrijving }}</td>
                                <td>{{ $product->EANcode }}</td>
                                <td>{{ $product->AantalOpVoorraad }}</td>
                                <td>EUR {{ number_format((float) $product->VerkoopPrijs, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('behandelingen.product.detail', $product->ProductId) }}" class="btn btn-danger btn-sm">Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Wireframe-03: terugknop naar het overzicht behandelingen --}}
            <a href="{{ route('behandelingen.index') }}" class="btn btn-outline-primary btn-sm">Terug</a>
        </div>
    </div>
@endsection
