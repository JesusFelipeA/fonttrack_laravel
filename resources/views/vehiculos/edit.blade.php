@extends('layouts.app')

@section('title', 'Editar vehículo')

@section('content')
    <x-page-header eyebrow="Vehículos" icon="bi-truck" :title="'Editar ' . $vehiculo->eco"
        description="Actualiza los datos de la unidad.">
        <x-slot:actions>
            <a href="{{ route('vehiculos.show', $vehiculo->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Volver al detalle
            </a>
        </x-slot:actions>
    </x-page-header>

    <form method="POST" action="{{ route('vehiculos.update', $vehiculo->id) }}" class="ft-panel" style="max-width: 52rem">
        @csrf
        @method('PUT')
        @include('vehiculos._form')
        <div class="ft-panel__footer justify-content-end">
            <a href="{{ route('vehiculos.show', $vehiculo->id) }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1" aria-hidden="true"></i> Guardar cambios</button>
        </div>
    </form>
@endsection
