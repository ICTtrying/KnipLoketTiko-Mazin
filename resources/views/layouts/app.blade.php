<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', config('app.name', 'Kniploket Tiko'))</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background: #f5f7fb;
            }

            .navbar-kniploket {
                background: #c8102e;
            }

            .navbar-brand,
            .nav-link,
            .navbar-text {
                font-weight: 600;
            }

            /* Compacte navigatierij zoals de wireframe: kleine links met vaste tussenruimte,
               zodat alle items + gebruikersinfo + uitlogknop samen op één regel passen */
            .navbar-links-kniploket {
                column-gap: .1rem;
            }

            .navbar-links-kniploket .nav-link {
                font-size: .8rem;
                white-space: nowrap;
                padding: .25rem .3rem;
            }

            .navbar-kniploket .navbar-text {
                font-size: .75rem;
            }

            .navbar-kniploket .btn {
                font-size: .75rem;
            }

            /* Gebruikersnaam in de navbar: eigen, unieke class (bewust los van de
               Bootstrap-buttonclasses zodat styling nooit op knoppen elders kan lekken).
               Grijze tekst, transparante achtergrond (wireframe toont geen vlak), breekt nooit. */
            .navbar-kniploket .navbar-gebruikersbadge {
                color: #d0d0d0;
                background-color: transparent;
                white-space: nowrap;
            }

            /* Actieve navbar-link: paarsachtige chip, exact bemonsterd uit de
               wireframe-screenshots (#9D2B5F achter "Bestellingen") */
            .navbar-kniploket .nav-link.nav-link-actief {
                background-color: #9d2b5f;
                color: #fff;
                border-radius: .25rem;
            }

            /* Kniploket Tiko-huisstijl: rode titels en breadcrumb-links */
            .titel-kniploket {
                color: #c8102e;
                font-weight: 700;
            }

            .breadcrumb a {
                color: #c8102e;
                text-decoration: none;
            }

            /* Rode tabel-header met witte tekst (wireframes overzicht + producten) */
            .tabel-header-kniploket th {
                background: #c8102e;
                color: #fff;
            }

            /* Paginering: pill-knopjes, actieve pagina rood met witte tekst */
            .pagination-kniploket {
                gap: .35rem;
            }

            .pagination-kniploket .page-link {
                border-radius: .5rem;
                color: #1f2937;
                border: 1px solid #e5e7eb;
            }

            .pagination-kniploket .page-item.active .page-link {
                background: #c8102e;
                border-color: #c8102e;
                color: #fff;
            }
        </style>
    </head>
    <body class="d-flex flex-column min-vh-100">
        {{-- Geen collapse-/hamburgergedrag: de wireframe toont één vaste rij.
             Alles rechts van het logo (links + gebruikersinfo + uitlogknop) zit in
             ÉÉN niet-wrappende flex-rij (flex-nowrap), zodat er nooit iets afbreekt. --}}
        <nav class="navbar navbar-dark navbar-kniploket">
            {{-- Zelfde .container als de hoofdcontent, zodat navbar en content-kaarten
                 op elke schermbreedte exact dezelfde linker-/rechterrand delen (wireframes) --}}
            <div class="container d-flex justify-content-between align-items-center flex-nowrap">
                <a class="navbar-brand text-uppercase" href="{{ route('home') }}">Kniploket Tiko</a>
                <div class="d-flex align-items-center flex-nowrap">
                    @php
                        /** @var array<string, array{url: string, patroon: string}> $navigatieLinks Navigatielinks met routepatroon voor actieve-linkdetectie; modules zonder routes matchen nooit en zijn dus nooit actief */
                        $navigatieLinks = [
                            'Accounts' => ['url' => '#', 'patroon' => 'accounts.*'],
                            'Medewerkers' => ['url' => '#', 'patroon' => 'medewerkers.*'],
                            'Beschikbaarheid' => ['url' => '#', 'patroon' => 'beschikbaarheid.*'],
                            'Klanten' => ['url' => '#', 'patroon' => 'klanten.*'],
                            'Afspraken' => ['url' => '#', 'patroon' => 'afspraken.*'],
                            'Behandelingen' => ['url' => '#', 'patroon' => 'behandelingen.*'],
                            'Producten' => ['url' => '#', 'patroon' => 'producten.*'],
                            'Bestellingen' => ['url' => route('bestellingen.index'), 'patroon' => 'bestellingen.*'],
                        ];
                    @endphp
                    <ul class="navbar-nav d-flex flex-row flex-nowrap align-items-center mb-0 me-3 navbar-links-kniploket">
                        @foreach ($navigatieLinks as $linkLabel => $navigatieLink)
                            <li class="nav-item">
                                <a @class(['nav-link', 'nav-link-actief' => request()->routeIs($navigatieLink['patroon'])]) href="{{ $navigatieLink['url'] }}">{{ $linkLabel }}</a>
                            </li>
                        @endforeach
                    </ul>
                    {{-- Gebruikersnaam: grijze platte tekst zonder achtergrondvlak, eigen scoped class --}}
                    <span class="navbar-text navbar-gebruikersbadge me-2">Salon Eigenaar (eigenaar)</span>
                    <a href="#" class="btn btn-outline-light btn-sm text-nowrap">Uitloggen</a>
                </div>
            </div>
        </nav>

        <main class="container py-4 flex-grow-1">
            @if (session('succesmelding'))
                <div class="alert alert-success" role="alert">
                    {{ session('succesmelding') }}
                </div>
            @endif

            @if (session('foutmelding'))
                {{-- Alleen de algemene melding; veldspecifieke fouten staan bij het veld zelf --}}
                <div class="alert alert-danger" role="alert">
                    {{ session('foutmelding') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="text-center text-muted small py-3">
            &copy; 2026 Kniploket Tiko - Alle rechten voorbehouden
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
