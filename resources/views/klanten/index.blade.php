@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 max-w-6xl">

        {{-- Wireframe-05: success flash [cite: 388] --}}
        @if (session('success'))
            <div id="flash-success" class="bg-[#dcfce7] text-[#166534] px-6 py-4 mb-6 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Breadcrumb [cite: 274] --}}
        <nav class="text-sm mb-4 font-medium">
            <a href="{{ url('/') }}" class="text-red-700 hover:underline">Home</a>
            <span class="text-gray-500 mx-2">/</span>
            <span class="text-gray-500">Klanten</span>
        </nav>

        <h1 class="text-3xl font-bold text-red-700 mb-6">Overzicht klanten</h1>

        {{-- Zoekbalk: Uitgelijnd naar rechts (Wireframe-02)  --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 flex justify-end">
            <form method="GET" action="{{ route('klanten.index') }}" class="flex flex-col sm:flex-row items-end gap-3 w-full sm:w-auto">
                <div class="w-full sm:w-64">
                    <label for="postcode" class="block text-sm font-bold text-gray-800 mb-2">Postcode zoeken</label>
                    <input
                        type="text"
                        id="postcode"
                        name="postcode"
                        value="{{ $postcode ?? '' }}"
                        placeholder="Bijv. 3512AB"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 text-sm"
                    />
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-6 rounded transition text-sm">
                        Toon klanten
                    </button>
                    <a href="{{ route('klanten.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded transition text-center text-sm flex items-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Resultatenkaart --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-10">
            {{-- Paginering en teller (Wireframe-02) [cite: 276, 277, 278] --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 bg-white">
                <span class="text-sm text-gray-500">Gevonden klanten - {{ count($klanten ?? []) }} klant(en)</span>
                <div class="flex items-center gap-1 text-sm mt-2 sm:mt-0">
                    <button class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded text-gray-400 bg-white" disabled>‹</button>
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-red-700 text-white font-bold">1</button>
                    <button class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded text-gray-600 bg-white hover:bg-gray-100">2</button>
                    <button class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded text-gray-400 bg-white" disabled>›</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left whitespace-nowrap">
                    <thead class="bg-red-700 text-white">
                        <tr>
                            <th class="px-6 py-3 font-bold text-sm">Naam</th>
                            <th class="px-6 py-3 font-bold text-sm">Relatienummer</th>
                            <th class="px-6 py-3 font-bold text-sm">Adres</th>
                            <th class="px-6 py-3 font-bold text-sm">Postcode</th>
                            <th class="px-6 py-3 font-bold text-sm">Woonplaats</th>
                            <th class="px-6 py-3 font-bold text-sm">Mobiel</th>
                            <th class="px-6 py-3 font-bold text-sm">Contact e-mail</th>
                            <th class="px-6 py-3 font-bold text-sm text-center">Actie</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if(empty($klanten))
                            {{-- Wireframe-04: Header blijft, tabel toont foutmelding [cite: 630] --}}
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-600 text-sm">
                                    {{ $message ?? 'Er zijn geen klanten bekent die de geselecteerde postcode hebben' }}
                                </td>
                            </tr>
                        @else
                            @foreach ($klanten as $klant)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $klant->Voornaam }} {{ $klant->Achternaam }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $klant->Relatienummer }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $klant->Straatnaam }} {{ $klant->Huisnummer }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $klant->Postcode }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $klant->Plaats }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $klant->Mobiel }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $klant->ContactEmail }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('klanten.show', $klant->Id) }}"
                                       class="inline-block border border-blue-500 text-blue-500 bg-white text-sm px-4 py-1.5 rounded hover:bg-blue-50 transition">
                                        Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer [cite: 318] --}}
        <p class="text-center text-xs text-gray-400 mt-10 pb-8">© 2026 Kniploket Tiko - Alle rechten voorbehouden</p>
    </div>
</div>

<script>
    const flash = document.getElementById('flash-success');
    if (flash) {
        setTimeout(() => {
            flash.style.opacity = '0';
            flash.style.transition = 'opacity 0.3s ease';
            setTimeout(() => flash.remove(), 300);
        }, 3000); // Verdwijnt exact na 3 seconden conform specificatie [cite: 209]
    }
</script>
@endsection