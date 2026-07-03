@extends('layouts.app')

@section('title', 'Productdetail')

@section('content')
    {{-- Wireframe-03: breadcrumb Home / Producten / Detail --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="flex gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Home</a></li>
            <li class="text-slate-500">/</li>
            <li><a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800">Producten</a></li>
            <li class="text-slate-500">/</li>
            <li class="text-slate-700" aria-current="page">Detail</li>
        </ol>
    </nav>

    {{-- Alleen het statische tekstgedeelte is rood; de productnaam is grijs (wireframe-03) --}}
    <h1 class="mb-4 text-2xl font-bold text-slate-900"><span class="text-kniploket-red">Productdetail</span> <span class="text-slate-500">{{ $product->Naam }}</span></h1>

    <div class="max-w-2xl">
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="p-5">
                <table class="w-full text-sm">
                    <tbody>
                        <tr class="border-b border-slate-200">
                            <th class="w-32 px-4 py-3 text-left font-semibold text-slate-900">Product</th>
                            <td class="px-4 py-3 text-slate-700">{{ $product->Naam }}</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-900">Merk</th>
                            <td class="px-4 py-3 text-slate-700">{{ $product->Merk }}</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-900">Omschrijving</th>
                            <td class="px-4 py-3 text-slate-700">{{ $product->Omschrijving }}</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-900">EAN-code</th>
                            <td class="px-4 py-3 text-slate-700">{{ $product->EANcode }}</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-900">Houdbaarheidsdatum</th>
                            <td class="px-4 py-3 text-slate-700">{{ \Illuminate\Support\Carbon::parse($product->Houdbaarheidsdatum)->format('d-m-Y') }}</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-900">Inkoopprijs</th>
                            <td class="px-4 py-3 text-slate-700">EUR {{ number_format((float) $product->InkoopPrijs, 2, ',', '.') }}</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-900">Verkoopprijs</th>
                            <td class="px-4 py-3 text-slate-700">EUR {{ number_format((float) $product->VerkoopPrijs, 2, ',', '.') }}</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-900">Aantal op voorraad</th>
                            <td class="px-4 py-3 text-slate-700">{{ $product->AantalOpVoorraad }}</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-900">Leverancier</th>
                            <td class="px-4 py-3 text-slate-700">{{ $product->LeverancierNaam ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-900">Postcode leverancier</th>
                            <td class="px-4 py-3 text-slate-700">{{ $product->LeverancierPostcode ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-900">Plaats leverancier</th>
                            <td class="px-4 py-3 text-slate-700">{{ $product->LeverancierPlaats ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-900">E-mail leverancier</th>
                            <td class="px-4 py-3 text-slate-700">{{ $product->LeverancierEmail ?? '-' }}</td>
                        </tr>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left font-semibold text-slate-900">Mobiel leverancier</th>
                            <td class="px-4 py-3 text-slate-700">{{ $product->LeverancierMobiel ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-900">Opmerking</th>
                            <td class="px-4 py-3 text-slate-700">{{ $product->Opmerking ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>

                {{-- Wireframe-03: knoppen rechts onder de gegevens --}}
                <div class="mt-5 flex justify-end gap-3">
                    <a href="{{ route('products.edit', $product->Id) }}" class="inline-flex items-center rounded bg-kniploket-red px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        Wijzigen
                    </a>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center rounded border border-blue-300 bg-white px-4 py-2 text-sm font-semibold text-blue-600 hover:bg-blue-50">
                        Terug
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if (session('succesmelding'))
        {{-- User Story 08: de succesmelding verdwijnt na 3 seconden --}}
        <script>
            setTimeout(function () {
                var melding = document.querySelector('.alert-success');

                if (melding) {
                    melding.remove();
                }
            }, 3000);
        </script>
    @endif
@endsection
