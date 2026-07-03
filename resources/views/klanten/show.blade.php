@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('klanten.index') }}" class="text-blue-500 hover:text-blue-700 text-sm">
            ← Terug naar overzicht
        </a>
    </div>

    <div class="bg-white rounded shadow p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            Klant Detail
        </h1>

        <div class="grid grid-cols-2 gap-6">
            <!-- Persoonlijke gegevens -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                    Persoonlijke gegevens
                </h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600">Voornaam</label>
                        <p class="text-gray-900">{{ $klant->Voornaam }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Tussenvoegsel</label>
                        <p class="text-gray-900">{{ $klant->Tussenvoegsel ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Achternaam</label>
                        <p class="text-gray-900">{{ $klant->Achternaam }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Relatienummer</label>
                        <p class="text-gray-900">{{ $klant->Relatienummer }}</p>
                    </div>
                </div>
            </div>

            <!-- Contactgegevens -->
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                    Contactgegevens
                </h2>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600">E-mail</label>
                        <p class="text-gray-900">{{ $klant->ContactEmail }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Mobiel</label>
                        <p class="text-gray-900">{{ $klant->Mobiel }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Adres</label>
                        <p class="text-gray-900">
                            {{ $klant->Straatnaam }} {{ $klant->Huisnummer }}
                            @if ($klant->Toevoeging)
                                {{ $klant->Toevoeging }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Postcode</label>
                        <p class="text-gray-900">{{ $klant->Postcode }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Plaats</label>
                        <p class="text-gray-900">{{ $klant->Plaats }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Extra info -->
        <div class="mt-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">
                Bijzonderheden
            </h2>
            <p class="text-gray-900">{{ $klant->Bijzonderheden ?? '-' }}</p>
        </div>

        <!-- Action buttons -->
        <div class="mt-8 flex gap-3">
            <a 
                href="{{ route('klanten.edit', $klant->Id) }}" 
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded transition"
            >
                Wijzig
            </a>
            <a 
                href="{{ route('klanten.index') }}" 
                class="bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-4 rounded transition"
            >
                Annuleer
            </a>
        </div>
    </div>
</div>
@endsection