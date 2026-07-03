@extends('layouts.app')

@section('title', 'Productdetail')

@section('content')
    {{-- Wireframe-04: breadcrumb Home / Behandelingen / Detail --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('behandelingen.index') }}">Behandelingen</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    {{-- Alleen het statische tekstgedeelte is rood; de productnaam is grijs --}}
    <h1 class="h3 mb-3"><span class="titel-kniploket">Productdetail</span> <span class="text-muted">{{ $product->Naam }}</span></h1>

    <div class="col-lg-6 px-0">
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table mb-3 align-middle">
                    <tbody>
                        <tr>
                            <th class="w-25 small">Product</th>
                            <td class="small">{{ $product->Naam }}</td>
                        </tr>
                        <tr>
                            <th class="small">Merk</th>
                            <td class="small">{{ $product->Merk }}</td>
                        </tr>
                        <tr>
                            <th class="small">Omschrijving</th>
                            <td class="small">{{ $product->Omschrijving }}</td>
                        </tr>
                        <tr>
                            <th class="small">EAN-code</th>
                            <td class="small">{{ $product->EANcode }}</td>
                        </tr>
                        <tr>
                            <th class="small">Houdbaarheidsdatum</th>
                            <td class="small">{{ \Illuminate\Support\Carbon::parse($product->Houdbaarheidsdatum)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th class="small">Inkoopprijs</th>
                            <td class="small">EUR {{ number_format((float) $product->InkoopPrijs, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="small">Verkoopprijs</th>
                            <td class="small">EUR {{ number_format((float) $product->VerkoopPrijs, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th class="small">Aantal op voorraad</th>
                            <td class="small">{{ $product->AantalOpVoorraad }}</td>
                        </tr>
                        <tr>
                            <th class="small">Leverancier</th>
                            <td class="small">{{ $product->LeverancierNaam }}</td>
                        </tr>
                        <tr>
                            <th class="small">Postcode leverancier</th>
                            <td class="small">{{ $product->LeverancierPostcode }}</td>
                        </tr>
                        <tr>
                            <th class="small">Plaats leverancier</th>
                            <td class="small">{{ $product->LeverancierPlaats }}</td>
                        </tr>
                        <tr>
                            <th class="small">E-mail leverancier</th>
                            <td class="small">{{ $product->LeverancierEmail }}</td>
                        </tr>
                        <tr>
                            <th class="small">Mobiel leverancier</th>
                            <td class="small">{{ $product->LeverancierMobiel }}</td>
                        </tr>
                        <tr>
                            <th class="small">Opmerking</th>
                            <td class="small">{{ $product->Opmerking }}</td>
                        </tr>
                    </tbody>
                </table>

                {{-- Wireframe-04: knoppen onder de gegevens --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('behandelingen.product.wijzigen', $product->ProductId) }}" class="btn btn-danger btn-sm">Wijzigen</a>
                    <a href="{{ route('behandelingen.index') }}" class="btn btn-outline-primary btn-sm">Terug</a>
                </div>
            </div>
        </div>
    </div>

    @if (session('succesmelding'))
        {{-- User Story 06: de succesmelding verdwijnt na 3 seconden --}}
        <script>
            setTimeout(function () {
                var melding = document.querySelector('.alert-success');

                if (melding) {
                    melding.remove();
                }
            }, 3000);
        </script>
    @endif
@endsection
