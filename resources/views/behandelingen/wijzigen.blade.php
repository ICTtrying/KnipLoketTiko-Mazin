@extends('layouts.app')

@section('title', 'Product wijzigen')

@section('content')
    {{-- Wireframe-05: breadcrumb Home / Behandelingen / Wijzigen --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('behandelingen.index') }}">Behandelingen</a></li>
            <li class="breadcrumb-item active" aria-current="page">Wijzigen</li>
        </ol>
    </nav>

    {{-- Alleen het statische tekstgedeelte is rood; de productnaam is grijs --}}
    <h1 class="h3 mb-3"><span class="titel-kniploket">Product wijzigen</span> <span class="text-muted">{{ $product->Naam }}</span></h1>

    <div class="col-lg-7 px-0">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('behandelingen.product.opslaan', $product->ProductId) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">Product</label>
                            <input type="text" class="form-control bg-light" value="{{ $product->Naam }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Merk</label>
                            <input type="text" class="form-control bg-light" value="{{ $product->Merk }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Omschrijving</label>
                            <input type="text" class="form-control bg-light" value="{{ $product->Omschrijving }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">EAN-code</label>
                            <input type="text" class="form-control bg-light" value="{{ $product->EANcode }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Inkoopprijs</label>
                            <input type="text" class="form-control bg-light" value="EUR {{ number_format((float) $product->InkoopPrijs, 2, ',', '.') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Aantal op voorraad</label>
                            <input type="text" class="form-control bg-light" value="{{ $product->AantalOpVoorraad }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Huidige verkoopprijs</label>
                            <input type="text" class="form-control bg-light" value="EUR {{ number_format((float) $product->VerkoopPrijs, 2, ',', '.') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Leverancier</label>
                            <input type="text" class="form-control bg-light" value="{{ $product->LeverancierNaam }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Houdbaarheidsdatum</label>
                            <input type="text" class="form-control bg-light" value="{{ \Illuminate\Support\Carbon::parse($product->Houdbaarheidsdatum)->format('d-m-Y') }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Plaats leverancier</label>
                            <input type="text" class="form-control bg-light" value="{{ $product->LeverancierPlaats }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="nieuwe_verkoopprijs" class="form-label small">Nieuwe verkoopprijs <span class="text-danger">*</span></label>
                            {{-- Client-side validatie: verplicht numeriek veld (HTML5) plus een live
                                 30 procent-controle in JavaScript. De submit wordt bewust niet hard
                                 geblokkeerd, zodat de server-side melding uit de user story
                                 ("Gegevens niet bijgewerkt") bij opslaan ook verschijnt. --}}
                            <input
                                type="number"
                                id="nieuwe_verkoopprijs"
                                name="nieuwe_verkoopprijs"
                                class="form-control @error('nieuwe_verkoopprijs') is-invalid @enderror"
                                value="{{ old('nieuwe_verkoopprijs', number_format((float) $product->VerkoopPrijs, 2, '.', '')) }}"
                                step="0.01"
                                min="0.01"
                                data-minimum-prijs="{{ number_format((float) $product->InkoopPrijs * 1.30, 2, '.', '') }}"
                                required
                            >
                            @error('nieuwe_verkoopprijs')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="verkoopprijs-js-fout" class="text-danger small d-none">Verkoopprijs moet minimaal 30 procent boven de inkoopprijs liggen</div>
                            <div class="form-text small">Minimaal 30 procent boven de inkoopprijs.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="opmerking" class="form-label small">Opmerking</label>
                            <input
                                type="text"
                                id="opmerking"
                                name="opmerking"
                                class="form-control @error('opmerking') is-invalid @enderror"
                                value="{{ old('opmerking', $product->Opmerking) }}"
                                maxlength="255"
                            >
                            @error('opmerking')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <p class="small text-muted mt-3 mb-4">Velden met een <span class="text-danger">*</span> zijn verplicht.</p>

                    {{-- Wireframe-05: knoppen rechtsonder --}}
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-danger btn-sm">Opslaan</button>
                        <a href="{{ route('behandelingen.product.detail', $product->ProductId) }}" class="btn btn-secondary btn-sm">Terug</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Client-side 30 procent-controle: directe feedback tijdens het typen --}}
    <script>
        (function () {
            var prijsVeld = document.getElementById('nieuwe_verkoopprijs');
            var jsFoutmelding = document.getElementById('verkoopprijs-js-fout');
            var minimumPrijs = parseFloat(prijsVeld.dataset.minimumPrijs);

            prijsVeld.addEventListener('input', function () {
                var waarde = parseFloat(prijsVeld.value.replace(',', '.'));
                var ongeldig = !isNaN(waarde) && waarde < minimumPrijs;

                prijsVeld.classList.toggle('is-invalid', ongeldig);
                jsFoutmelding.classList.toggle('d-none', !ongeldig);
            });
        })();
    </script>
@endsection
