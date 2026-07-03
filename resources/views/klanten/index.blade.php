@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="container mx-auto px-6">

        {{-- Wireframe-05: success flash, verdwijnt na 3 seconden --}}
        @if (session('success'))
            <div id="flash-success" class="bg-green-100 border border-green-300 text-green-800 text-sm px-4 py-3 rounded mb-4 shadow-sm transition-all duration-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- Breadcrumb --}}
        <nav class="text-sm mb-2 font-medium">
            <a href="{{ url('/') }}" class="text-red-600 hover:underline">Home</a>
            <span class="text-gray-400 mx-2">/</span>
            <span class="text-gray-700">Klanten</span>
        </nav>

        <h1 class="text-2xl font-bold text-red-700 mb-6">Overzicht klanten</h1>

        {{-- Zoekbalk --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('klanten.index') }}" class="flex flex-col sm:flex-row items-start sm:items-end justify-end gap-3">
                <div class="w-full sm:w-auto">
                    <label for="postcode" class="block text-sm font-semibold text-gray-700 mb-1">Postcode zoeken</label>
                    <input
                        type="text"
                        id="postcode"
                        name="postcode"
                        value="{{ $postcode ?? '' }}"
                        placeholder="Bijv. 3512AB"
                        class="w-full sm:w-52 px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                    />
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit"
                        class="flex-1 sm:flex-none bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-5 rounded transition shadow-sm">
                        Toon klanten
                    </button>
                    <a href="{{ route('klanten.index') }}"
                        class="flex-1 sm:flex-none bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold py-2 px-5 rounded transition shadow-sm text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Resultatenkaart --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @if(empty($klanten))
                <div class="px-6 py-8 text-center text-sm text-gray-600 font-medium">
                    Er zijn geen klanten bekent die de geselecteerde postcode hebben
                </div>
            @else
                {{-- Gevonden klanten + paginering --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b border-gray-200 bg-gray-50 gap-3">
                    <span class="text-sm font-medium text-gray-600">Gevonden klanten - {{ count($klanten) }} klant(en)</span>
                    <div class="flex items-center gap-1 text-sm self-center">
                        <button class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full text-gray-400 bg-white cursor-not-allowed" disabled>‹</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded-full bg-red-600 text-white font-semibold">1</button>
                        <button class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full text-gray-600 bg-white hover:bg-gray-100 transition">2</button>
                        <button class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full text-gray-400 bg-white cursor-not-allowed" disabled>›</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left whitespace-nowrap">
                        <thead class="bg-red-600 text-white uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Naam</th>
                                <th class="px-6 py-3 font-semibold">Relatienummer</th>
                                <th class="px-6 py-3 font-semibold">Adres</th>
                                <th class="px-6 py-3 font-semibold">Postcode</th>
                                <th class="px-6 py-3 font-semibold">Woonplaats</th>
                                <th class="px-6 py-3 font-semibold">Mobiel</th>
                                <th class="px-6 py-3 font-semibold">Contact e-mail</th>
                                <th class="px-6 py-3 font-semibold text-center">Actie</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($klanten as $klant)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $klant->Voornaam }} {{ $klant->Achternaam }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $klant->Relatienummer }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $klant->Straatnaam }} {{ $klant->Huisnummer }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $klant->Postcode }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $klant->Plaats }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $klant->Mobiel }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $klant->ContactEmail }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('klanten.show', $klant->Id) }}"
                                       class="inline-block border border-blue-500 text-blue-600 text-xs font-semibold px-4 py-1.5 rounded hover:bg-blue-50 transition shadow-sm">
                                        Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-400 mt-10">© 2026 Kniploket Tiko - Alle rechten voorbehouden</p>
    </div>
</div>

<script>
    // Wireframe-05: flash verdwijnt na 3 seconden
    const flash = document.getElementById('flash-success');
    if (flash) {
        setTimeout(() => {
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 300);
        }, 3000);
    }
</script>
@endsection