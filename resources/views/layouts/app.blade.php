<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', config('app.name', 'Kniploket Tiko'))</title>

        @vite(['resources/css/app.css'])
    </head>
    <body class="flex min-h-screen flex-col bg-kniploket-page-bg font-sans text-slate-900">
        {{-- Vanaf md (≥768px): exact de oorspronkelijke, wireframe-exacte vaste rij, nooit wrappend.
             Onder md: hamburgermenu dat inklapt/uitklapt via een verborgen checkbox (peer), zonder JS. --}}
        <nav class="bg-kniploket-red text-white">
            {{-- Zelfde binnenbreedte-classes als de hoofdcontent, zodat navbar en content-kaarten
                 op elke schermbreedte exact dezelfde linker-/rechterrand delen (wireframes) --}}
            <div class="mx-auto max-w-6xl px-4 py-3 md:flex md:flex-nowrap md:items-center md:justify-between md:gap-4">
                <input type="checkbox" id="mobiel-menu-toggle" class="peer hidden">

                {{-- md:contents: op desktop verliest deze rij zijn eigen flexbox-doos, zodat logo en
                     hamburger-knop rechtstreeks kinderen van de buitenste flex-rij worden --}}
                <div class="flex items-center justify-between gap-4 md:contents">
                    <a class="shrink-0 text-lg font-bold tracking-wide whitespace-nowrap uppercase" href="{{ route('home') }}">Kniploket Tiko</a>

                    {{-- Eén vast hamburgerpictogram: de checkbox zit te diep genest onder deze SVG's om via
                         een CSS-sibling-relatie (peer-checked) naar een kruisicoon te kunnen wisselen zonder
                         de structuur fragiel te maken; het in-/uitklappen van het menu zelf werkt wel via peer. --}}
                    <label for="mobiel-menu-toggle" class="cursor-pointer rounded p-1 hover:bg-white/10 md:hidden" aria-label="Menu openen of sluiten">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </label>
                </div>

                <div class="mt-3 hidden flex-col gap-3 peer-checked:flex md:mt-0 md:flex md:w-auto md:flex-row md:flex-nowrap md:items-center">
                    @php
                        /** @var array<string, array{url: string, patroon: string}> $navigatieLinks Navigatielinks met routepatroon voor actieve-linkdetectie; modules zonder routes matchen nooit en zijn dus nooit actief */
                        $navigatieLinks = [
                            'Accounts' => ['url' => '#', 'patroon' => 'accounts.*'],
                            'Medewerkers' => ['url' => '#', 'patroon' => 'medewerkers.*'],
                            'Beschikbaarheid' => ['url' => '#', 'patroon' => 'beschikbaarheid.*'],
                            'Klanten' => ['url' => route('klanten.index'), 'patroon' => 'klanten.*'],
                            'Afspraken' => ['url' => '#', 'patroon' => 'afspraken.*'],
                            'Behandelingen' => ['url' => route('behandelingen.index'), 'patroon' => 'behandelingen.*'],
                            'Producten' => ['url' => route('products.index'), 'patroon' => 'products.*'],
                            'Bestellingen' => ['url' => route('bestellingen.index'), 'patroon' => 'bestellingen.*'],
                        ];
                    @endphp
                    <ul class="flex flex-col gap-0.5 md:mr-3 md:flex-row md:flex-nowrap md:items-center">
                        @foreach ($navigatieLinks as $linkLabel => $navigatieLink)
                            <li>
                                {{-- Actieve link: paarsachtige chip (#9d2b5f), exact bemonsterd uit de wireframe-screenshots --}}
                                <a
                                    href="{{ $navigatieLink['url'] }}"
                                    @class([
                                        'block whitespace-nowrap rounded px-2 py-1.5 text-sm font-semibold md:px-1.5 md:py-1 md:text-[.8rem]',
                                        'bg-kniploket-purple text-white' => request()->routeIs($navigatieLink['patroon']),
                                        'text-white/90 hover:bg-white/10 hover:text-white' => ! request()->routeIs($navigatieLink['patroon']),
                                    ])
                                >{{ $linkLabel }}</a>
                            </li>
                        @endforeach
                    </ul>
                    {{-- Gebruikersnaam: grijze platte tekst zonder achtergrondvlak --}}
                    <span class="text-sm font-semibold whitespace-nowrap text-neutral-300 md:mr-2 md:text-[.75rem]">Salon Eigenaar (eigenaar)</span>
                    <a href="#" class="inline-block w-fit rounded border border-white px-2 py-1 text-sm font-semibold whitespace-nowrap hover:bg-white hover:text-kniploket-red md:text-[.75rem]">Uitloggen</a>
                </div>
            </div>
        </nav>

        <main class="mx-auto w-full max-w-6xl flex-grow px-4 pt-6 pb-4">
            @if (session('succesmelding'))
                <div class="mb-4 rounded border border-emerald-300 bg-emerald-100 px-4 py-3 text-emerald-900" role="alert">
                    {{ session('succesmelding') }}
                </div>
            @endif

            @if (session('foutmelding'))
                {{-- Alleen de algemene melding; veldspecifieke fouten staan bij het veld zelf --}}
                <div class="mb-4 rounded border border-red-300 bg-red-100 px-4 py-3 text-red-900" role="alert">
                    {{ session('foutmelding') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="py-3 text-center text-sm text-slate-500">
            &copy; 2026 Kniploket Tiko - Alle rechten voorbehouden
        </footer>

        {{-- Meldingen automatisch verwijderen na 3 seconden --}}
        <script>
            setTimeout(function () {
                var alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(function (alert) {
                    alert.remove();
                });
            }, 3000);
        </script>
    </body>
</html>
