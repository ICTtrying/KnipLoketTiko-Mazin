@extends('layouts.app')

@section('title', 'Producten per behandeling')

@section('content')
    {{-- Breadcrumb: Home / Behandelingen / Detail --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="flex flex-wrap items-center gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-kniploket-red hover:underline">Home</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li><a href="{{ route('behandelingen.index') }}" class="text-kniploket-red hover:underline">Behandelingen</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li class="text-slate-500" aria-current="page">Detail</li>
        </ol>
    </nav>

    {{-- Titel: Deels rood, deels grijs --}}
    <h1 class="mb-3 text-2xl font-bold">
        <span class="titel-kniploket">Producten per behandeling</span>
        <span class="font-normal text-slate-500">{{ $behandeling->Naam }}</span>
    </h1>

    {{-- Witte kaart om de tabel en knop heen --}}
    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="tabel-header-kniploket text-white">
                    <tr>
                        <th scope="col" class="px-4 py-2 text-left text-sm font-semibold">Product</th>
                        <th scope="col" class="px-4 py-2 text-left text-sm font-semibold">Merk</th>
                        <th scope="col" class="px-4 py-2 text-left text-sm font-semibold">Omschrijving</th>
                        <th scope="col" class="px-4 py-2 text-left text-sm font-semibold">EAN-code</th>
                        <th scope="col" class="px-4 py-2 text-left text-sm font-semibold">Aantal op voorraad</th>
                        <th scope="col" class="px-4 py-2 text-left text-sm font-semibold">Verkoopprijs</th>
                        <th scope="col" class="px-4 py-2 text-center text-sm font-semibold">Actie</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($producten as $product)
                        <tr class="border-t border-slate-200 hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold">{{ $product->Naam }}</td>
                            <td class="px-4 py-3">{{ $product->Merk }}</td>
                            <td class="px-4 py-3">{{ $product->Omschrijving }}</td>
                            <td class="px-4 py-3">{{ $product->EANcode }}</td>
                            <td class="px-4 py-3">{{ $product->AantalOpVoorraad }}</td>
                            <td class="px-4 py-3">EUR {{ number_format((float) $product->VerkoopPrijs, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('behandelingen.product.detail', $product->ProductId) }}"
                                    class="inline-flex items-center justify-center rounded bg-kniploket-danger px-4 py-1 text-sm font-medium text-white hover:bg-kniploket-danger-dark">Wijzigen</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Terug-knop rechts uitgelijnd --}}
        <div class="flex justify-end pt-4">
            <a href="{{ route('behandelingen.index') }}" class="inline-flex items-center justify-center rounded border border-blue-600 px-6 py-1 m-4 text-sm font-medium text-blue-600 hover:bg-blue-50">Terug</a>
        </div>
    </div>
@endsection