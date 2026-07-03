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

            .navbar-brand,
            .nav-link,
            .navbar-text {
                font-weight: 600;
            }

            .table thead.bg-danger th {
                color: #fff;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('home') }}">Kniploket Tiko</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Navigatie wisselen">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="#">Accounts</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Medewerkers</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Beschikbaarheid</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Klanten</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Afspraken</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Behandelingen</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Producten</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('bestellingen.index') }}">Bestellingen</a></li>
                    </ul>
                    <div class="d-flex align-items-center gap-3 text-white">
                        <span class="navbar-text">Salon Eigenaar (eigenaar)</span>
                        <a href="#" class="btn btn-outline-light btn-sm">Uitloggen</a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="container py-4">
            @if (session('succesmelding'))
                <div class="alert alert-success" role="alert">
                    {{ session('succesmelding') }}
                </div>
            @endif

            @if (session('foutmelding'))
                <div class="alert alert-danger" role="alert">
                    {{ session('foutmelding') }}
                </div>
            @endif

            @yield('content')
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>