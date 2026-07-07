@extends('layouts.app')

@section('title', 'Product wijzigen')

@section('content-max-width', 'max-w-3xl')

@section('content')
    {{-- Wireframe-04: breadcrumb Home / Producten / Wijzigen --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="flex gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Home</a></li>
            <li class="text-slate-500">/</li>
            <li><a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800">Producten</a></li>
            <li class="text-slate-500">/</li>
            <li class="text-slate-700" aria-current="page">Wijzigen</li>
        </ol>
    </nav>

    {{-- Alleen het statische tekstgedeelte is rood; de productnaam is grijs (wireframe-04) --}}
    <h1 class="mb-4 text-2xl font-bold text-slate-900"><span class="text-kniploket-red">Product wijzigen</span> <span class="text-slate-500">{{ $product->Naam }}</span></h1>

    <div class="max-w-3xl">
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="p-5">
                <form method="POST" action="{{ route('products.update', $product->Id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Product</label>
                            <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-700" value="{{ $product->Naam }}" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Merk</label>
                            <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-700" value="{{ $product->Merk }}" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Omschrijving</label>
                            <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-700" value="{{ $product->Omschrijving }}" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">EAN-code</label>
                            <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-700" value="{{ $product->EANcode }}" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Inkoopprijs</label>
                            <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-700" value="EUR {{ number_format((float) $product->InkoopPrijs, 2, ',', '.') }}" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Aantal op voorraad</label>
                            <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-700" value="{{ $product->AantalOpVoorraad }}" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Huidige verkoopprijs</label>
                            <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-700" value="EUR {{ number_format((float) $product->VerkoopPrijs, 2, ',', '.') }}" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Leverancier</label>
                            <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-700" value="{{ $product->LeverancierNaam ?? '-' }}" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Houdbaarheidsdatum</label>
                            <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-700" value="{{ \Illuminate\Support\Carbon::parse($product->Houdbaarheidsdatum)->format('d-m-Y') }}" readonly>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Plaats leverancier</label>
                            <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-700" value="{{ $product->LeverancierPlaats ?? '-' }}" readonly>
                        </div>
                        <div>
                            <label for="nieuwe_houdbaarheidsdatum" class="mb-1 block text-sm font-medium text-slate-700">Nieuwe houdbaarheidsdatum <span class="text-red-600">*</span></label>
                            {{-- Client-side validatie: verplicht datumveld (HTML5); de 7-dagenregel wordt
                                 server-side gecontroleerd zodat de melding uit de user story verschijnt --}}
                            <input
                                type="date"
                                id="nieuwe_houdbaarheidsdatum"
                                name="nieuwe_houdbaarheidsdatum"
                                class="w-full rounded border px-3 py-2 text-sm @error('nieuwe_houdbaarheidsdatum') border-red-500 bg-red-50 @else border-slate-300 @enderror"
                                value="{{ old('nieuwe_houdbaarheidsdatum', \Illuminate\Support\Carbon::parse($product->Houdbaarheidsdatum)->format('Y-m-d')) }}"
                                required
                            >
                            @error('nieuwe_houdbaarheidsdatum')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                            <div class="mt-1 text-xs text-slate-600">De houdbaarheidsdatum mag uiterlijk met 7 dagen worden verlengd.</div>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Opmerking</label>
                            <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-700" value="{{ $product->Opmerking ?? '-' }}" readonly>
                        </div>
                    </div>

                    <p class="mt-4 mb-4 text-xs text-slate-600">Velden met een <span class="text-red-600">*</span> zijn verplicht.</p>

                    {{-- Wireframe-04: knoppen rechtsonder --}}
                    <div class="flex justify-end gap-3">
                        <button type="submit" class="inline-flex items-center rounded bg-kniploket-red px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                            Opslaan
                        </button>
                        <a href="{{ route('products.show', $product->Id) }}" class="inline-flex items-center rounded border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Terug
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
