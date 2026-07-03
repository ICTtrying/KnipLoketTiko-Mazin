@extends('layouts.app')

@section('title', 'Klant wijzigen')

@section('content')
    {{-- Foutmelding voor ontbrekende ContactId (Debug) --}}
    @if(!isset($klant->ContactId))
        <div class="alert alert-warning shadow-sm mb-3">
            <strong>Let op:</strong> De <code>ContactId</code> ontbreekt in de data. De database zal niets updaten. Controleer je <code>sp_get_klant_by_id</code> stored procedure!
        </div>
    @endif

    {{-- Wireframe-06: foutmelding bovenaan pagina als strakke balk --}}
    @if ($errors->any() || session('error'))
        <div class="alert alert-danger shadow-sm mb-3">
            {{ session('error') ?? 'Klantgegevens zijn niet bijgewerkt.' }}
        </div>
    @endif

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('klanten.index') }}">Klanten</a></li>
            <li class="breadcrumb-item active" aria-current="page">Wijzigen</li>
        </ol>
    </nav>

    <h1 class="h3 titel-kniploket mb-3">
        <span>Klant wijzigen</span>
        <span class="text-muted ms-2" style="font-weight: normal;">{{ $klant->Voornaam }} {{ $klant->Achternaam }}</span>
    </h1>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('klanten.update', $klant->Id) }}" method="POST">
                @csrf
                @method('PUT')
                
                {{-- Fallback: als ContactId mist, probeer Id (voor het geval je de query anders hebt opgebouwd) --}}
                <input type="hidden" name="contact_id" value="{{ $klant->ContactId ?? $klant->Id }}">

                <div class="row g-3 mb-3">
                    
                    {{-- BEWERKBAAR: Naam --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Naam <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-5">
                                <input type="text" name="voornaam" value="{{ old('voornaam', $klant->Voornaam) }}" class="form-control" placeholder="Voornaam" required>
                            </div>
                            <div class="col-3">
                                <input type="text" name="tussenvoegsel" value="{{ old('tussenvoegsel', $klant->Tussenvoegsel) }}" class="form-control" placeholder="Tussenv.">
                            </div>
                            <div class="col-4">
                                <input type="text" name="achternaam" value="{{ old('achternaam', $klant->Achternaam) }}" class="form-control" placeholder="Achternaam" required>
                            </div>
                        </div>
                    </div>

                    {{-- NIET BEWERKBAAR: Relatienummer --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Relatienummer</label>
                        <input type="text"
                            value="{{ $klant->Relatienummer }}"
                            readonly
                            class="form-control bg-light text-muted" style="cursor: not-allowed;" />
                    </div>

                    {{-- BEWERKBAAR: Contact e-mail --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-bold">Contact e-mail <span class="text-danger">*</span></label>
                        <input type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $klant->ContactEmail) }}"
                            class="form-control @error('email') is-invalid @enderror"
                            required />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- NIET BEWERKBAAR: Account e-mail (Spiegelt Contact e-mail) --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Account e-mail</label>
                        <input type="email"
                            id="account_email"
                            name="account_email"
                            value="{{ old('account_email', $klant->ContactEmail ?? '') }}"
                            readonly
                            class="form-control bg-light text-muted" style="cursor: not-allowed;" />
                    </div>

                    {{-- BEWERKBAAR: Straatnaam --}}
                    <div class="col-md-6">
                        <label for="straatnaam" class="form-label fw-bold">Straatnaam <span class="text-danger">*</span></label>
                        <input type="text"
                            id="straatnaam"
                            name="straatnaam"
                            value="{{ old('straatnaam', $klant->Straatnaam) }}"
                            class="form-control" required />
                    </div>

                    {{-- BEWERKBAAR: Huisnummer & Toevoeging --}}
                    <div class="col-md-6">
                        <div class="row g-2">
                            <div class="col-6">
                                <label for="huisnummer" class="form-label fw-bold">Huisnummer <span class="text-danger">*</span></label>
                                <input type="text"
                                    id="huisnummer"
                                    name="huisnummer"
                                    value="{{ old('huisnummer', $klant->Huisnummer) }}"
                                    class="form-control" required />
                            </div>
                            <div class="col-6">
                                <label for="toevoeging" class="form-label fw-bold">Toevoeging</label>
                                <input type="text"
                                    id="toevoeging"
                                    name="toevoeging"
                                    value="{{ old('toevoeging', $klant->Toevoeging) }}"
                                    class="form-control" />
                            </div>
                        </div>
                    </div>

                    {{-- BEWERKBAAR: Postcode --}}
                    <div class="col-md-6">
                        <label for="postcode" class="form-label fw-bold">Postcode <span class="text-danger">*</span></label>
                        <input type="text"
                            id="postcode"
                            name="postcode"
                            value="{{ old('postcode', $klant->Postcode) }}"
                            class="form-control" required />
                    </div>

                    {{-- BEWERKBAAR: Plaats --}}
                    <div class="col-md-6">
                        <label for="plaats" class="form-label fw-bold">Plaats <span class="text-danger">*</span></label>
                        <input type="text"
                            id="plaats"
                            name="plaats"
                            value="{{ old('plaats', $klant->Plaats) }}"
                            class="form-control" required />
                    </div>

                    {{-- BEWERKBAAR: Mobiel --}}
                    <div class="col-md-6">
                        <label for="mobiel" class="form-label fw-bold">Mobiel <span class="text-danger">*</span></label>
                        <input type="text"
                            id="mobiel"
                            name="mobiel"
                            value="{{ old('mobiel', $klant->Mobiel) }}"
                            class="form-control" required />
                    </div>
                </div>

                {{-- BEWERKBAAR: Bijzonderheden --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="bijzonderheden" class="form-label fw-bold">Bijzonderheden</label>
                        <input type="text"
                            id="bijzonderheden"
                            name="bijzonderheden"
                            value="{{ old('bijzonderheden', $klant->Bijzonderheden) }}"
                            class="form-control" />
                    </div>
                </div>

                {{-- Horizontale uitlijning van verplichte velden tekst en knoppen --}}
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center border-top pt-3 mt-3">
                    <p class="text-muted small mb-3 mb-sm-0">
                        Velden met een <span class="text-danger">*</span> zijn verplicht.
                    </p>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger">Opslaan</button>
                        <a href="{{ route('klanten.show', $klant->Id) }}" class="btn btn-outline-primary">Terug</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const accountEmailInput = document.getElementById('account_email');

            // Zorg ervoor dat het 'Account e-mail' veld automatisch verandert wanneer 'Contact e-mail' wordt aangepast
            if (emailInput && accountEmailInput) {
                emailInput.addEventListener('input', function() {
                    accountEmailInput.value = this.value;
                });
            }
        });
    </script>
@endsection