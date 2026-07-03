@extends('layouts.app')

@section('title', 'Home')

@section('content')
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="p-4">
                <span class="mb-2 inline-block rounded bg-amber-400 px-2 py-1 text-xs font-bold text-slate-900">Kapsalon applicatie</span>
                <h1 class="mb-1 text-2xl font-semibold">Eigenaar</h1>
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="text-sm text-slate-500">
                        <li aria-current="page">Home</li>
                    </ol>
                </nav>
                <p class="text-slate-500">Welkom bij Kniploket Tiko - hier regel je eenvoudig klanten, afspraken en planning voor de salon.</p>

                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @php
                    /** @var array<string, array{omschrijving: string, url: string}> $modules Weergavelijst van de dashboardkaarten uit wireframe-01 */
                    $modules = [
                        'Accounts' => ['omschrijving' => 'Beheer gebruikersaccounts en roltoewijzigingen.', 'url' => route('accounts.index')],
                        'Medewerkers' => ['omschrijving' => 'Overzicht van medewerkers en hun basisgegevens.', 'url' => route('medewerkers.index')],
                        'Beschikbaarheid' => ['omschrijving' => 'Bekijk de beschikbaarheid van medewerkers per dag en tijd.', 'url' => route('beschikbaarheid.index')],
                        'Klanten' => ['omschrijving' => 'Bekijk en filter klantgegevens op postcode en contactinformatie.', 'url' => route('klanten.index')],
                        'Afspraken' => ['omschrijving' => 'Plan, bekijk en beheer afspraken met status en tijd.', 'url' => route('afspraken.index')],
                        'Behandelingen' => ['omschrijving' => 'Overzicht van behandelingen, duur en prijsinformatie.', 'url' => route('behandelingen.index')],
                        'Producten' => ['omschrijving' => 'Bekijk en beheer producten binnen het assortiment.', 'url' => route('products.index')],
                        'Bestellingen' => ['omschrijving' => 'Bekijk en beheer klantbestellingen en bestelstatus.', 'url' => route('bestellingen.index')],
                    ];
                    @endphp


                    @foreach ($modules as $naam => $module)
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <h2 class="text-base font-semibold">{{ $naam }}</h2>
                            <p class="mb-3 text-sm text-slate-500">{{ $module['omschrijving'] }}</p>
                            <a href="{{ $module['url'] }}" class="inline-flex items-center justify-center rounded border border-blue-600 px-2.5 py-1 text-sm font-medium text-blue-600 hover:bg-blue-50">Openen</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
@endsection
