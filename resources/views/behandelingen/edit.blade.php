@extends('layouts.app')

@section('title', 'Product wijzigen')

@section('content')
    {{-- Wireframe-05: breadcrumb Home / Behandelingen / Wijzigen --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="flex flex-wrap items-center gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-kniploket-red hover:underline">Home</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li><a href="{{ route('behandelingen.index') }}" class="text-kniploket-red hover:underline">Behandelingen</a>
            </li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li class="text-slate-500" aria-current="page">Wijzigen</li>
        </ol>
    </nav>

    @if (session('error'))
        {{-- Alleen de algemene melding; veldspecifieke fouten staan bij het veld zelf --}}
        <div class="mb-4 rounded border border-red-300 w-163 bg-red-100 px-4 py-3 text-red-900" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Alleen het statische tekstgedeelte is rood; de productnaam is grijs --}}
    <h1 class="mb-3 text-2xl font-bold"><span class="titel-kniploket">Product wijzigen</span> <span
            class="font-normal text-slate-500">{{ $product->Naam }}</span></h1>

    <div class="lg:w-7/12">
        <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <form method="POST" action="{{ route('behandelingen.update', $product->ProductId) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-3 md:grid-cols-2 ">
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Product</label>
                        <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm"
                            value="{{ $product->Naam }}" readonly>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Merk</label>
                        <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm"
                            value="{{ $product->Merk }}" readonly>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Omschrijving</label>
                        <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm"
                            value="{{ $product->Omschrijving }}" readonly>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">EAN-code</label>
                        <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm"
                            value="{{ $product->EANcode }}" readonly>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Inkoopprijs</label>
                        <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm"
                            value="EUR {{ number_format((float) $product->InkoopPrijs, 2, ',', '.') }}" readonly>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Aantal op voorraad</label>
                        <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm"
                            value="{{ $product->AantalOpVoorraad }}" readonly>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Huidige verkoopprijs</label>
                        <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm"
                            value="EUR {{ number_format((float) $product->VerkoopPrijs, 2, ',', '.') }}" readonly>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Leverancier</label>
                        <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm"
                            value="{{ $product->LeverancierNaam }}" readonly>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Houdbaarheidsdatum</label>
                        <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm"
                            value="{{ \Illuminate\Support\Carbon::parse($product->Houdbaarheidsdatum)->format('d-m-Y') }}"
                            readonly>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Plaats leverancier</label>
                        <input type="text" class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm"
                            value="{{ $product->LeverancierPlaats }}" readonly>
                    </div>
                    <div>
                        <label for="nieuwe_verkoopprijs" class="mb-1 block text-sm font-semibold text-slate-700">Nieuwe
                            verkoopprijs <span class="text-kniploket-danger">*</span></label>
                        {{-- Client-side validatie: verplicht numeriek veld (HTML5) plus een live
                        30 procent-controle in JavaScript. De submit wordt bewust niet hard
                        geblokkeerd, zodat de server-side melding uit de user story
                        ("Gegevens niet bijgewerkt") bij opslaan ook verschijnt. --}}
                        <input type="number" id="nieuwe_verkoopprijs" name="nieuwe_verkoopprijs"
                            class="w-full rounded border border-slate-300 px-3 py-2 text-sm @error('nieuwe_verkoopprijs') border-red-500 @enderror"
                            value="{{ old('nieuwe_verkoopprijs', number_format((float) $product->VerkoopPrijs, 2, '.', '')) }}"
                            step="0.01" min="0.01"
                            data-minimum-prijs="{{ number_format((float) $product->InkoopPrijs * 1.30, 2, '.', '') }}"
                            required>
                        @error('nieuwe_verkoopprijs')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                        <div id="verkoopprijs-js-fout" class="mt-1 hidden text-sm text-kniploket-danger">Verkoopprijs moet
                            minimaal 30 procent boven de inkoopprijs liggen</div>
                        <div class="mt-1 text-sm text-slate-500">Minimaal 30 procent boven de inkoopprijs.</div>
                    </div>
                    <div>
                        <label for="opmerking" class="mb-1 block text-sm font-semibold text-slate-700">Opmerking</label>
                        <input type="text" id="opmerking" name="opmerking"
                            class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm @error('opmerking') border-red-500 @enderror"
                            value="{{ old('opmerking', $product->Opmerking) }}" maxlength="255" readonly>
                        @error('opmerking')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <p class="mb-4 mt-3 text-sm text-slate-500">Velden met een <span class="text-kniploket-danger">*</span> zijn
                    verplicht.</p>

                {{-- Wireframe-05: knoppen rechtsonder --}}
                <div class="flex justify-end gap-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded bg-kniploket-danger px-4 py-2 text-sm font-medium text-white hover:bg-kniploket-danger-dark">Opslaan</button>
                    <a href="{{ route('behandelingen.product.detail', $product->ProductId) }}"
                        class="inline-flex items-center justify-center rounded bg-kniploket-secondary px-4 py-2 text-sm font-medium text-white hover:bg-kniploket-secondary-dark">Terug</a>
                </div>
            </form>
        </div>
    </div>
@endsection