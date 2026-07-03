@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <span class="badge text-bg-warning mb-2">Kapsalon applicatie</span>
            <h1 class="h3 mb-1">Eigenaar</h1>
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">Home</li>
                </ol>
            </nav>
            <p class="text-muted">Welkom bij Kniploket Tiko - hier regel je eenvoudig klanten, afspraken en planning voor de salon.</p>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                @php
                    /** @var array<string, array{omschrijving: string, url: string}> $modules Weergavelijst van de dashboardkaarten uit wireframe-01 */
                    $modules = [
                        'Accounts' => ['omschrijving' => 'Beheer gebruikersaccounts en roltoewijzigingen.', 'url' => '#'],
                        'Medewerkers' => ['omschrijving' => 'Overzicht van medewerkers en hun basisgegevens.', 'url' => '#'],
                        'Beschikbaarheid' => ['omschrijving' => 'Bekijk de beschikbaarheid van medewerkers per dag en tijd.', 'url' => '#'],
                        'Klanten' => ['omschrijving' => 'Bekijk en filter klantgegevens op postcode en contactinformatie.', 'url' => '#'],
                        'Afspraken' => ['omschrijving' => 'Plan, bekijk en beheer afspraken met status en tijd.', 'url' => '#'],
                        'Behandelingen' => ['omschrijving' => 'Overzicht van behandelingen, duur en prijsinformatie.', 'url' => '#'],
                        'Producten' => ['omschrijving' => 'Bekijk en beheer producten binnen het assortiment.', 'url' => '#'],
                        'Bestellingen' => ['omschrijving' => 'Bekijk en beheer klantbestellingen en bestelstatus.', 'url' => '#'],
                    ];
                @endphp

                @foreach ($modules as $naam => $module)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h2 class="h6">{{ $naam }}</h2>
                                <p class="small text-muted mb-3">{{ $module['omschrijving'] }}</p>
                                <a href="{{ $module['url'] }}" class="btn btn-outline-primary btn-sm">Openen</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
