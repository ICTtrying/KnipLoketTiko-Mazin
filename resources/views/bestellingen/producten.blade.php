@extends('layouts.app')

@section('title', 'Producten per bestelling')

@section('content')
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="flex flex-wrap items-center gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-kniploket-red hover:underline">Home</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li><a href="{{ route('bestellingen.index') }}" class="text-kniploket-red hover:underline">Bestellingen</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li class="text-slate-500" aria-current="page">Detail</li>
        </ol>
    </nav>

    {{-- Alleen het statische tekstgedeelte is rood; het bestelnummer houdt de standaard donkere tekstkleur (wireframe-04) --}}
    <h1 class="mb-3 text-2xl font-bold"><span class="text-kniploket-red">Producten per bestelling</span> <span class="text-slate-900">{{ $bestelling->BestelNummer }}</span></h1>

    {{-- Tabel en Terug-knop zitten samen in dezelfde witte kaart, knop direct onder de laatste rij (wireframe-04) --}}
    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        {{-- overflow-x-auto + min-w-full: de tabel scrolt netjes horizontaal binnen de kaart op smalle
             schermen in plaats van kolommen onleesbaar samen te persen; whitespace-nowrap per cel
             voorkomt lelijke midden-woord-afbrekingen. --}}
        <div class="overflow-x-auto">
            {{-- hover:bg-kniploket-hover-row op de rijen: lichtgrijze achtergrond bij hover, alleen op tbody-rijen --}}
            <table class="min-w-full border-collapse align-middle">
                <thead class="bg-kniploket-red text-white">
                    <tr>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Product</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Categorie</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Merk</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Aantal</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Prijs per stuk</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">BTW</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Korting</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Totaal (Kort. + BTW)</th>
                        <th class="px-3 py-2 text-left text-sm font-semibold whitespace-nowrap">Actie</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($producten as $product)
                        <tr class="border-b border-slate-200 last:border-0 hover:bg-kniploket-hover-row">
                            <td class="px-3 py-2 text-sm whitespace-nowrap">{{ $product->ProductNaam }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">{{ $product->CategorieNaam }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">{{ $product->Merk }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">{{ $product->Aantal }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">EUR {{ number_format((float) $product->UnitPrijs, 2, ',', '.') }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">{{ number_format((float) $product->BTWPercentage, 2, ',', '.') }}%</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">{{ number_format((float) $product->Korting, 2, ',', '.') }}%</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">EUR {{ number_format((float) $product->RegelTotaal, 2, ',', '.') }}</td>
                            <td class="px-3 py-2 text-sm whitespace-nowrap">
                                <a href="{{ route('bestellingen.producten.wijzigen', ['bestellingId' => $bestelling->Id, 'id' => $product->ProductPerBestellingId]) }}" class="inline-flex w-20 items-center justify-center rounded border border-kniploket-danger bg-kniploket-danger px-2.5 py-1 text-sm font-medium text-white hover:border-kniploket-danger-dark hover:bg-kniploket-danger-dark">Wijzigen</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-3 py-4 text-center text-sm text-slate-500">Er zijn geen producten bekend voor deze bestelling</td>
                        </tr>
                    @endforelse
                    {{-- Terug-knop als laatste tabelrij: cellen 1 t/m 8 leeg via colspan,
                         de knop in de negende cel zodat hij exact onder de Actie-kolom
                         met de Wijzigen-knopjes valt (wireframe-04/06/08) --}}
                    <tr>
                        <td colspan="8"></td>
                        <td class="px-3 py-2 whitespace-nowrap">
                            {{-- Zelfde w-20-vaste-breedte als de Wijzigen-knopjes zodat beide exact even groot zijn
                                 (anders krimpt "Terug" mee met zijn kortere tekst); blauwe outline, witte
                                 achtergrond, blauwe tekst — zelfde stijl als de Producten-knop --}}
                            <a href="{{ route('bestellingen.index') }}" class="inline-flex w-20 items-center justify-center rounded border border-blue-600 bg-white px-2.5 py-1 text-sm font-medium text-blue-600 hover:bg-blue-50">Terug</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
