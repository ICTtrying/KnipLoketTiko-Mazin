@extends('layouts.app')

@section('title', 'Productdetail')

@section('content')
    {{-- Wireframe-04: breadcrumb Home / Behandelingen / Detail --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="flex flex-wrap items-center gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-kniploket-red hover:underline">Home</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li><a href="{{ route('behandelingen.index') }}" class="text-kniploket-red hover:underline">Behandelingen</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li class="text-slate-500" aria-current="page">Detail</li>
        </ol>
    </nav>

    {{-- Alleen het statische tekstgedeelte is rood; de productnaam is grijs --}}
    <h1 class="mb-3 text-2xl font-bold"><span class="titel-kniploket">Productdetail</span> <span class="font-normal text-slate-500">{{ $product->Naam }}</span></h1>

    <div class="lg:w-1/2">
        <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">Product</th>
                            <td class="px-4 py-3 text-sm">{{ $product->Naam }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">Merk</th>
                            <td class="px-4 py-3 text-sm">{{ $product->Merk }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">Omschrijving</th>
                            <td class="px-4 py-3 text-sm">{{ $product->Omschrijving }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">EAN-code</th>
                            <td class="px-4 py-3 text-sm">{{ $product->EANcode }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">Houdbaarheidsdatum</th>
                            <td class="px-4 py-3 text-sm">{{ \Illuminate\Support\Carbon::parse($product->Houdbaarheidsdatum)->format('d-m-Y') }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">Inkoopprijs</th>
                            <td class="px-4 py-3 text-sm">EUR {{ number_format((float) $product->InkoopPrijs, 2, ',', '.') }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">Verkoopprijs</th>
                            <td class="px-4 py-3 text-sm">EUR {{ number_format((float) $product->VerkoopPrijs, 2, ',', '.') }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">Aantal op voorraad</th>
                            <td class="px-4 py-3 text-sm">{{ $product->AantalOpVoorraad }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">Leverancier</th>
                            <td class="px-4 py-3 text-sm">{{ $product->LeverancierNaam }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">Postcode leverancier</th>
                            <td class="px-4 py-3 text-sm">{{ $product->LeverancierPostcode }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">Plaats leverancier</th>
                            <td class="px-4 py-3 text-sm">{{ $product->LeverancierPlaats }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">E-mail leverancier</th>
                            <td class="px-4 py-3 text-sm">{{ $product->LeverancierEmail }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">Mobiel leverancier</th>
                            <td class="px-4 py-3 text-sm">{{ $product->LeverancierMobiel }}</td>
                        </tr>
                        <tr class="border-t border-slate-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold">Opmerking</th>
                            <td class="px-4 py-3 text-sm">{{ $product->Opmerking }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Wireframe-04: knoppen onder de gegevens --}}
            <div class="flex justify-end gap-2 pt-4">
                <a href="{{ route('behandelingen.product.wijzigen', $product->ProductId) }}" class="inline-flex items-center justify-center rounded bg-kniploket-danger px-4 py-2 text-sm font-medium text-white hover:bg-kniploket-danger-dark">Wijzigen</a>
                <a href="{{ route('behandelingen.index') }}" class="inline-flex items-center justify-center rounded border border-blue-600 px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50">Terug</a>
            </div>
        </div>
    </div>
@endsection
