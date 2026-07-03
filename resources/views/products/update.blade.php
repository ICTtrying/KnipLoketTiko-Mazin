@extends('layouts.app')

@section('title', 'Product wijzigen')

@section('content')
    {{-- Wireframe-04: breadcrumb Home / Producten / Wijzigen --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Producten</a></li>
            <li class="breadcrumb-item active" aria-current="page">Wijzigen</li>
        </ol>
    </nav>

    {{-- Alleen het statische tekstgedeelte is rood; de productnaam is grijs (wireframe-04) --}}
    <h1 class="h3 mb-3"><span class="titel-kniploket">Product wijzigen</span> <span class="text-muted">{{ $product->Naam }}</span></h1>

    <div class="col-lg-7 px-0">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('products.update', $product->Id) }}">
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
                            <label for="nieuwe_houdbaarheidsdatum" class="form-label small">Nieuwe houdbaarheidsdatum <span class="text-danger">*</span></label>
                            {{-- Client-side validatie: verplicht datumveld (HTML5); de 7-dagenregel wordt
                                 server-side gecontroleerd zodat de melding uit de user story verschijnt --}}
                            <input
                                type="date"
                                id="nieuwe_houdbaarheidsdatum"
                                name="nieuwe_houdbaarheidsdatum"
                                class="form-control @error('nieuwe_houdbaarheidsdatum') is-invalid @enderror"
                                value="{{ old('nieuwe_houdbaarheidsdatum', \Illuminate\Support\Carbon::parse($product->Houdbaarheidsdatum)->format('Y-m-d')) }}"
                                required
                            >
                            @error('nieuwe_houdbaarheidsdatum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text small">De houdbaarheidsdatum mag uiterlijk met 7 dagen worden verlengd.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Opmerking</label>
                            <input type="text" class="form-control bg-light" value="{{ $product->Opmerking }}" readonly>
                        </div>
                    </div>

                    <p class="small text-muted mt-3 mb-4">Velden met een <span class="text-danger">*</span> zijn verplicht.</p>

                    {{-- Wireframe-04: knoppen rechtsonder --}}
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-danger btn-sm">Opslaan</button>
                        <a href="{{ route('products.show', $product->Id) }}" class="btn btn-secondary btn-sm">Terug</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
