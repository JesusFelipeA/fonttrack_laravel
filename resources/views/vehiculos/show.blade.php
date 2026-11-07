@extends('layouts.app')

@section('title', 'Vehículo ' . $vehiculo->eco)

@section('content')
    <x-page-header eyebrow="Vehículos" icon="bi-truck" :title="'Vehículo ' . $vehiculo->eco"
        :description="trim(($vehiculo->marca ?? '') . ' ' . ($vehiculo->modelo ?? '')) ?: 'Detalle de la unidad'">
        <x-slot:actions>
            <a href="{{ route('vehiculos.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Volver
            </a>
            <a href="{{ route('vehiculos.edit', $vehiculo->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1" aria-hidden="true"></i> Editar
            </a>
        </x-slot:actions>
    </x-page-header>

    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i><div>{{ session('error') }}</div>
        </div>
    @endif

    <section class="ft-panel" style="max-width: 52rem" aria-label="Datos del vehículo">
        <div class="ft-panel__body">
            <dl class="ft-detail-list">
                <dt>ECO</dt><dd><span class="ft-code">{{ $vehiculo->eco }}</span></dd>
                <dt>Placas</dt><dd><span class="ft-code">{{ $vehiculo->placas ?? 'N/A' }}</span></dd>
                <dt>Marca</dt><dd>{{ $vehiculo->marca ?? 'N/A' }}</dd>
                <dt>Modelo</dt><dd>{{ $vehiculo->modelo ?? 'N/A' }}</dd>
                <dt>Año</dt><dd>{{ $vehiculo->anio ?? 'N/A' }}</dd>
                <dt>Kilometraje</dt><dd class="ft-num">{{ number_format($vehiculo->kilometraje) }} km</dd>
                <dt>Color</dt><dd>{{ $vehiculo->color ?? 'N/A' }}</dd>
                <dt>Conductor habitual</dt><dd>{{ $vehiculo->conductor_habitual ?? 'No asignado' }}</dd>
                <dt>Estado</dt><dd><x-status-badge :status="$vehiculo->estatus" /></dd>
                <dt>Lugar</dt><dd>{{ $vehiculo->lugar->nombre ?? 'N/A' }}</dd>
            </dl>
        </div>
        <div class="ft-panel__footer justify-content-end">
            <form method="POST" action="{{ route('vehiculos.destroy', $vehiculo->id) }}" id="formEliminarVehiculo">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-secondary text-danger">
                    <i class="bi bi-trash3 me-1" aria-hidden="true"></i> Eliminar
                </button>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('formEliminarVehiculo').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;
            FT.confirm('Esta acción no se puede deshacer.', {
                title: @json('¿Eliminar el vehículo ' . $vehiculo->eco . '?'),
                confirmText: 'Eliminar',
                danger: true
            }).then(ok => { if (ok) form.submit(); });
        });
    </script>
@endpush
