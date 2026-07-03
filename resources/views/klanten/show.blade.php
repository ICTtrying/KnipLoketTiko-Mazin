@extends('layouts.app')

@section('title', 'Klantdetail')

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('klanten.index') }}">Klanten</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    <h1 class="h3 titel-kniploket mb-3">
        <span>Klantdetail</span>
        <span class="text-muted ms-2" style="font-weight: normal;">{{ $klant->Voornaam }} {{ $klant->Achternaam }}</span>
    </h1>

    {{-- Data Grid (Wireframe-03) binnen een Bootstrap kaart --}}
    <div class="card shadow-sm" style="max-width: 800px;">
        <div class="card-body p-4">
            <div class="container-fluid p-0 small">
                <div class="row mb-2 pb-2 border-bottom border-light">
                    <div class="col-sm-4 fw-bold text-dark">Naam</div>
                    <div class="col-sm-8 text-muted">{{ $klant->Voornaam }} {{ $klant->Tussenvoegsel }} {{ $klant->Achternaam }}</div>
                </div>

                <div class="row mb-2 pb-2 border-bottom border-light">
                    <div class="col-sm-4 fw-bold text-dark">Relatienummer</div>
                    <div class="col-sm-8 text-muted">{{ $klant->Relatienummer }}</div>
                </div>

                <div class="row mb-2 pb-2 border-bottom border-light">
                    <div class="col-sm-4 fw-bold text-dark">Contact e-mail</div>
                    <div class="col-sm-8 text-muted">{{ $klant->ContactEmail }}</div>
                </div>

                <div class="row mb-2 pb-2 border-bottom border-light">
                    <div class="col-sm-4 fw-bold text-dark">Account e-mail</div>
                    <div class="col-sm-8 text-muted">{{ $klant->AccountEmail ?? '-' }}</div>
                </div>

                <div class="row mb-2 pb-2 border-bottom border-light">
                    <div class="col-sm-4 fw-bold text-dark">Straatnaam</div>
                    <div class="col-sm-8 text-muted">{{ $klant->Straatnaam }}</div>
                </div>

                <div class="row mb-2 pb-2 border-bottom border-light">
                    <div class="col-sm-4 fw-bold text-dark">Huisnummer</div>
                    <div class="col-sm-8 text-muted">{{ $klant->Huisnummer }}</div>
                </div>

                <div class="row mb-2 pb-2 border-bottom border-light">
                    <div class="col-sm-4 fw-bold text-dark">Toevoeging</div>
                    <div class="col-sm-8 text-muted">{{ $klant->Toevoeging ?? '-' }}</div>
                </div>

                <div class="row mb-2 pb-2 border-bottom border-light">
                    <div class="col-sm-4 fw-bold text-dark">Postcode</div>
                    <div class="col-sm-8 text-muted">{{ $klant->Postcode }}</div>
                </div>

                <div class="row mb-2 pb-2 border-bottom border-light">
                    <div class="col-sm-4 fw-bold text-dark">Plaats</div>
                    <div class="col-sm-8 text-muted">{{ $klant->Plaats }}</div>
                </div>

                <div class="row mb-2 pb-2 border-bottom border-light">
                    <div class="col-sm-4 fw-bold text-dark">Mobiel</div>
                    <div class="col-sm-8 text-muted">{{ $klant->Mobiel }}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold text-dark">Bijzonderheden</div>
                    <div class="col-sm-8 text-muted">{{ $klant->Bijzonderheden ?? '-' }}</div>
                </div>
            </div>

            {{-- Actieknop uitlijning --}}
            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('klanten.edit', $klant->Id) }}" class="btn btn-danger">Wijzigen</a>
                <a href="{{ route('klanten.index') }}" class="btn btn-outline-primary">Terug</a>
            </div>
        </div>
    </div>
@endsection