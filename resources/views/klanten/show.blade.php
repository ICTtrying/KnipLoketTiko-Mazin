@extends('layouts.app')

@section('title', 'Klantdetail')

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="flex flex-wrap items-center gap-2 text-sm">
            <li><a href="{{ url('/') }}" class="text-kniploket-red hover:underline">Home</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li><a href="{{ route('klanten.index') }}" class="text-kniploket-red hover:underline">Klanten</a></li>
            <li class="text-slate-400" aria-hidden="true">/</li>
            <li class="text-slate-500" aria-current="page">Detail</li>
        </ol>
    </nav>

    <h1 class="mb-3 text-2xl font-bold">
        <span class="titel-kniploket">Klantdetail</span>
        <span class="ml-2 font-normal text-slate-500">{{ $klant->Voornaam }} {{ $klant->Achternaam }}</span>
    </h1>

    {{-- Success melding (verdwijnt na 3 seconden) - Wireframe-05 --}}
    @if (session('success'))
        <div id="successMessage" class="mb-3 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const element = document.getElementById('successMessage');
                if (element) {
                    element.style.transition = 'opacity 0.5s ease-out';
                    element.style.opacity = '0';
                    setTimeout(() => element.remove(), 500);
                }
            }, 3000);
        </script>
    @endif

    {{-- Data Grid (Wireframe-03) binnen een kaart --}}
    <div class="max-w-3xl rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="space-y-2 text-sm">
            <div class="flex border-b border-slate-200 pb-2">
                <div class="w-1/3 font-bold text-slate-900">Naam</div>
                <div class="w-2/3 text-slate-600">
                    {{ $klant->Voornaam }} 
                    @if($klant->Tussenvoegsel){{ $klant->Tussenvoegsel }} @endif
                    {{ $klant->Achternaam }}
                </div>
            </div>

            <div class="flex border-b border-slate-200 pb-2">
                <div class="w-1/3 font-bold text-slate-900">Relatienummer</div>
                <div class="w-2/3 text-slate-600">{{ $klant->Relatienummer }}</div>
            </div>

            <div class="flex border-b border-slate-200 pb-2">
                <div class="w-1/3 font-bold text-slate-900">E-mail</div>
                <div class="w-2/3 text-slate-600">
                    {{ $klant->Email && $klant->Email !== '-' ? $klant->Email : '(geen e-mail)' }}
                </div>
            </div>

            <div class="flex border-b border-slate-200 pb-2">
                <div class="w-1/3 font-bold text-slate-900">Straatnaam</div>
                <div class="w-2/3 text-slate-600">
                    {{ $klant->Straatnaam && $klant->Straatnaam !== '-' ? $klant->Straatnaam : '(geen straat)' }}
                </div>
            </div>

            <div class="flex border-b border-slate-200 pb-2">
                <div class="w-1/3 font-bold text-slate-900">Huisnummer</div>
                <div class="w-2/3 text-slate-600">
                    {{ $klant->Huisnummer && $klant->Huisnummer !== '-' ? $klant->Huisnummer : '(geen nummer)' }}
                </div>
            </div>

            <div class="flex border-b border-slate-200 pb-2">
                <div class="w-1/3 font-bold text-slate-900">Toevoeging</div>
                <div class="w-2/3 text-slate-600">{{ $klant->Toevoeging ?? '(geen toevoeging)' }}</div>
            </div>

            <div class="flex border-b border-slate-200 pb-2">
                <div class="w-1/3 font-bold text-slate-900">Postcode</div>
                <div class="w-2/3 text-slate-600">
                    {{ $klant->Postcode && $klant->Postcode !== '-' ? $klant->Postcode : '(geen postcode)' }}
                </div>
            </div>

            <div class="flex border-b border-slate-200 pb-2">
                <div class="w-1/3 font-bold text-slate-900">Plaats</div>
                <div class="w-2/3 text-slate-600">
                    {{ $klant->Plaats && $klant->Plaats !== '-' ? $klant->Plaats : '(geen plaats)' }}
                </div>
            </div>

            <div class="flex border-b border-slate-200 pb-2">
                <div class="w-1/3 font-bold text-slate-900">Mobiel</div>
                <div class="w-2/3 text-slate-600">
                    {{ $klant->Mobiel && $klant->Mobiel !== '-' ? $klant->Mobiel : '(geen mobiel)' }}
                </div>
            </div>

            <div class="flex">
                <div class="w-1/3 font-bold text-slate-900">Bijzonderheden</div>
                <div class="w-2/3 text-slate-600">{{ $klant->Bijzonderheden ?: '(geen bijzonderheden)' }}</div>
            </div>
        </div>

        {{-- Actieknop uitlijning --}}
        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ route('klanten.edit', $klant->Id) }}" class="inline-flex items-center justify-center rounded bg-kniploket-danger px-4 py-2 text-sm font-medium text-white hover:bg-kniploket-danger-dark transition-colors">Wijzigen</a>
            <a href="{{ route('klanten.index') }}" class="inline-flex items-center justify-center rounded border border-blue-600 px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 transition-colors">Terug</a>
        </div>
    </div>
@endsection