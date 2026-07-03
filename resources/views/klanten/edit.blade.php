@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('klanten.index') }}" class="text-blue-500 hover:text-blue-700 text-sm">
            ← Terug naar overzicht
        </a>
    </div>

    <div class="bg-white rounded shadow p-6 max-w-2xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            Klant wijzigen
        </h1>

        <!-- Error messages -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-4">
                <p class="font-semibold mb-2">Klantgegevens zijn niet bijgewerkt</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('klanten.update', $klant->Id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Hidden contact ID -->
            <input type="hidden" name="contact_id" value="{{ $klant->ContactId }}">

            <!-- Read-only fields -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Voornaam
                    </label>
                    <input 
                        type="text" 
                        value="{{ $klant->Voornaam }}"
                        readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100 text-gray-600"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Achternaam
                    </label>
                    <input 
                        type="text" 
                        value="{{ $klant->Achternaam }}"
                        readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100 text-gray-600"
                    />
                </div>
            </div>

            <!-- Editable field -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Contact e-mailadres
                </label>
                <input 
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $klant->ContactEmail) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror"
                    placeholder="Voer e-mailadres in"
                    required
                />
                @error('email')
                    <span class="text-red-600 text-sm mt-1 block">
                        Het e-mailadres is al in gebruik
                    </span>
                @enderror
            </div>

            <!-- Additional info (read-only) -->
            <div class="mb-6 p-4 bg-gray-50 rounded">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Contact adres</h3>
                <p class="text-sm text-gray-600">
                    {{ $klant->Straatnaam }} {{ $klant->Huisnummer }}
                    @if ($klant->Toevoeging)
                        {{ $klant->Toevoeging }}
                    @endif
                    <br>
                    {{ $klant->Postcode }} {{ $klant->Plaats }}
                </p>
                <p class="text-sm text-gray-600 mt-2">
                    <strong>Mobiel:</strong> {{ $klant->Mobiel }}
                </p>
            </div>

            <!-- Action buttons -->
            <div class="flex gap-3">
                <button 
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded transition"
                >
                    Opslaan
                </button>
                <a 
                    href="{{ route('klanten.show', $klant->Id) }}"
                    class="bg-gray-400 hover:bg-gray-500 text-white font-semibold py-2 px-6 rounded transition"
                >
                    Annuleer
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Client-side validation -->
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const emailInput = document.getElementById('email');
        const email = emailInput.value.trim();

        // Simple email format check
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!email) {
            e.preventDefault();
            alert('E-mailadres is verplicht');
            return false;
        }

        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Voer een geldig e-mailadres in');
            return false;
        }

        return true;
    });
</script>
@endsection