@extends('layouts.app')

@section('title', 'Materiales')

@if(Auth::user()->id_lugar)
    @push('topbar-actions')
        <!-- Campana de notificaciones -->
        <button type="button" class="notification-bell" id="notificationBell" title="Notificaciones pendientes" aria-label="Notificaciones pendientes">
            <i class="bi bi-bell bell-icon" aria-hidden="true"></i>
            <span class="notification-badge hidden" id="notificationBadge">0</span>
        </button>
    @endpush
@endif

@section('content')
    @if(Auth::user()->id_lugar || Auth::user()->tipo_usuario == 1)
        
        <x-page-header
            eyebrow="Inventario"
            icon="bi-box-seam"
            title="Materiales"
            :description="'Existencias y costos del almacén · ' . (Auth::user()->lugar->nombre ?? 'Todos los lugares')">
            <x-slot:actions>
                <a href="{{ route('materials.export') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-download me-1" aria-hidden="true"></i> Exportar existencias
                </a>
                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalCardex">
                    <i class="bi bi-file-earmark-arrow-up me-1" aria-hidden="true"></i> Subir Kardex
                </button>
                <button class="btn btn-outline-secondary" id="btnReporteFallas">
                    <i class="bi bi-clipboard2-plus me-1" aria-hidden="true"></i> Reporte de fallas
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMaterial" id="btnNuevoMaterial">
                    <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Registrar material
                </button>
            </x-slot:actions>
        </x-page-header>

        <section class="ft-panel" aria-label="Lista de materiales">
            <form class="ft-toolbar" action="{{ route('materials') }}" method="GET" role="search">
                <div class="ft-toolbar__grow ft-search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <label for="buscarMaterial" class="visually-hidden">Buscar material</label>
                    <input class="form-control" type="search" id="buscarMaterial" name="query"
                        placeholder="Buscar por clave, descripción, genérico o clasificación" value="{{ request('query') }}">
                </div>
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                @if(request('query'))
                    <a href="{{ route('materials') }}" class="btn btn-ghost">Limpiar</a>
                @endif
            </form>

            <div class="ft-table-wrap">
                <table class="table table-hover ft-table-stack">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Clave</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Genérico</th>
                            <th scope="col">Clasificación</th>
                            <th scope="col" class="text-center">Existencia</th>
                            <th scope="col" class="text-end">Costo prom.</th>
                            <th scope="col" class="ft-col-actions"><span class="visually-hidden">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materiales as $material)
                            <tr data-id="{{ $material->id_material }}">
                                <td data-label="ID" class="text-body-secondary">{{ $material->id_material }}</td>
                                <td data-label="Clave"><span class="ft-code ft-cell-title">{{ $material->clave_material }}</span></td>
                                <td data-label="Descripción">{{ $material->descripcion }}</td>
                                <td data-label="Genérico">{{ $material->generico }}</td>
                                <td data-label="Clasificación">
                                    @if($material->clasificacion)
                                        <span class="ft-badge ft-badge--neutral ft-badge--plain">{{ $material->clasificacion }}</span>
                                    @endif
                                </td>
                                <td data-label="Existencia" class="text-center">
                                    <div class="ft-stock {{ $material->existencia <= 0 ? 'ft-stock--empty' : '' }}">
                                        <button class="btn btn-sm btn-ghost btnDisminuir" data-id="{{ $material->id_material }}"
                                            title="Reportar falla" aria-label="Reportar falla de {{ $material->clave_material }}">
                                            <i class="bi bi-dash" aria-hidden="true"></i>
                                        </button>
                                        <span class="ft-stock__value">{{ $material->existencia }}</span>
                                        <button class="btn btn-sm btn-ghost btnAumentar" data-id="{{ $material->id_material }}"
                                            title="Aumentar existencia" aria-label="Aumentar existencia de {{ $material->clave_material }}">
                                            <i class="bi bi-plus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                                <td data-label="Costo prom." class="text-end text-nowrap">${{ number_format((float) $material->costo_promedio, 2) }}</td>
                                <td class="ft-col-actions">
                                    <div class="ft-table-actions">
                                        <button class="btn btn-ghost btn-sm btn-icon btnVer" data-id="{{ $material->id_material }}"
                                            data-bs-toggle="modal" data-bs-target="#modalMaterial" title="Ver detalles" aria-label="Ver {{ $material->clave_material }}">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-ghost btn-sm btn-icon btnEditar" data-id="{{ $material->id_material }}"
                                            data-bs-toggle="modal" data-bs-target="#modalMaterial" title="Editar" aria-label="Editar {{ $material->clave_material }}">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-ghost btn-sm btn-icon text-danger btnEliminar" data-id="{{ $material->id_material }}"
                                            title="Eliminar" aria-label="Eliminar {{ $material->clave_material }}">
                                            <i class="bi bi-trash3" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if($materiales->count() == 0)
                            <tr>
                                <td colspan="8" class="empty-state">
                                    <i class="bi bi-box-seam" aria-hidden="true"></i>
                                    <h4>{{ request('query') ? 'Sin coincidencias' : 'Sin materiales' }}</h4>
                                    <p>{{ request('query') ? 'Ningún material coincide con la búsqueda.' : 'No hay materiales registrados.' }}</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if($materiales->hasPages())
                <div class="ft-panel__footer ft-pagination">
                    {{ $materiales->appends(['query' => request('query')])->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </section>
    @else
        <!-- Mensaje para usuarios sin lugar asignado -->
        <section class="ft-panel">
            <x-empty-state icon="bi-shield-lock" title="Acceso restringido"
                text="No tienes un lugar asignado para acceder a esta sección. Contacta al administrador para obtener acceso." />
        </section>
    @endif
@endsection

@push('modals')
    @if(Auth::user()->id_lugar)
        <!-- Modal de Notificaciones -->
        <div class="modal fade notification-modal" id="modalNotificaciones" tabindex="-1" aria-labelledby="modalNotificacionesTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalNotificacionesTitle">
                            <i class="bi bi-bell" aria-hidden="true"></i>
                            Notificaciones pendientes
                            <span class="ft-badge ft-badge--warning ft-badge--plain" id="modalNotificationCount">0</span>
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div id="notificationsContainer" aria-live="polite">
                            <div class="text-center py-4">
                                <div class="loading-spinner"></div>
                                <p class="mt-2 mb-0 text-body-secondary">Cargando notificaciones...</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btnRefreshNotifications">
                            <i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i> Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para ver detalles de notificación -->
        <div class="modal fade" id="modalDetalleNotificacion" tabindex="-1" aria-labelledby="modalDetalleNotificacionTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalDetalleNotificacionTitle"><i class="bi bi-card-text" aria-hidden="true"></i> Detalles de la notificación</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body" id="detalleNotificacionBody">
                        <!-- Se carga via AJAX -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para aprobar notificación -->
        <div class="modal fade" id="modalAprobarNotificacion" tabindex="-1" aria-labelledby="modalAprobarTitle" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalAprobarTitle"><i class="bi bi-check2-circle" aria-hidden="true"></i> Aprobar notificación</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                            <div>Al aprobar esta notificación se creará automáticamente un reporte de falla y se descontará el stock de los materiales.</div>
                        </div>
                        <div class="mb-3">
                            <label for="passwordAprobar" class="form-label required">Confirma tu contraseña</label>
                            <div class="input-group">
                                <input type="password" id="passwordAprobar" class="form-control"
                                    placeholder="Tu contraseña" required autocomplete="current-password">
                                <button type="button" class="btn btn-outline-secondary" id="btnTogglePasswordAprobar" aria-label="Mostrar u ocultar contraseña">
                                    <i class="bi bi-eye" id="iconTogglePasswordAprobar" aria-hidden="true"></i>
                                </button>
                            </div>
                            <small id="passwordValidating" class="text-body-secondary" style="display: none;" role="status">
                                Validando contraseña...
                            </small>
                            <small id="passwordValidSuccess" class="text-success" style="display: none;" role="status">
                                <i class="bi bi-check-circle-fill" aria-hidden="true"></i> Contraseña válida
                            </small>
                        </div>

                        <div id="datosUsuarioAutoriza" class="mb-3" style="display: none;">
                            <div class="alert alert-success mb-0">
                                <i class="bi bi-person-check-fill" aria-hidden="true"></i>
                                <div>
                                    <strong class="d-block mb-1">Usuario que autoriza</strong>
                                    <span id="nombreUsuarioAutoriza"></span> · <span id="correoUsuarioAutoriza"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="comentariosAprobar" class="form-label required">Correo para envío del reporte</label>
                            <input type="email" id="comentariosAprobar" class="form-control"
                                placeholder="correo@ejemplo.com" required aria-describedby="comentariosAprobarHelp">
                            <div class="form-text" id="comentariosAprobarHelp">Se enviará el PDF del reporte a este correo.</div>
                        </div>
                        <div id="errorAprobar" class="alert alert-danger d-none mb-0" role="alert">
                            <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                            <span id="errorAprobarMessage">Error al procesar</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" id="btnConfirmarAprobar" disabled>
                            <i class="bi bi-check2 me-1" aria-hidden="true"></i> Aprobar y enviar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para rechazar notificación -->
        <div class="modal fade" id="modalRechazarNotificacion" tabindex="-1" aria-labelledby="modalRechazarTitle" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalRechazarTitle"><i class="bi bi-x-circle" aria-hidden="true"></i> Rechazar notificación</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                            <div>La notificación rechazada se conserva en el registro para auditoría, pero no se procesa.</div>
                        </div>
                        <div class="mb-3">
                            <label for="comentariosRechazar" class="form-label required">Motivo del rechazo</label>
                            <textarea id="comentariosRechazar" class="form-control" rows="4"
                                placeholder="Explica por qué se rechaza esta notificación" required></textarea>
                        </div>
                        <div id="errorRechazar" class="alert alert-danger d-none mb-0" role="alert">
                            <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
                            <span id="errorRechazarMessage">Error al procesar</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="btnConfirmarRechazar">
                            <i class="bi bi-x-lg me-1" aria-hidden="true"></i> Rechazar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal para subir archivos del Kardex -->
    <div class="modal fade" id="modalCardex" tabindex="-1" aria-labelledby="modalCardexTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formCardex" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalCardexTitle"><i class="bi bi-file-earmark-arrow-up" aria-hidden="true"></i> Subir archivo del Kardex</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label for="lugarCardex" class="form-label">Lugar</label>
                                <input type="text" id="lugarCardex" class="form-control" value="{{ Auth::user()->lugar->nombre ?? 'Sin lugar asignado' }}" readonly>
                                <input type="hidden" name="id_lugar" value="{{ Auth::user()->id_lugar }}">
                            </div>
                            <div class="col-md-7">
                                <label for="archivo_cardex" class="form-label required">Archivo Excel</label>
                                <input type="file" id="archivo_cardex" name="archivo_cardex" class="form-control"
                                    accept=".xlsx,.xls" required aria-describedby="cardexHelp">
                                <div class="form-text" id="cardexHelp">Formatos .xlsx o .xls</div>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                            <div>
                                <strong>Columnas requeridas:</strong>
                                Clave Material, Descripción, Genérico, Clasificación, Existencia y Costo Promedio.
                            </div>
                        </div>
                        <div id="progreso" class="mt-3" style="display: none;">
                            <div class="progress" role="progressbar" aria-label="Progreso de carga">
                                <div class="progress-bar" style="width: 0%"></div>
                            </div>
                            <small class="text-body-secondary">Procesando archivo...</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnSubirCardex">
                            <i class="bi bi-upload me-1" aria-hidden="true"></i> Subir archivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Material (Registro/Edición/Ver) -->
    <div class="modal fade" id="modalMaterial" tabindex="-1" aria-labelledby="modalMaterialTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formMaterial">
                    @csrf
                    <input type="hidden" id="materialId">
                    <input type="hidden" name="id_lugar" value="{{ Auth::user()->id_lugar }}">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalMaterialTitle"></h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body" id="modalMaterialBody"></div>
                    <div class="modal-footer" id="modalMaterialFooter"></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirmación de Eliminación -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="modalEliminarTitle"><i class="bi bi-trash3" aria-hidden="true"></i> Eliminar material</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">¿Seguro que deseas eliminar este material? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Aumentar Existencia -->
    <div class="modal fade" id="modalAumentar" tabindex="-1" aria-labelledby="modalAumentarTitle" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <form id="formAumentar">
                    @csrf
                    <input type="hidden" id="materialIdAumentar">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalAumentarTitle"><i class="bi bi-plus-circle" aria-hidden="true"></i> Aumentar existencia</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <dl class="ft-detail-list mb-3">
                            <dt>Material</dt><dd id="materialNombreAumentar"></dd>
                            <dt>Existencia actual</dt><dd id="existenciaActualAumentar" class="ft-num"></dd>
                        </dl>
                        <label for="cantidadAumentar" class="form-label required">Cantidad a aumentar</label>
                        <input type="number" id="cantidadAumentar" name="cantidad" class="form-control" min="1" required inputmode="numeric">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Aumentar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Reporte de Fallas -->
    <div class="modal fade" id="modalFalla" tabindex="-1" aria-labelledby="modalFallaTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formFalla">
                    @csrf
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalFallaTitle"><i class="bi bi-clipboard2-plus" aria-hidden="true"></i> Reporte de fallas / uso de materiales</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <h3 class="form-section-title">Vehículo</h3>
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label for="lugarFalla" class="form-label">Lugar</label>
                                <input type="text" id="lugarFalla" class="form-control" value="{{ Auth::user()->lugar->nombre ?? 'Sin lugar asignado' }}" readonly>
                                <input type="hidden" name="id_lugar" value="{{ Auth::user()->id_lugar }}">
                            </div>
                            <div class="col-md-7">
                                <label for="vehiculo_eco" class="form-label">Vehículo (ECO)</label>
                                <select id="vehiculo_eco" name="vehiculo_eco" class="form-select" aria-describedby="vehiculoEcoHelp">
                                    <option value="">Cargando vehículos...</option>
                                </select>
                                <div class="form-text" id="vehiculoEcoHelp">Los datos del vehículo se llenan automáticamente.</div>
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="campo_eco" class="form-label">No. ECO</label>
                                <input type="text" name="eco" id="campo_eco" class="form-control ft-code" readonly>
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="campo_placas" class="form-label">Placas</label>
                                <input type="text" name="placas" id="campo_placas" class="form-control ft-code" readonly>
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="campo_marca" class="form-label">Marca</label>
                                <input type="text" name="marca" id="campo_marca" class="form-control" readonly>
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="campo_anio" class="form-label">Año</label>
                                <input type="text" name="anio" id="campo_anio" class="form-control" readonly>
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="campo_km" class="form-label">KM</label>
                                <input type="text" name="km" id="campo_km" class="form-control" readonly>
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="campo_fecha" class="form-label">Fecha</label>
                                <input type="date" name="fecha" id="campo_fecha" class="form-control">
                            </div>
                        </div>

                        <h3 class="form-section-title">Servicio</h3>
                        <div class="mb-3">
                            <label for="campo_conductor" class="form-label">Nombre del conductor</label>
                            <input type="text" name="nombre_conductor" id="campo_conductor" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="falla_descripcion" class="form-label">Descripción del servicio / fallo</label>
                            <textarea name="descripcion" id="falla_descripcion" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="mb-1">
                            <label for="falla_observaciones" class="form-label">Observaciones técnicas del trabajo realizado</label>
                            <textarea name="observaciones" id="falla_observaciones" rows="3" class="form-control"></textarea>
                        </div>

                        <h3 class="form-section-title">Materiales a utilizar</h3>
                        <div class="mb-3">
                            <label for="materialBuscar" class="form-label">Buscar material</label>
                            <input type="text" id="materialBuscar" class="form-control" list="materialesList"
                                placeholder="Clave o descripción">
                            <datalist id="materialesList">
                                @foreach($catalogo as $material)
                                    <option data-id="{{ $material->id_material }}"
                                        value="{{ $material->clave_material }} - {{ $material->descripcion }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="border rounded-3 mb-3 overflow-hidden">
                            <table class="table table-sm mb-0" id="materialesTable">
                                <thead>
                                    <tr>
                                        <th scope="col">Clave</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col" style="width: 7rem">Cantidad</th>
                                        <th scope="col" class="ft-col-actions"><span class="visually-hidden">Acción</span></th>
                                    </tr>
                                </thead>
                                <tbody id="materialesTableBody"></tbody>
                            </table>
                        </div>

                        <div class="mb-1">
                            <label for="correo_destino" class="form-label required">Correo para envío del reporte</label>
                            <input type="email" name="correo_destino" id="correo_destino" class="form-control"
                                placeholder="correo@ejemplo.com" required aria-describedby="correoDestinoHelp">
                            <div class="form-text" id="correoDestinoHelp">Se enviará el PDF del reporte a este correo.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmitFalla">
                            <i class="bi bi-send me-1" aria-hidden="true"></i> Enviar reporte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        // Escapa texto antes de insertarlo como HTML.
        function esc(value) {
            return $('<div>').text(value == null ? '' : String(value)).html();
        }

        // Mensaje legible a partir de una respuesta de error de Laravel.
        function ajaxError(xhr, fallback = 'No se pudo conectar con el servidor') {
            const res = xhr && xhr.responseJSON;
            if (res?.errors) return Object.values(res.errors).flat().join('\n');
            return res?.error || res?.message || fallback;
        }

        // Campos del formulario de material (registro y edición).
        function materialFields(m = {}) {
            const v = (k) => esc(m[k] ?? '');
            return `
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="clave_material" class="form-label required">Clave material</label>
                        <input type="text" id="clave_material" name="clave_material" class="form-control ft-code" value="${v('clave_material')}" required>
                    </div>
                    <div class="col-sm-6">
                        <label for="clasificacion" class="form-label">Clasificación</label>
                        <input type="text" id="clasificacion" name="clasificacion" class="form-control" value="${v('clasificacion')}">
                    </div>
                    <div class="col-12">
                        <label for="descripcion" class="form-label required">Descripción</label>
                        <input type="text" id="descripcion" name="descripcion" class="form-control" value="${v('descripcion')}" required>
                    </div>
                    <div class="col-12">
                        <label for="generico" class="form-label">Genérico</label>
                        <input type="text" id="generico" name="generico" class="form-control" value="${v('generico')}">
                    </div>
                    <div class="col-sm-6">
                        <label for="existencia" class="form-label required">Existencia</label>
                        <input type="number" id="existencia" name="existencia" class="form-control" value="${v('existencia')}" min="0" required inputmode="numeric">
                    </div>
                    <div class="col-sm-6">
                        <label for="costo_promedio" class="form-label required">Costo promedio</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" id="costo_promedio" name="costo_promedio" class="form-control" value="${v('costo_promedio')}" step="0.01" min="0" required inputmode="decimal">
                        </div>
                    </div>
                </div>`;
        }

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Cargar notificaciones al iniciar
            @if(Auth::user()->id_lugar)
                loadNotifications();
                updateNotificationCount();
            @endif

            // Abrir modal de notificaciones
            $('#notificationBell').click(function () {
                $('#modalNotificaciones').modal('show');
                loadNotifications();
            });

            // Actualizar notificaciones
            $('#btnRefreshNotifications').click(function () {
                loadNotifications();
            });

            // Cargar notificaciones
            function loadNotifications() {
                $('#notificationsContainer').html(`
                    <div class="text-center py-4">
                        <div class="loading-spinner"></div>
                        <p class="mt-2 mb-0 text-body-secondary">Cargando notificaciones...</p>
                    </div>
                `);

                $.get('/notificaciones/pendientes')
                    .done(function (response) {
                        if (response.success) {
                            renderNotifications(response.notificaciones, response.total);
                        } else {
                            $('#notificationsContainer').html(`
                                <div class="no-notifications">
                                    <i class="bi bi-bell-slash" aria-hidden="true"></i>
                                    <h5>Error al cargar notificaciones</h5>
                                    <p class="mb-0">${esc(response.error || 'Intenta de nuevo más tarde.')}</p>
                                </div>
                            `);
                        }
                    })
                    .fail(function () {
                        $('#notificationsContainer').html(`
                            <div class="no-notifications">
                                <i class="bi bi-bell-slash" aria-hidden="true"></i>
                                <h5>Error al cargar notificaciones</h5>
                                <p class="mb-0">No se pudo conectar con el servidor.</p>
                            </div>
                        `);
                    });
            }

            // Renderizar notificaciones
            function renderNotifications(notificaciones, total) {
                const userLugarId = {{ Auth::user()->id_lugar ?? 'null' }};
                // Verificación adicional en el frontend (el backend ya filtra por id_lugar)
                const filteredNotificaciones = notificaciones.filter(notif => notif.id_lugar === userLugarId);

                $('#modalNotificationCount').text(filteredNotificaciones.length);

                if (filteredNotificaciones.length === 0) {
                    $('#notificationsContainer').html(`
                        <div class="no-notifications">
                            <i class="bi bi-bell-slash" aria-hidden="true"></i>
                            <h5>No hay notificaciones pendientes</h5>
                            <p class="mb-0">Todas las notificaciones han sido procesadas.</p>
                        </div>
                    `);
                    return;
                }

                let html = '';
                filteredNotificaciones.forEach(function (notif) {
                    html += `
                        <article class="notification-item" data-id="${notif.id}">
                            <div class="notification-header">
                                <span class="notification-title">${esc(notif.resumen)}</span>
                                <span class="notification-time">${esc(notif.tiempo_transcurrido)}</span>
                            </div>
                            <div class="notification-details">
                                <strong>Usuario:</strong> ${esc(notif.usuario_reporta)}<br>
                                <strong>Lugar:</strong> ${esc(notif.lugar)}<br>
                                <strong>Vehículo:</strong> ${esc(notif.vehiculo || 'No especificado')}<br>
                                <strong>Materiales:</strong> ${esc(notif.materiales_count)} items<br>
                                <strong>Fecha:</strong> ${esc(notif.fecha_creacion)}
                            </div>
                            <div class="notification-actions">
                                <button class="btn btn-view btn-sm" onclick="verDetalleNotificacion(${notif.id})">
                                    <i class="bi bi-eye me-1" aria-hidden="true"></i> Ver detalles
                                </button>
                                <button class="btn btn-approve btn-sm" onclick="aprobarNotificacion(${notif.id})">
                                    <i class="bi bi-check2 me-1" aria-hidden="true"></i> Aprobar
                                </button>
                                <button class="btn btn-reject btn-sm" onclick="rechazarNotificacion(${notif.id})">
                                    <i class="bi bi-x-lg me-1" aria-hidden="true"></i> Rechazar
                                </button>
                            </div>
                        </article>
                    `;
                });

                $('#notificationsContainer').html(html);
            }

            // Actualizar contador de notificaciones
            function updateNotificationCount() {
                $.get('/notificaciones/contador')
                    .done(function (response) {
                        if (response.success) {
                            updateNotificationBadge(response.count);
                        }
                    });
            }

            // Actualizar badge de notificaciones
            function updateNotificationBadge(count) {
                if (count > 0) {
                    $('#notificationBadge').text(count).removeClass('hidden');
                    $('#notificationBell').addClass('has-notifications')
                        .attr('aria-label', `Notificaciones pendientes: ${count}`);
                } else {
                    $('#notificationBadge').addClass('hidden');
                    $('#notificationBell').removeClass('has-notifications')
                        .attr('aria-label', 'Notificaciones pendientes: ninguna');
                }
            }

            // Ver detalles de notificación
            window.verDetalleNotificacion = function (id) {
                $.get(`/notificaciones/${id}`)
                    .done(function (response) {
                        if (response.success) {
                            const notif = response.notificacion;
                            let materialesHtml = '';
                            if (notif.materiales && notif.materiales.length > 0) {
                                materialesHtml = '<h3 class="form-section-title">Materiales</h3><ul class="mb-0">';
                                notif.materiales.forEach(function (material) {
                                    materialesHtml += `<li>${esc(material.descripcion)} <span class="text-body-secondary">(Cantidad: ${esc(material.cantidad)})</span></li>`;
                                });
                                materialesHtml += '</ul>';
                            } else {
                                materialesHtml = '<p class="mt-3 mb-0 text-body-secondary">No se especificaron materiales.</p>';
                            }

                            const filas = [
                                ['ID', notif.id],
                                ['Lugar', notif.lugar_nombre],
                                ['Vehículo', `${notif.eco || ''} ${notif.placas || ''} ${notif.marca || ''}`.trim() || 'No especificado'],
                                ['Año', notif.anio || 'No especificado'],
                                ['KM', notif.km || 'No especificado'],
                                ['Fecha', notif.fecha || 'No especificada'],
                                ['Conductor', notif.nombre_conductor || 'No especificado'],
                                ['Descripción', notif.descripcion || 'No especificada'],
                                ['Observaciones', notif.observaciones || 'No especificadas'],
                                ['Usuario reporta', notif.usuario_reporta],
                                ['Correo reporta', notif.correo_reporta],
                                ['Correo destino', notif.correo_destino || 'No especificado'],
                                ['Fecha creación', notif.fecha_creacion],
                                ['Estado', notif.estado],
                            ];

                            $('#detalleNotificacionBody').html(
                                '<dl class="ft-detail-list">' +
                                filas.map(([k, val]) => `<dt>${k}</dt><dd>${esc(val)}</dd>`).join('') +
                                '</dl>' + materialesHtml
                            );
                            $('#modalDetalleNotificacion').modal('show');
                        } else {
                            Swal.fire('Error', response.error || 'No se pudo cargar la notificación', 'error');
                        }
                    })
                    .fail(function () {
                        Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                    });
            };

            // Aprobar notificación
            window.aprobarNotificacion = function (id) {
                $('#modalAprobarNotificacion').modal('show');
                $('#passwordAprobar').val('');
                $('#comentariosAprobar').val('');
                $('#errorAprobar').addClass('d-none');
                $('#btnConfirmarAprobar').prop('disabled', true);
                $('#datosUsuarioAutoriza').hide();
                $('#passwordValidating').hide();
                $('#passwordValidSuccess').hide();

                $('#btnConfirmarAprobar').off('click').on('click', function () {
                    const password = $('#passwordAprobar').val();
                    const comentarios = $('#comentariosAprobar').val();

                    if (!password || !comentarios) {
                        $('#errorAprobarMessage').text('Por favor, completa todos los campos.');
                        $('#errorAprobar').removeClass('d-none');
                        return;
                    }

                    $('#btnConfirmarAprobar').addClass('loading').prop('disabled', true);

                    $.ajax({
                        url: `/notificaciones/${id}/aprobar`,
                        method: 'POST',
                        data: { password, comentarios },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Éxito', response.message, 'success').then(() => {
                                    $('#modalAprobarNotificacion').modal('hide');
                                    loadNotifications();
                                    updateNotificationCount();
                                });
                            } else {
                                $('#errorAprobarMessage').text(response.error || 'Error al aprobar notificación');
                                $('#errorAprobar').removeClass('d-none');
                            }
                        },
                        error: function () {
                            $('#errorAprobarMessage').text('No se pudo conectar con el servidor');
                            $('#errorAprobar').removeClass('d-none');
                        },
                        complete: function () {
                            $('#btnConfirmarAprobar').removeClass('loading').prop('disabled', false);
                        }
                    });
                });

                // Validar contraseña en tiempo real
                let timeout;
                $('#passwordAprobar').off('input').on('input', function () {
                    clearTimeout(timeout);
                    const password = $(this).val();
                    $('#errorAprobar').addClass('d-none');
                    if (password.length > 0) {
                        $('#passwordValidating').show();
                        $('#passwordValidSuccess').hide();
                        timeout = setTimeout(function () {
                            $.post('{{ route('verificar-password') }}', { password })
                                .done(function (response) {
                                    if (response.success) {
                                        $('#passwordValidating').hide();
                                        $('#passwordValidSuccess').show();
                                        $('#btnConfirmarAprobar').prop('disabled', false);
                                        $('#datosUsuarioAutoriza').show();
                                        $('#nombreUsuarioAutoriza').text(response.user?.nombre || @json(Auth::user()->nombre));
                                        $('#correoUsuarioAutoriza').text(response.user?.email || @json(Auth::user()->correo));
                                    } else {
                                        $('#passwordValidating').hide();
                                        $('#passwordValidSuccess').hide();
                                        $('#errorAprobarMessage').text('Contraseña incorrecta');
                                        $('#errorAprobar').removeClass('d-none');
                                        $('#btnConfirmarAprobar').prop('disabled', true);
                                    }
                                })
                                .fail(function (xhr) {
                                    $('#passwordValidating').hide();
                                    $('#passwordValidSuccess').hide();
                                    $('#btnConfirmarAprobar').prop('disabled', true);
                                    $('#errorAprobarMessage').text(xhr.status === 401 ? 'Contraseña incorrecta' : 'Error al validar contraseña');
                                    $('#errorAprobar').removeClass('d-none');
                                });
                        }, 500);
                    } else {
                        $('#passwordValidating').hide();
                        $('#passwordValidSuccess').hide();
                        $('#btnConfirmarAprobar').prop('disabled', true);
                    }
                });

                // Mostrar/Ocultar contraseña
                $('#btnTogglePasswordAprobar').off('click').on('click', function () {
                    const passwordInput = $('#passwordAprobar');
                    const icon = $('#iconTogglePasswordAprobar');
                    if (passwordInput.attr('type') === 'password') {
                        passwordInput.attr('type', 'text');
                        icon.removeClass('bi-eye').addClass('bi-eye-slash');
                    } else {
                        passwordInput.attr('type', 'password');
                        icon.removeClass('bi-eye-slash').addClass('bi-eye');
                    }
                });
            };

            // Rechazar notificación
            window.rechazarNotificacion = function (id) {
                $('#modalRechazarNotificacion').modal('show');
                $('#comentariosRechazar').val('');
                $('#errorRechazar').addClass('d-none');

                $('#btnConfirmarRechazar').off('click').on('click', function () {
                    const comentarios = $('#comentariosRechazar').val();
                    if (!comentarios) {
                        $('#errorRechazarMessage').text('Por favor, ingresa el motivo del rechazo.');
                        $('#errorRechazar').removeClass('d-none');
                        return;
                    }

                    $('#btnConfirmarRechazar').addClass('loading').prop('disabled', true);

                    $.ajax({
                        url: `/notificaciones/${id}/rechazar`,
                        method: 'POST',
                        data: { comentarios },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Éxito', response.message, 'success').then(() => {
                                    $('#modalRechazarNotificacion').modal('hide');
                                    loadNotifications();
                                    updateNotificationCount();
                                });
                            } else {
                                $('#errorRechazarMessage').text(response.error || 'Error al rechazar notificación');
                                $('#errorRechazar').removeClass('d-none');
                            }
                        },
                        error: function () {
                            $('#errorRechazarMessage').text('No se pudo conectar con el servidor');
                            $('#errorRechazar').removeClass('d-none');
                        },
                        complete: function () {
                            $('#btnConfirmarRechazar').removeClass('loading').prop('disabled', false);
                        }
                    });
                });
            };

            // Cargar vehículos para el modal de reporte de fallas
            $('#modalFalla').on('show.bs.modal', function () {
                $.get('{{ route('materials.vehiculos.lugar', ['id_lugar' => Auth::user()->id_lugar ?? 0]) }}').done(function (response) {
                    if (Array.isArray(response.vehiculos)) {
                        let options = '<option value="">Selecciona un vehículo</option>';
                        response.vehiculos.forEach(function (vehiculo) {
                            options += `<option value="${esc(vehiculo.eco)}"
                                data-eco="${esc(vehiculo.eco)}"
                                data-placas="${esc(vehiculo.placas)}"
                                data-marca="${esc(vehiculo.marca)}"
                                data-anio="${esc(vehiculo.anio)}"
                                data-km="${esc(vehiculo.kilometraje)}">
                                ${esc(vehiculo.eco)} - ${esc(vehiculo.marca)}
                            </option>`;
                        });
                        $('#vehiculo_eco').html(response.vehiculos.length ? options : '<option value="">No hay vehículos activos en tu lugar</option>');
                    }
                }).fail(function (xhr) {
                    $('#vehiculo_eco').html('<option value="">No se pudieron cargar los vehículos</option>');
                });
            });

            // Llenar campos del vehículo al seleccionar
            $('#vehiculo_eco').change(function () {
                const selected = $(this).find(':selected');
                $('#campo_eco').val(selected.data('eco') || '');
                $('#campo_placas').val(selected.data('placas') || '');
                $('#campo_marca').val(selected.data('marca') || '');
                $('#campo_anio').val(selected.data('anio') || '');
                $('#campo_km').val(selected.data('km') || '');
            });

            // Agregar material al reporte de fallas
            $('#materialBuscar').on('input', function () {
                const selectedOption = $('#materialesList option').filter(function () {
                    return $(this).val() === $('#materialBuscar').val();
                });
                if (selectedOption.length) {
                    const id = selectedOption.data('id');
                    $.get(`/materials/${id}`).done(function (response) {
                        if (response.data) {
                            const material = response.data;
                            const row = `
                                <tr data-id="${material.id_material}">
                                    <td><span class="ft-code">${esc(material.clave_material)}</span></td>
                                    <td>${esc(material.descripcion)}</td>
                                    <td><input type="number" class="form-control form-control-sm cantidad" min="1" max="${material.existencia}" value="1" aria-label="Cantidad de ${esc(material.clave_material)}"></td>
                                    <td class="ft-col-actions"><button type="button" class="btn btn-ghost btn-sm btn-icon text-danger btnEliminarMaterial" aria-label="Quitar material"><i class="bi bi-trash3" aria-hidden="true"></i></button></td>
                                </tr>`;
                            $('#materialesTableBody').append(row);
                            $('#materialBuscar').val('');
                        }
                    });
                }
            });

            // Eliminar material del reporte
            $(document).on('click', '.btnEliminarMaterial', function () {
                $(this).closest('tr').remove();
            });

            // Enviar reporte de falla
            $('#formFalla').submit(function (e) {
                e.preventDefault();
                const materiales = [];
                $('#materialesTableBody tr').each(function () {
                    const id = $(this).data('id');
                    const cantidad = $(this).find('.cantidad').val();
                    materiales.push({ id, cantidad });
                });

                const data = {
                    id_lugar: $('#formFalla [name="id_lugar"]').val(),
                    eco: $('#campo_eco').val(),
                    placas: $('#campo_placas').val(),
                    marca: $('#campo_marca').val(),
                    ano: $('#campo_anio').val(),
                    km: $('#campo_km').val(),
                    fecha: $('#campo_fecha').val(),
                    nombre_conductor: $('#campo_conductor').val(),
                    descripcion: $('#formFalla [name="descripcion"]').val(),
                    observaciones: $('#formFalla [name="observaciones"]').val(),
                    usuario_reporta_id: {{ Auth::user()->id_usuario }},
                    nombre_usuario_reporta: @json(Auth::user()->nombre),
                    correo_usuario_reporta: @json(Auth::user()->correo),
                    correo_destino: $('#correo_destino').val(),
                    materials: JSON.stringify(materiales)
                };

                const submitBtn = $('#btnSubmitFalla').addClass('loading').prop('disabled', true);

                $.ajax({
                    url: '/notificaciones',
                    method: 'POST',
                    data: data,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Éxito', response.message, 'success').then(() => {
                                $('#modalFalla').modal('hide');
                                $('#formFalla')[0].reset();
                                $('#materialesTableBody').empty();
                                updateNotificationCount();
                            });
                        } else {
                            Swal.fire('Error', response.error || 'Error al enviar reporte', 'error');
                        }
                    },
                    error: function (xhr) {
                        Swal.fire('Error', ajaxError(xhr, 'Error al enviar reporte'), 'error');
                    },
                    complete: function () {
                        submitBtn.removeClass('loading').prop('disabled', false);
                    }
                });
            });

            // Subir archivo Kardex
            $('#formCardex').submit(function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                $('#btnSubirCardex').prop('disabled', true).addClass('loading');
                $('#progreso').show();
                $.ajax({
                    url: '{{ route('materials.import') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function () {
                        const xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function (e) {
                            if (e.lengthComputable) {
                                const percent = (e.loaded / e.total) * 100;
                                $('#progreso .progress-bar').css('width', percent + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function (response) {
                        Swal.fire('Éxito', response.message || 'Archivo procesado', 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire('Error', ajaxError(xhr, 'Error al procesar el archivo'), 'error');
                    },
                    complete: function () {
                        $('#btnSubirCardex').prop('disabled', false).removeClass('loading');
                        $('#progreso').hide();
                        $('#progreso .progress-bar').css('width', '0%');
                    }
                });
            });

            // Registrar material
            $('#btnNuevoMaterial').click(function () {
                $('#materialId').val('');
                $('#modalMaterialTitle').html('<i class="bi bi-plus-circle" aria-hidden="true"></i> Registrar material');
                $('#modalMaterialBody').html(materialFields());
                $('#modalMaterialFooter').html(`
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                `);
            });

            // Ver material
            $(document).on('click', '.btnVer', function () {
                const id = $(this).data('id');
                $('#modalMaterialTitle').html('<i class="bi bi-box-seam" aria-hidden="true"></i> Detalles del material');
                $('#modalMaterialBody').html('<div class="text-center py-4"><div class="loading-spinner"></div></div>');
                $('#modalMaterialFooter').html('<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>');
                $.get(`/materials/${id}`).done(function (response) {
                    if (response.data) {
                        const material = response.data;
                        const filas = [
                            ['ID', esc(material.id_material)],
                            ['Clave', `<span class="ft-code">${esc(material.clave_material)}</span>`],
                            ['Descripción', esc(material.descripcion)],
                            ['Genérico', esc(material.generico || 'No especificado')],
                            ['Clasificación', esc(material.clasificacion || 'No especificada')],
                            ['Existencia', `<span class="ft-num fw-semibold">${esc(material.existencia)}</span>`],
                            ['Costo promedio', `<span class="ft-num">$${esc(material.costo_promedio)}</span>`],
                        ];
                        $('#modalMaterialBody').html(
                            '<dl class="ft-detail-list">' + filas.map(([k, v]) => `<dt>${k}</dt><dd>${v}</dd>`).join('') + '</dl>'
                        );
                    }
                });
            });

            // Editar material
            $(document).on('click', '.btnEditar', function () {
                const id = $(this).data('id');
                $('#modalMaterialTitle').html('<i class="bi bi-pencil" aria-hidden="true"></i> Editar material');
                $('#modalMaterialBody').html('<div class="text-center py-4"><div class="loading-spinner"></div></div>');
                $('#modalMaterialFooter').html('');
                $.get(`/materials/${id}`).done(function (response) {
                    if (response.data) {
                        const material = response.data;
                        $('#materialId').val(material.id_material);
                        $('#modalMaterialBody').html(materialFields(material));
                        $('#modalMaterialFooter').html(`
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        `);
                    }
                });
            });

            // Guardar/Actualizar material
            $('#formMaterial').submit(function (e) {
                e.preventDefault();
                const id = $('#materialId').val();
                const url = id ? `/materials/${id}` : '/materials';
                const method = id ? 'PUT' : 'POST';
                const data = $(this).serialize();

                $.ajax({
                    url: url,
                    method: method,
                    data: data,
                    success: function (response) {
                        $('#modalMaterial').modal('hide');
                        Swal.fire('Éxito', response.message || 'Material guardado', 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire('Error', ajaxError(xhr, 'Error al procesar el material'), 'error');
                    }
                });
            });

            // Aumentar existencia
            $(document).on('click', '.btnAumentar', function () {
                const id = $(this).data('id');
                $.get(`/materials/${id}`).done(function (response) {
                    if (response.data) {
                        const material = response.data;
                        $('#materialIdAumentar').val(id);
                        $('#materialNombreAumentar').text(material.descripcion);
                        $('#existenciaActualAumentar').text(material.existencia);
                        $('#modalAumentar').modal('show');
                    }
                });
            });

            $('#formAumentar').submit(function (e) {
                e.preventDefault();
                const id = $('#materialIdAumentar').val();
                const data = $(this).serialize();

                $.ajax({
                    url: `/materials/${id}/aumentar`,
                    method: 'POST',
                    data: data,
                    success: function (response) {
                        $('#modalAumentar').modal('hide');
                        Swal.fire('Éxito', response.message || 'Existencia actualizada', 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire('Error', ajaxError(xhr, 'Error al aumentar existencia'), 'error');
                    }
                });
            });

            // Reporte de fallas
            $('#btnReporteFallas').click(function () {
                $('#modalFalla').modal('show');
            });

            // Eliminar material
            $(document).on('click', '.btnEliminar', function () {
                const id = $(this).data('id');
                $('#btnConfirmarEliminar').data('id', id);
                $('#modalEliminar').modal('show');
            });

            $('#btnConfirmarEliminar').click(function () {
                const id = $(this).data('id');
                $.ajax({
                    url: `/materials/${id}`,
                    method: 'DELETE',
                    success: function (response) {
                        $('#modalEliminar').modal('hide');
                        Swal.fire('Éxito', response.message || 'Material eliminado', 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire('Error', ajaxError(xhr, 'Error al eliminar material'), 'error');
                    }
                });
            });
        });
    </script>
@endpush
