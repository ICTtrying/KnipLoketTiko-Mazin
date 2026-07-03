@extends('layouts.app')

@section('title', 'Producten per behandeling')

@section('content')
    {{-- Breadcrumb: Home / Behandelingen / Detail --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('behandelingen.index') }}">Behandelingen</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    {{-- Titel: Deels rood, deels grijs --}}
    <h1 class="h3 mb-3">
        <span class="titel-kniploket fw-bold text-danger">Producten per behandeling</span>
        <span class="text-muted fw-normal">{{ $behandeling->Naam }}</span>
    </h1>

    {{-- Witte kaart om de tabel en knop heen --}}
    <div class="card shadow-sm border-0 rounded-1">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-3 custom-tabel-kniploket">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Merk</th>
                            <th scope="col">Omschrijving</th>
                            <th scope="col">EAN-code</th>
                            <th scope="col">Aantal op voorraad</th>
                            <th scope="col">Verkoopprijs</th>
                            <th scope="col">Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($producten as $product)
                            <tr>
                                <td class="fw-semibold">{{ $product->Naam }}</td>
                                <td>{{ $product->Merk }}</td>
                                <td>{{ $product->Omschrijving }}</td>
                                <td>{{ $product->EANcode }}</td>
                                <td>{{ $product->AantalOpVoorraad }}</td>
                                <td>EUR {{ number_format((float) $product->VerkoopPrijs, 2, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('behandelingen.product.detail', $product->ProductId) }}"
                                        class="btn btn-danger btn-sm px-4">Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Terug-knop rechts uitgelijnd --}}
            <div class="d-flex justify-content-end p-3">
                <a href="{{ route('behandelingen.index') }}" class="btn btn-outline-primary btn-sm px-4">Terug</a>
            </div>
        </div>
    </div>
@endsection