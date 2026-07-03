@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="container mx-auto px-6">

        {{-- Wireframe-06: foutmelding bovenaan pagina buiten de kaart --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded mb-4 shadow-sm">
                Klantgegevens zijn niet bijgewerkt.
            </div>
        @endif

        {{-- Breadcrumb --}}
        <nav class="text-sm mb-2 font-medium">
            <a href="{{ url('/') }}" class="text-red-600 hover:underline">Home</a>
            <span class="text-gray-400 mx-2">/</span>
            <a href="{{ route('klanten.index') }}" class="text-red-600 hover:underline">Klanten</a>
            <span class="text-gray-400 mx-2">/</span>
            <span class="text-gray-700">Wijzigen</span>
        </nav>

        <h1 class="text-2xl font-bold mb-4">
            <span class="text-red-700">Klant wijzigen</span>
            <span class="text-gray-500 font-normal ml-2">— {{ $klant->Voornaam }} {{ $klant->Achternaam }}</span>
        </h1>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-3xl">
            <form action="{{ route('klanten.update', $klant->Id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="contact_id" value="{{ $klant->ContactId }}">

                {{-- Rij 1: Naam | Relatienummer --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Naam <span class="text-red-600">*</span>
                        </label>
                        <input type="text"
                            value="{{ $klant->Voornaam }} {{ $klant->Tussenvoegsel }} {{ $klant->Achternaam }}"
                            readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-500 bg-gray-50 cursor-not-allowed select-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Relatienummer</label>
                        <input type="text"
                            value="{{ $klant->Relatienummer }}"
                            readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-500 bg-gray-50 cursor-not-allowed select-none font-mono" />
                    </div>
                </div>

                {{-- Rij 2: Contact e-mail | Account e-mail --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                            Contact e-mail <span class="text-red-600">*</span>
                        </label>
                        <input type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $klant->ContactEmail) }}"
                            class="w-full px-3 py-2 border rounded text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 font-medium text-red-700
                                @error('email') border-red-500 ring-1 ring-red-500 @else border-gray-300 @enderror"
                            required />
                        @error('email')
                            <span class="text-red-600 text-xs mt-1 block font-medium">Het e-mailadres is al in gebruik</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Account e-mail</label>
                        <input type="text"
                            value="{{ $klant->AccountEmail ?? '' }}"
                            readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-500 bg-gray-50 cursor-not-allowed select-none" />
                    </div>
                </div>

                {{-- Rij 3: Straatnaam | Huisnummer | Toevoeging --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Straatnaam <span class="text-red-600">*</span>
                        </label>
                        <input type="text"
                            value="{{ $klant->Straatnaam }}"
                            readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-500 bg-gray-50 cursor-not-allowed select-none" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Huisnummer <span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                value="{{ $klant->Huisnummer }}"
                                readonly
                                class="w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-500 bg-gray-50 cursor-not-allowed select-none" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Toevoeging</label>
                            <input type="text"
                                value="{{ $klant->Toevoeging }}"
                                readonly
                                class="w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-500 bg-gray-50 cursor-not-allowed select-none" />
                        </div>
                    </div>
                </div>

                {{-- Rij 4: Postcode | Plaats --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Postcode <span class="text-red-600">*</span>
                        </label>
                        <input type="text"
                            value="{{ $klant->Postcode }}"
                            readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-500 bg-gray-50 cursor-not-allowed select-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Plaats <span class="text-red-600">*</span>
                        </label>
                        <input type="text"
                            value="{{ $klant->Plaats }}"
                            readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-500 bg-gray-50 cursor-not-allowed select-none" />
                    </div>
                </div>

                {{-- Rij 5: Mobiel --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Mobiel <span class="text-red-600">*</span>
                        </label>
                        <input type="text"
                            value="{{ $klant->Mobiel }}"
                            readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-500 bg-gray-50 cursor-not-allowed select-none" />
                    </div>
                </div>

                {{-- Rij 6: Bijzonderheden --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Bijzonderheden</label>
                    <input type="text"
                        value="{{ $klant->Bijzonderheden }}"
                        readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-500 bg-gray-50 cursor-not-allowed select-none" />
                </div>

                <p class="text-xs text-gray-500 mb-5">
                    Velden met een <span class="text-red-600">*</span> zijn verplicht.
                </p>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-6 rounded transition shadow-sm">
                        Opslaan
                    </button>
                    <a href="{{ route('klanten.show', $klant->Id) }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold py-2 px-6 rounded transition shadow-sm">
                        Terug
                    </a>
                </div>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-400 mt-10">© 2026 Kniploket Tiko - Alle rechten voorbehouden</p>
    </div>
</div>

<script>
    document.querySelector('form').addEventListener('submit', function (e) {
        const email = document.getElementById('email').value.trim();
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
    });
</script>
@endsection