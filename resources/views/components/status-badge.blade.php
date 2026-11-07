@props(['status'])

@php
    // Estados reales de la base de datos: tb_vehiculos.estatus y tb_notificaciones.estado.
    $mapa = [
        'activo' => ['success', 'Activo'],
        'inactivo' => ['neutral', 'Inactivo'],
        'mantenimiento' => ['warning', 'En mantenimiento'],
        'pendiente' => ['warning', 'Pendiente'],
        'aprobada' => ['success', 'Aprobada'],
        'rechazada' => ['danger', 'Rechazada'],
    ];
    [$tono, $texto] = $mapa[$status] ?? ['neutral', ucfirst((string) $status)];
@endphp

<span {{ $attributes->merge(['class' => "ft-badge ft-badge--{$tono}"]) }}>{{ $texto }}</span>
