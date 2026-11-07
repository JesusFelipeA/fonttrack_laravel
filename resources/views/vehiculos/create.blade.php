@extends('layouts.app')

@section('title', 'Nuevo vehículo')

@section('content')
    <x-page-header eyebrow="Vehículos" icon="bi-truck" title="Nuevo vehículo"
        description="Registra una unidad de la flota y asígnala a un lugar.">
        <x-slot:actions>
            <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Volver a vehículos
            </a>
        </x-slot:actions>
    </x-page-header>

    <form method="POST" action="{{ route('vehiculos.store') }}" class="ft-panel" style="max-width: 52rem">
        @csrf
        @include('vehiculos._form')
        <div class="ft-panel__footer justify-content-end">
            <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1" aria-hidden="true"></i> Guardar</button>
        </div>
    </form>
@endsection
