@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="container mx-auto px-6">

        {{-- Breadcrumb --}}
        <nav class="text-sm mb-2 font-medium">
            <a href="{{ url('/') }}" class="text-red-600 hover:underline">Home</a>
            <span class="text-gray-400 mx-2">/</span>
            <a href="{{ route('klanten.index') }}" class="text-red-600 hover:underline">Klanten</a>
            <span class="text-gray-400 mx-2">/</span>
            <span class="text-gray-700">Detail</span>
        </nav>

        <h1 class="text-2xl font-bold mb-6 text-gray-900">
            <span>Klantdetail</span>
            <span class="text-gray-500 font-normal ml-2">— {{ $klant->Voornaam }} {{ $klant->Achternaam }}</span>
        </h1>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 max-w-3xl overflow-hidden">
            <table class="w-full text-sm text-left">
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-gray-700 bg-gray-50 w-48">Naam</td>
                        <td class="px-6 py-3.5 text-gray-900">{{ $klant->Voornaam }} {{ $klant->Tussenvoegsel }} {{ $klant->Achternaam }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-gray-700 bg-gray-50">Relatienummer</td>
                        <td class="px-6 py-3.5 text-gray-900 font-mono text-xs tracking-wider">{{ $klant->Relatienummer }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-gray-700 bg-gray-50">Contact e-mail</td>
                        <td class="px-6 py-3.5 text-gray-900 font-medium text-red-700">{{ $klant->ContactEmail }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-gray-700 bg-gray-50">Account e-mail</td>
                        <td class="px-6 py-3.5 text-gray-900">{{ $klant->AccountEmail ?? '-' }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-gray-700 bg-gray-50">Straatnaam</td>
                        <td class="px-6 py-3.5 text-gray-900">{{ $klant->Straatnaam }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-gray-700 bg-gray-50">Huisnummer</td>
                        <td class="px-6 py-3.5 text-gray-900">{{ $klant->Huisnummer }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-gray-700 bg-gray-50">Toevoeging</td>
                        <td class="px-6 py-3.5 text-gray-900">{{ $klant->Toevoeging ?? '-' }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-gray-700 bg-gray-50">Postcode</td>
                        <td class="px-6 py-3.5 text-gray-900">{{ $klant->Postcode }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-gray-700 bg-gray-50">Plaats</td>
                        <td class="px-6 py-3.5 text-gray-900">{{ $klant->Plaats }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-gray-700 bg-gray-50">Mobiel</td>
                        <td class="px-6 py-3.5 text-gray-900">{{ $klant->Mobiel }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-gray-700 bg-gray-50">Bijzonderheden</td>
                        <td class="px-6 py-3.5 text-gray-900 whitespace-pre-line">{{ $klant->Bijzonderheden ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <a href="{{ route('klanten.edit', $klant->Id) }}"
                   class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-6 rounded transition shadow-sm">
                    Wijzigen
                </a>
                <a href="{{ route('klanten.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold py-2 px-6 rounded transition shadow-sm">
                    Terug
                </a>
            </div>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-400 mt-10">© 2026 Kniploket Tiko - Alle rechten voorbehouden</p>
    </div>
</div>
@endsection