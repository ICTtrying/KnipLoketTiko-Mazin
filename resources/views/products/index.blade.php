@extends('layouts.app')

@section('title', 'Overzicht producten')

@section('content')
    {{-- Wireframe-02: breadcrumb Home / Producten --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Producten</li>
        </ol>
    </nav>

    <h1 class="h3 text-danger mb-3">Overzicht producten</h1>
@endsection
