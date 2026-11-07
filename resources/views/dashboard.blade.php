@extends('layouts.app')

@section('title', 'Panel')

@section('content')
    <x-page-header
        eyebrow="Panel general"
        icon="bi-grid-1x2"
        title="Hola, {{ \Illuminate\Support\Str::of(Auth::user()->nombre)->before(' ') }}"
        description="Resumen del inventario, la flota y los reportes de fallas de todos los lugares.">
        <x-slot:actions>
            <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-clipboard2-pulse me-1" aria-hidden="true"></i> Ver reportes
            </a>
            <a href="{{ route('materials') }}" class="btn btn-primary">
                <i class="bi bi-box-seam me-1" aria-hidden="true"></i> Ir a materiales
            </a>
        </x-slot:actions>
    </x-page-header>

    {{-- Indicadores principales --}}
    <section aria-label="Indicadores" class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <x-stat-card label="Materiales registrados" icon="bi-box-seam" :href="route('materials')"
                :value="number_format($materiales['total'])"
                :meta="number_format($materiales['unidades']) . ' unidades en existencia'" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat-card label="Valor del inventario" icon="bi-cash-stack"
                :value="'$' . number_format($materiales['valor'], 2)"
                meta="Existencia × costo promedio" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat-card label="Solicitudes pendientes" icon="bi-bell" :href="route('materials')"
                :value="number_format($notificacionesPendientes)"
                :meta="$notificacionesPendientes ? 'Requieren aprobación' : 'Todo al día'" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat-card label="Reportes de fallas" icon="bi-clipboard2-pulse" :href="route('reportes.index')"
                :value="number_format($fallas['total'])"
                :meta="number_format($fallas['ultimos_30']) . ' en los últimos 30 días'" />
        </div>
    </section>

    <div class="row g-3">
        {{-- Reportes recientes --}}
        <div class="col-12 col-xl-8">
            <section class="ft-panel h-100" aria-labelledby="tituloRecientes">
                <div class="ft-panel__header">
                    <h2 class="ft-panel__title" id="tituloRecientes"><i class="bi bi-clock-history" aria-hidden="true"></i> Reportes recientes</h2>
                    <a href="{{ route('reportes.index') }}" class="btn btn-sm btn-ghost">Ver todos <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                </div>
                @if($fallasRecientes->isEmpty())
                    <x-empty-state icon="bi-clipboard2-check" title="Sin reportes todavía"
                        text="Cuando se registre un reporte de falla aparecerá aquí." />
                @else
                    <div class="ft-table-wrap">
                        <table class="table table-hover ft-table-stack">
                            <thead>
                                <tr>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Lugar</th>
                                    <th scope="col">ECO</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col" class="text-end">Cant.</th>
                                    <th scope="col" class="ft-col-actions"><span class="visually-hidden">Acciones</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fallasRecientes as $falla)
                                    <tr>
                                        <td data-label="Fecha" class="text-nowrap">{{ $falla->fecha ? \Carbon\Carbon::parse($falla->fecha)->format('d/m/Y') : '—' }}</td>
                                        <td data-label="Lugar">{{ optional($falla->lugar)->nombre ?? '—' }}</td>
                                        <td data-label="ECO"><span class="ft-code">{{ $falla->eco ?: '—' }}</span></td>
                                        <td data-label="Descripción" class="text-truncate" style="max-width: 18rem">{{ $falla->descripcion ?: '—' }}</td>
                                        <td data-label="Cantidad" class="text-end">{{ $falla->cantidad ?? 0 }}</td>
                                        <td class="ft-col-actions">
                                            <a href="{{ route('fallas.pdf', $falla->id) }}" class="btn btn-sm btn-ghost btn-icon" title="Ver PDF del reporte" aria-label="Ver PDF del reporte del {{ $falla->fecha }}">
                                                <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>

        {{-- Flota --}}
        <div class="col-12 col-md-6 col-xl-4">
            <section class="ft-panel h-100" aria-labelledby="tituloFlota">
                <div class="ft-panel__header">
                    <h2 class="ft-panel__title" id="tituloFlota"><i class="bi bi-truck" aria-hidden="true"></i> Flota</h2>
                    <a href="{{ route('vehiculos.index') }}" class="btn btn-sm btn-ghost">Gestionar</a>
                </div>
                <div class="ft-panel__body">
                    <div class="d-flex align-items-baseline gap-2 mb-3">
                        <span class="ft-stat__value">{{ number_format($vehiculos['total']) }}</span>
                        <span class="text-body-secondary">vehículos registrados</span>
                    </div>
                    @php $t = max($vehiculos['total'], 1); @endphp
                    <div class="ft-meter mb-3" role="img"
                         aria-label="{{ $vehiculos['activo'] }} activos, {{ $vehiculos['mantenimiento'] }} en mantenimiento, {{ $vehiculos['inactivo'] }} inactivos">
                        <span style="width: {{ $vehiculos['activo'] / $t * 100 }}%; background: var(--ft-success)"></span>
                        <span style="width: {{ $vehiculos['mantenimiento'] / $t * 100 }}%; background: #D9922E"></span>
                        <span style="width: {{ $vehiculos['inactivo'] / $t * 100 }}%; background: var(--ft-border-strong)"></span>
                    </div>
                    <ul class="list-unstyled m-0 d-grid gap-2">
                        <li class="d-flex justify-content-between"><x-status-badge status="activo" /><strong class="ft-num">{{ $vehiculos['activo'] }}</strong></li>
                        <li class="d-flex justify-content-between"><x-status-badge status="mantenimiento" /><strong class="ft-num">{{ $vehiculos['mantenimiento'] }}</strong></li>
                        <li class="d-flex justify-content-between"><x-status-badge status="inactivo" /><strong class="ft-num">{{ $vehiculos['inactivo'] }}</strong></li>
                    </ul>
                </div>
            </section>
        </div>

        {{-- Materiales sin existencia --}}
        <div class="col-12 col-md-6 col-xl-8">
            <section class="ft-panel h-100" aria-labelledby="tituloSinExistencia">
                <div class="ft-panel__header">
                    <h2 class="ft-panel__title" id="tituloSinExistencia"><i class="bi bi-exclamation-diamond" aria-hidden="true"></i> Materiales sin existencia</h2>
                    <span class="ft-badge {{ $materiales['sin_existencia'] ? 'ft-badge--danger' : 'ft-badge--success' }}">{{ $materiales['sin_existencia'] }}</span>
                </div>
                @if($materialesSinExistencia->isEmpty())
                    <x-empty-state icon="bi-check2-circle" title="Todo con existencia"
                        text="Ningún material registrado tiene existencia en cero." />
                @else
                    <ul class="list-unstyled m-0">
                        @foreach($materialesSinExistencia as $material)
                            <li class="d-flex align-items-center justify-content-between gap-3 px-3 px-md-4 py-2 border-bottom">
                                <div class="min-w-0">
                                    <span class="ft-code text-body-secondary">{{ $material->clave_material }}</span>
                                    <span class="d-block text-truncate">{{ $material->descripcion }}</span>
                                </div>
                                <span class="ft-badge ft-badge--danger ft-badge--plain">0</span>
                            </li>
                        @endforeach
                    </ul>
                    @if($materiales['sin_existencia'] > $materialesSinExistencia->count())
                        <div class="ft-panel__footer">
                            <span class="text-body-secondary small">Mostrando {{ $materialesSinExistencia->count() }} de {{ $materiales['sin_existencia'] }}</span>
                            <a href="{{ route('materials') }}" class="btn btn-sm btn-ghost">Ver materiales</a>
                        </div>
                    @endif
                @endif
            </section>
        </div>

        {{-- Organización --}}
        <div class="col-12 col-xl-4">
            <section class="ft-panel h-100" aria-labelledby="tituloOrganizacion">
                <div class="ft-panel__header">
                    <h2 class="ft-panel__title" id="tituloOrganizacion"><i class="bi bi-diagram-3" aria-hidden="true"></i> Organización</h2>
                </div>
                <div class="ft-panel__body d-grid gap-2">
                    <a href="{{ route('users') }}" class="d-flex align-items-center justify-content-between p-3 border rounded-3 text-decoration-none text-body">
                        <span><i class="bi bi-people me-2 text-body-secondary" aria-hidden="true"></i>Usuarios</span>
                        <strong class="ft-num">{{ $usuarios }}</strong>
                    </a>
                    <a href="{{ route('lugares.index') }}" class="d-flex align-items-center justify-content-between p-3 border rounded-3 text-decoration-none text-body">
                        <span><i class="bi bi-geo-alt me-2 text-body-secondary" aria-hidden="true"></i>Lugares</span>
                        <strong class="ft-num">{{ $lugares }}</strong>
                    </a>
                </div>
            </section>
        </div>
    </div>
@endsection
