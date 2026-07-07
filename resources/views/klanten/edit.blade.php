@extends('layouts.app')

@section('title', 'Klant wijzigen')

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="flex flex-wrap items-center gap-2 text-sm">
            <li><a href="{{ url('/') }}" class="text-kniploket-red hover:underline">Home</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li><a href="{{ route('klanten.index') }}" class="text-kniploket-red hover:underline">Klanten</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li class="text-slate-500" aria-current="page">Wijzigen</li>
        </ol>
    </nav>

    <h1 class="mb-3 text-2xl font-bold">
        <span class="titel-kniploket">Klant wijzigen</span>
        <span class="ml-2 font-normal text-slate-500">{{ $klant->Voornaam }} {{ $klant->Achternaam }}</span>
    </h1>

    {{-- Error melding (Wireframe-06) --}}
    @if ($errors->any())
        <div class="mb-3 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">
            <p class="font-semibold">Klantgegevens zijn niet bijgewerkt</p>
            <ul class="mt-2 list-inside list-disc space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <form action="{{ route('klanten.update', $klant->Id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mb-3">

                {{-- BEWERKBAAR: Naam --}}
                <div>
                    <label class="mb-1 block text-sm font-bold text-slate-700">
                        Naam <span class="text-kniploket-danger">*</span>
                    </label>
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            name="voornaam" 
                            value="{{ old('voornaam', $klant->Voornaam) }}" 
                            class="flex-1 rounded border border-slate-300 px-3 py-2 text-sm @error('voornaam') border-red-500 @enderror" 
                            placeholder="Voornaam" 
                            required>
                        <input 
                            type="text" 
                            name="tussenvoegsel" 
                            value="{{ old('tussenvoegsel', $klant->Tussenvoegsel ?? '') }}" 
                            class="w-24 rounded border border-slate-300 px-3 py-2 text-sm" 
                            placeholder="Tussenv.">
                        <input 
                            type="text" 
                            name="achternaam" 
                            value="{{ old('achternaam', $klant->Achternaam) }}" 
                            class="flex-1 rounded border border-slate-300 px-3 py-2 text-sm @error('achternaam') border-red-500 @enderror" 
                            placeholder="Achternaam" 
                            required>
                    </div>
                    @error('voornaam')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
                    @error('achternaam')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
                </div>

                {{-- NIET BEWERKBAAR: Relatienummer --}}
                <div>
                    <label class="mb-1 block text-sm font-bold text-slate-700">Relatienummer</label>
                    <input 
                        type="text"
                        value="{{ $klant->Relatienummer }}"
                        readonly
                        class="w-full rounded border border-slate-300 bg-slate-100 px-3 py-2 text-sm text-slate-500 cursor-not-allowed" />
                </div>

                {{-- BEWERKBAAR: E-mail --}}
                <div>
                    <label for="email" class="mb-1 block text-sm font-bold text-slate-700">
                        E-mail <span class="text-kniploket-danger">*</span>
                    </label>
                    <input 
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $klant->Email ?? '') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm @error('email') border-red-500 @enderror"
                        required />
                    @error('email')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                {{-- BEWERKBAAR: Straatnaam --}}
                <div>
                    <label for="straatnaam" class="mb-1 block text-sm font-bold text-slate-700">
                        Straatnaam <span class="text-kniploket-danger">*</span>
                    </label>
                    <input 
                        type="text"
                        id="straatnaam"
                        name="straatnaam"
                        value="{{ old('straatnaam', $klant->Straatnaam ?? '') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm @error('straatnaam') border-red-500 @enderror" 
                        required />
                    @error('straatnaam')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                {{-- BEWERKBAAR: Huisnummer & Toevoeging --}}
                <div>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label for="huisnummer" class="mb-1 block text-sm font-bold text-slate-700">
                                Huisnummer <span class="text-kniploket-danger">*</span>
                            </label>
                            <input 
                                type="text"
                                id="huisnummer"
                                name="huisnummer"
                                value="{{ old('huisnummer', $klant->Huisnummer ?? '') }}"
                                class="w-full rounded border border-slate-300 px-3 py-2 text-sm @error('huisnummer') border-red-500 @enderror" 
                                required />
                            @error('huisnummer')
                                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex-1">
                            <label for="toevoeging" class="mb-1 block text-sm font-bold text-slate-700">Toevoeging</label>
                            <input 
                                type="text"
                                id="toevoeging"
                                name="toevoeging"
                                value="{{ old('toevoeging', $klant->Toevoeging ?? '') }}"
                                class="w-full rounded border border-slate-300 px-3 py-2 text-sm" />
                        </div>
                    </div>
                </div>

                {{-- BEWERKBAAR: Postcode --}}
                <div>
                    <label for="postcode" class="mb-1 block text-sm font-bold text-slate-700">
                        Postcode <span class="text-kniploket-danger">*</span>
                    </label>
                    <input 
                        type="text"
                        id="postcode"
                        name="postcode"
                        value="{{ old('postcode', $klant->Postcode ?? '') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm @error('postcode') border-red-500 @enderror" 
                        required />
                    @error('postcode')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                {{-- BEWERKBAAR: Plaats --}}
                <div>
                    <label for="plaats" class="mb-1 block text-sm font-bold text-slate-700">
                        Plaats <span class="text-kniploket-danger">*</span>
                    </label>
                    <input 
                        type="text"
                        id="plaats"
                        name="plaats"
                        value="{{ old('plaats', $klant->Plaats ?? '') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm @error('plaats') border-red-500 @enderror" 
                        required />
                    @error('plaats')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                {{-- BEWERKBAAR: Mobiel --}}
                <div>
                    <label for="mobiel" class="mb-1 block text-sm font-bold text-slate-700">
                        Mobiel <span class="text-kniploket-danger">*</span>
                    </label>
                    <input 
                        type="text"
                        id="mobiel"
                        name="mobiel"
                        value="{{ old('mobiel', $klant->Mobiel ?? '') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm @error('mobiel') border-red-500 @enderror" 
                        required />
                    @error('mobiel')
                        <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                {{-- BEWERKBAAR: Bijzonderheden --}}
                <div>
                    <label for="bijzonderheden" class="mb-1 block text-sm font-bold text-slate-700">Bijzonderheden</label>
                    <input 
                        type="text"
                        id="bijzonderheden"
                        name="bijzonderheden"
                        value="{{ old('bijzonderheden', $klant->Bijzonderheden ?? '') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm" 
                        maxlength="50"
                        placeholder="Max 50 karakters" />
                </div>
            </div>

            {{-- Horizontale uitlijning van verplichte velden tekst en knoppen --}}
            <div class="flex flex-col items-center justify-between border-t border-slate-200 pt-3 mt-3 sm:flex-row">
                <p class="mb-3 text-sm text-slate-500 sm:mb-0">
                    Velden met een <span class="text-kniploket-danger">*</span> zijn verplicht.
                </p>
                <div class="flex gap-2">
                    <button 
                        type="submit" 
                        class="inline-flex items-center justify-center rounded bg-kniploket-danger px-4 py-2 text-sm font-medium text-white hover:bg-kniploket-danger-dark transition-colors">
                        Opslaan
                    </button>
                    <a 
                        href="{{ route('klanten.show', $klant->Id) }}" 
                        class="inline-flex items-center justify-center rounded border border-blue-600 px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 transition-colors">
                        Terug
                    </a>
                </div>
            </div>
        </form>
    </div>

@endsection