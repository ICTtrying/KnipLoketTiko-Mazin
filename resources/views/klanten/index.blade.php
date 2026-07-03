@extends('layouts.app')

@section('title', 'Overzicht klanten')

@section('content')
    {{-- Succes- of foutmeldingen --}}
    @if (session('success'))
        <div class="alert alert-success shadow-sm mb-3" id="flash-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger shadow-sm mb-3">
            {{ session('error') }}
        </div>
    @endif

    {{-- Breadcrumb en titel staan boven de witte kaarten op de pagina-achtergrond (Wireframe-02) --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Klanten</li>
        </ol>
    </nav>

    <h1 class="h3 titel-kniploket mb-3">Overzicht klanten</h1>

    {{-- Witte kaart met de zoekbalk, rechts uitgelijnd (Wireframe-02) --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('klanten.index') }}">
                <div class="d-flex align-items-end gap-2 flex-wrap justify-content-end">
                    <div style="min-width: 250px;">
                        <label for="postcode" class="form-label mb-1">Postcode zoeken</label>
                        <input
                            type="text"
                            id="postcode"
                            name="postcode"
                            value="{{ $postcode ?? '' }}"
                            placeholder="Bijv. 3512AB"
                            class="form-control"
                        />
                    </div>
                    <button type="submit" class="btn btn-danger">Toon klanten</button>
                    <a href="{{ route('klanten.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Witte kaart met teltekst, gecentreerde paginering en de tabel (Wireframe-02) --}}
    <div class="card shadow-sm">
        <div class="card-body">
            {{-- Teltekst op een eigen regel --}}
            <p class="text-muted small mb-2">Gevonden klanten - {{ count($klanten ?? []) }} klant(en)</p>
            
            {{-- Gecentreerde paginering --}}
            <div class="d-flex justify-content-center mb-3">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><span class="page-link">‹</span></li>
                        <li class="page-item active"><span class="page-link bg-danger border-danger text-white fw-bold">1</span></li>
                        <li class="page-item"><a class="page-link text-secondary" href="#">2</a></li>
                        <li class="page-item disabled"><span class="page-link">›</span></li>
                    </ul>
                </nav>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="tabel-header-kniploket">
                        <tr>
                            <th>Naam</th>
                            <th>Relatienummer</th>
                            <th>Adres</th>
                            <th>Postcode</th>
                            <th>Woonplaats</th>
                            <th>Mobiel</th>
                            <th>Contact e-mail</th>
                            <th class="text-center">Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(empty($klanten))
                            {{-- Wireframe-04: Header blijft, tabel toont foutmelding --}}
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    {{ $message ?? 'Er zijn geen klanten bekent die de geselecteerde postcode hebben' }}
                                </td>
                            </tr>
                        @else
                            @foreach ($klanten as $klant)
                            <tr>
                                <td>{{ $klant->Voornaam }} {{ $klant->Achternaam }}</td>
                                <td>{{ $klant->Relatienummer }}</td>
                                <td>{{ $klant->Straatnaam }} {{ $klant->Huisnummer }}</td>
                                <td>{{ $klant->Postcode }}</td>
                                <td>{{ $klant->Plaats }}</td>
                                <td>{{ $klant->Mobiel }}</td>
                                <td>{{ $klant->ContactEmail }}</td>
                                <td class="text-center">
                                    <a href="{{ route('klanten.show', $klant->Id) }}" class="btn btn-outline-primary btn-sm">Details</a>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const flash = document.getElementById('flash-success');
            if (flash) {
                setTimeout(() => {
                    flash.style.opacity = '0';
                    flash.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => flash.remove(), 500);
                }, 3000);
            }
        });
    </script>
@endsection