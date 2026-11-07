@extends('layouts.app')

@section('title', 'Materiales')

@guest
    @push('styles')
        <script>
            window.location.href = '/login';
        </script>
    @endpush
@endguest

@auth
    @push('user-menu')
        <li>
            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalCambiarNombre">
                <i class="bi bi-person-gear" aria-hidden="true"></i> Cambiar nombre
            </button>
        </li>
        <li>
            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalCambiarPassword">
                <i class="bi bi-key" aria-hidden="true"></i> Cambiar contraseña
            </button>
        </li>
        <li>
            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalCambiarFoto">
                <i class="bi bi-camera" aria-hidden="true"></i> Cambiar imagen de perfil
            </button>
        </li>
        <li><hr class="dropdown-divider"></li>
    @endpush
@endauth

@push('before-shell')
    <!-- Acceso por secuencia de teclado (funcionalidad existente) -->
    <div class="secret-indicator" id="secretIndicator" title="Comandos secretos: ↑↓k↓↑" aria-hidden="true">
        <i class="bi bi-keyboard"></i>
    </div>
    <div class="secret-sequence" id="secretSequence" role="status" aria-live="polite">
        Secuencia: <span id="sequenceDisplay"></span>
    </div>
    <button class="admin-access-btn btn" id="adminAccessBtn" onclick="location.href='/materials'">
        <i class="bi bi-shield-lock me-1" aria-hidden="true"></i> Acceso administrador
    </button>

    <!-- Aviso de éxito -->
    <div class="notification-success" id="notificationSuccess" role="status" aria-live="polite">
        <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
        <span id="notificationMessage">¡Notificación enviada correctamente!</span>
    </div>
@endpush

@section('content')
    <x-page-header
        eyebrow="Inventario"
        icon="bi-box-seam"
        title="Materiales"
        :description="'Consulta existencias y reporta el uso de materiales · ' . (optional(optional(Auth::user())->lugar)->nombre ?? 'Todos los lugares')">
        <x-slot:actions>
            <a href="{{ route('materiales.export') }}" class="btn btn-outline-secondary">
                <i class="bi bi-download me-1" aria-hidden="true"></i> Exportar Excel
            </a>
            <button class="btn btn-primary" id="btnReporteFallas">
                <i class="bi bi-clipboard2-plus me-1" aria-hidden="true"></i> Reportar uso de materiales
            </button>
        </x-slot:actions>
    </x-page-header>

    @if(session('error'))
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-shield-lock" aria-hidden="true"></i><div>{{ session('error') }}</div>
        </div>
    @endif

    <section class="ft-panel" aria-label="Lista de materiales">
        <form class="ft-toolbar" action="{{ route('materiales.index') }}" method="GET" role="search">
            <div class="ft-toolbar__grow ft-search">
                <i class="bi bi-search" aria-hidden="true"></i>
                <label for="buscarMaterial" class="visually-hidden">Buscar material</label>
                <input class="form-control" type="search" id="buscarMaterial" name="query" placeholder="Buscar material" value="{{ request('query') }}">
            </div>
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            @if(request('query'))
                <a href="{{ route('materiales.index') }}" class="btn btn-ghost">Limpiar</a>
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
                    @if(isset($materiales) && $materiales->count() > 0)
                        @foreach($materiales as $material)
                            <tr data-id="{{ $material->id_material }}">
                                <td data-label="ID" class="text-body-secondary">{{ $material->id_material }}</td>
                                <td data-label="Clave">{{ $material->clave_material }}</td>
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
                                            title="Reportar uso" aria-label="Reportar uso de {{ $material->clave_material }}">
                                            <i class="bi bi-dash" aria-hidden="true"></i>
                                        </button>
                                        <span class="ft-stock__value">{{ $material->existencia }}</span>
                                    </div>
                                </td>
                                <td data-label="Costo prom." class="text-end text-nowrap">${{ number_format((float) $material->costo_promedio, 2) }}</td>
                                <td class="ft-col-actions">
                                    <div class="ft-table-actions">
                                        <button class="btn btn-ghost btn-sm btn-icon btnVer" data-id="{{ $material->id_material }}"
                                            title="Ver detalles" aria-label="Ver {{ $material->clave_material }}">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="empty-state">
                                <i class="bi bi-box-seam" aria-hidden="true"></i>
                                <h4>{{ request('query') ? 'Sin coincidencias' : 'No hay materiales disponibles' }}</h4>
                                <p>{{ request('query') ? 'Prueba con otra clave o descripción.' : 'Cuando se registren materiales en tu lugar aparecerán aquí.' }}</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if(isset($materiales) && method_exists($materiales, 'links') && $materiales->hasPages())
            <div class="ft-panel__footer ft-pagination">
                {{ $materiales->appends(['query' => request('query')])->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection

@push('modals')
    <!-- Modal Material (View) -->
    <div class="modal fade" id="modalMaterial" tabindex="-1" aria-labelledby="modalMaterialTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="modalMaterialTitle"></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="modalMaterialBody"></div>
                <div class="modal-footer" id="modalMaterialFooter"></div>
            </div>
        </div>
    </div>

    <!-- Modal de reporte con autocompletado de vehículos -->
    <div class="modal fade" id="modalReporte" tabindex="-1" aria-labelledby="modalReporteTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formReporte">
                    @csrf
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalReporteTitle"><i class="bi bi-clipboard2-plus" aria-hidden="true"></i> Reporte de uso de materiales</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="id_lugar_reporte" class="form-label required">Lugar</label>
                            <select id="id_lugar_reporte" name="id_lugar" class="form-select" required>
                                <option value="">Selecciona un lugar</option>
                                @if(isset($lugares))
                                    @foreach($lugares as $lugar)
                                        <option value="{{ $lugar->id_lugar }}" @selected(Auth::user()->id_lugar == $lugar->id_lugar)>
                                            {{ $lugar->nombre }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <h3 class="form-section-title">Vehículo</h3>
                        <div class="vehicle-search-container mb-3">
                            <label for="vehicleSearch" class="form-label">Buscar vehículo</label>
                            <div class="ft-search">
                                <i class="bi bi-search" aria-hidden="true"></i>
                                <input type="text" id="vehicleSearch" class="form-control vehicle-search-input"
                                    placeholder="ECO, placas, marca o modelo" autocomplete="off" aria-describedby="vehicleSearchHelp">
                            </div>
                            <div class="form-text" id="vehicleSearchHelp">Al elegir un vehículo se llenan sus datos.</div>
                            <div class="vehicle-results-dropdown" id="vehicleResultsDropdown" role="listbox" aria-label="Vehículos encontrados"></div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6 col-md-4">
                                <label for="ecoField" class="form-label">No. ECO</label>
                                <input type="text" name="eco" id="ecoField" class="form-control ft-code">
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="placasField" class="form-label">Placas</label>
                                <input type="text" name="placas" id="placasField" class="form-control ft-code">
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="marcaField" class="form-label">Marca</label>
                                <input type="text" name="marca" id="marcaField" class="form-control">
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="anoField" class="form-label">Año</label>
                                <input type="text" name="ano" id="anoField" class="form-control">
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="kmField" class="form-label">KM</label>
                                <input type="text" name="km" id="kmField" class="form-control">
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="fechaReporte" class="form-label">Fecha</label>
                                <input type="date" name="fecha" id="fechaReporte" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <h3 class="form-section-title">Servicio</h3>
                        <div class="mb-3">
                            <label for="conductorField" class="form-label">Nombre del conductor</label>
                            <input type="text" name="nombre_conductor" id="conductorField" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="descripcionReporte" class="form-label">Descripción del servicio / trabajo realizado</label>
                            <textarea name="descripcion" id="descripcionReporte" rows="3" class="form-control" placeholder="Describe el trabajo realizado o la falla atendida"></textarea>
                        </div>
                        <div class="mb-1">
                            <label for="observacionesReporte" class="form-label">Observaciones técnicas</label>
                            <textarea name="observaciones" id="observacionesReporte" rows="3" class="form-control" placeholder="Observaciones adicionales del trabajo"></textarea>
                        </div>

                        <h3 class="form-section-title">Materiales utilizados</h3>
                        <div class="material-search-container mb-3">
                            <label for="materialSearch" class="form-label">Buscar material</label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="ft-search flex-grow-1">
                                    <i class="bi bi-search" aria-hidden="true"></i>
                                    <input type="text" id="materialSearch" class="form-control material-search-input"
                                        placeholder="ID, clave, descripción, genérico o clasificación" autocomplete="off">
                                </div>
                                <button type="button" id="btnAgregarMaterial" class="btn btn-add-material" disabled>
                                    <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Agregar
                                </button>
                            </div>
                            <div class="search-results-dropdown" id="searchResultsDropdown" role="listbox" aria-label="Materiales encontrados"></div>
                        </div>

                        <div class="border rounded-3 mb-3 overflow-hidden">
                            <table class="table table-sm mb-0" id="selectedMaterialsTable">
                                <thead>
                                    <tr>
                                        <th scope="col">Material</th>
                                        <th scope="col" style="width: 6.5rem">Cantidad</th>
                                        <th scope="col" class="ft-col-actions"><span class="visually-hidden">Acciones</span></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <div class="mb-1">
                            <label for="usuarioReporta" class="form-label required">Usuario que reporta</label>
                            <select name="usuario_reporta_id" id="usuarioReporta" class="form-select" required>
                                <option value="">Selecciona el usuario que reporta</option>
                            </select>
                            <input type="hidden" name="nombre_usuario_reporta" id="nombreUsuarioReporta">
                            <input type="hidden" name="correo_usuario_reporta" id="correoUsuarioReporta">
                        </div>

                        <input type="hidden" name="materials" id="materialsData">
                        <input type="hidden" name="vehicle_id" id="vehicleIdField">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnEnviarReporte" class="btn btn-primary">
                            <i class="bi bi-send me-1" aria-hidden="true"></i> Enviar notificación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cambiar nombre -->
    <div class="modal fade" id="modalCambiarNombre" tabindex="-1" aria-labelledby="modalCambiarNombreTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="modalCambiarNombreTitle"><i class="bi bi-person-gear" aria-hidden="true"></i> Cambiar nombre</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formCambiarNombre">
                    @csrf
                    <div class="modal-body">
                        <label for="nuevoNombre" class="form-label required">Nuevo nombre</label>
                        <input type="text" class="form-control" id="nuevoNombre" name="nombre"
                            value="{{ Auth::user()->nombre ?? '' }}" required autocomplete="name">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cambiar contraseña -->
    <div class="modal fade" id="modalCambiarPassword" tabindex="-1" aria-labelledby="modalCambiarPasswordTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="modalCambiarPasswordTitle"><i class="bi bi-key" aria-hidden="true"></i> Cambiar contraseña</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formCambiarPassword">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="passwordActual" class="form-label required">Contraseña actual</label>
                            <input type="password" class="form-control" id="passwordActual" name="current_password" required autocomplete="current-password">
                        </div>
                        <div class="mb-3">
                            <label for="passwordNueva" class="form-label required">Nueva contraseña</label>
                            <input type="password" class="form-control" id="passwordNueva" name="password" required minlength="6" autocomplete="new-password" aria-describedby="passwordNuevaHelp">
                            <div class="form-text" id="passwordNuevaHelp">Mínimo 6 caracteres.</div>
                        </div>
                        <div class="mb-1">
                            <label for="passwordConfirmar" class="form-label required">Confirmar nueva contraseña</label>
                            <input type="password" class="form-control" id="passwordConfirmar" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cambiar foto -->
    <div class="modal fade" id="modalCambiarFoto" tabindex="-1" aria-labelledby="modalCambiarFotoTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="modalCambiarFotoTitle"><i class="bi bi-camera" aria-hidden="true"></i> Cambiar imagen de perfil</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formCambiarFoto" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body text-center">
                        <img src="{{ optional(Auth::user())->foto_usuario_url ?? asset('img/usuario_default.png') }}"
                            alt="Foto actual" class="rounded-circle mb-3 object-fit-cover" width="128" height="128" id="previewFoto"
                            style="box-shadow: 0 0 0 4px var(--ft-brand-soft), 0 0 0 5px var(--ft-border);">
                        <div class="text-start">
                            <label for="nuevaFoto" class="form-label required">Nueva imagen</label>
                            <input type="file" class="form-control" id="nuevaFoto" name="foto_usuario"
                                accept="image/jpeg,image/png" required aria-describedby="nuevaFotoHelp">
                            <div class="form-text" id="nuevaFotoHelp">JPG o PNG, máximo 2 MB.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1" aria-hidden="true"></i> Subir imagen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        function esc(value) {
            return $('<div>').text(value == null ? '' : String(value)).html();
        }

        $(document).ready(function () {
            let selectedMaterial = null;
            let selectedVehicle = null;
            let searchTimeout = null;
            let vehicleSearchTimeout = null;

            // Secuencia secreta (↑ ↓ k ↓ ↑): muestra el acceso de administrador
            let secretSequence = [];
            const targetSequence = ['ArrowUp', 'ArrowDown', 'KeyK', 'ArrowDown', 'ArrowUp'];
            const sequenceDisplay = {
                'ArrowUp': '↑', 'ArrowDown': '↓', 'ArrowLeft': '←', 'ArrowRight': '→', 'KeyK': 'k'
            };

            $(document).keydown(function (e) {
                // No interferir mientras se escribe en un campo
                if ($(e.target).is('input, textarea, select, [contenteditable]')) return;

                if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'KeyK'].includes(e.code)) {
                    secretSequence.push(e.code);

                    $('#secretIndicator').addClass('active');
                    setTimeout(() => $('#secretIndicator').removeClass('active'), 200);

                    let displaySequence = secretSequence.map(key => sequenceDisplay[key] || key).join(' ');
                    $('#sequenceDisplay').text(displaySequence);
                    $('#secretSequence').addClass('show');

                    setTimeout(() => $('#secretSequence').removeClass('show'), 2000);

                    if (secretSequence.length > 5) {
                        secretSequence.shift();
                    }

                    if (secretSequence.length === 5 &&
                        JSON.stringify(secretSequence) === JSON.stringify(targetSequence)) {

                        $('#adminAccessBtn').addClass('show');
                        $('#sequenceDisplay').text('¡Acceso desbloqueado! ✓');
                        $('#secretSequence').addClass('show');

                        secretSequence = [];

                        setTimeout(() => $('#secretSequence').removeClass('show'), 3000);
                        setTimeout(() => window.location.href = '/materials', 2000);
                    }
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function handleAjaxError(xhr) {
                const res = xhr.responseJSON;
                if (res?.errors) {
                    FT.notice(Object.values(res.errors).flat().join('\n'), 'error');
                } else if (res?.message) {
                    FT.notice(res.message, 'error');
                } else {
                    FT.notice(xhr.responseText || 'Error inesperado', 'error');
                }
            }

            function showNotificationSuccess(message) {
                $('#notificationMessage').text(message);
                $('#notificationSuccess').addClass('show');
                setTimeout(() => $('#notificationSuccess').removeClass('show'), 4000);
            }

            // Modal de detalle de material
            function openMaterialModal(mode, data = {}) {
                const filas = [
                    ['ID', esc(data.id_material)],
                    ['Clave', `<span class="ft-code">${esc(data.clave_material)}</span>`],
                    ['Descripción', esc(data.descripcion)],
                    ['Genérico', esc(data.generico)],
                    ['Clasificación', esc(data.clasificacion)],
                    ['Existencia', `<span class="ft-num fw-semibold">${esc(data.existencia)}</span>`],
                    ['Costo ($)', `<span class="ft-num">${esc(data.costo_promedio)}</span>`],
                ];
                $('#modalMaterialTitle').html('<i class="bi bi-box-seam" aria-hidden="true"></i> Detalles del material');
                $('#modalMaterialBody').html('<dl class="ft-detail-list">' + filas.map(([k, v]) => `<dt>${k}</dt><dd>${v}</dd>`).join('') + '</dl>');
                $('#modalMaterialFooter').html('<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>');
                $('#modalMaterial').modal('show');
            }

            // Búsqueda de materiales
            function searchMaterials(query) {
                if (query.length < 2) {
                    $('#searchResultsDropdown').hide().empty();
                    $('#btnAgregarMaterial').prop('disabled', true);
                    selectedMaterial = null;
                    return;
                }

                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    $.ajax({
                        url: '/materiales/search',
                        method: 'GET',
                        data: { q: query },
                        success: function (materiales) {
                            const results = $('#searchResultsDropdown');
                            results.empty();

                            if (Array.isArray(materiales) && materiales.length > 0) {
                                materiales.forEach((material) => {
                                    const item = $(`
                                        <div class="search-result-item" role="option" tabindex="0">
                                            <div class="material-details min-w-0">
                                                <div class="material-code">${esc(material.clave_material)}</div>
                                                <div class="text-truncate">${esc(material.descripcion)}</div>
                                                <div class="material-meta">
                                                    ID ${esc(material.id_material)} · Genérico: ${esc(material.generico || 'N/A')} · Clasificación: ${esc(material.clasificacion || 'N/A')}
                                                </div>
                                            </div>
                                            <div class="material-stock">Stock: ${esc(material.existencia)}</div>
                                        </div>
                                    `);
                                    item.attr({
                                        'data-id': material.id_material,
                                        'data-clave': material.clave_material,
                                        'data-descripcion': material.descripcion
                                    });
                                    results.append(item);
                                });
                                results.show();
                            } else {
                                results.html('<div class="search-result-item text-body-secondary">No se encontraron materiales</div>').show();
                            }
                        },
                        error: function () {
                            $('#searchResultsDropdown').html('<div class="search-result-item text-danger">Error en la búsqueda</div>').show();
                        }
                    });
                }, 300);
            }

            // Búsqueda de vehículos
            function searchVehicles(query) {
                if (query.length < 2) {
                    $('#vehicleResultsDropdown').hide().empty();
                    selectedVehicle = null;
                    return;
                }

                clearTimeout(vehicleSearchTimeout);
                vehicleSearchTimeout = setTimeout(() => {
                    $.ajax({
                        url: '/vehiculos/search',
                        method: 'GET',
                        data: { q: query },
                        success: function (vehiculos) {
                            const results = $('#vehicleResultsDropdown');
                            results.empty();

                            if (Array.isArray(vehiculos) && vehiculos.length > 0) {
                                vehiculos.forEach((vehiculo) => {
                                    const meta = [
                                        vehiculo.placas ? 'Placas: ' + vehiculo.placas : '',
                                        [vehiculo.marca, vehiculo.modelo].filter(Boolean).join(' '),
                                        vehiculo.anio ? 'Año: ' + vehiculo.anio : '',
                                        vehiculo.kilometraje ? 'KM: ' + Number(vehiculo.kilometraje).toLocaleString('es-MX') : '',
                                        vehiculo.conductor_habitual ? 'Conductor: ' + vehiculo.conductor_habitual : ''
                                    ].filter(Boolean).join(' · ');
                                    const item = $(`
                                        <div class="vehicle-result-item" role="option" tabindex="0">
                                            <div class="vehicle-info">
                                                <div class="vehicle-eco">ECO ${esc(vehiculo.eco)}</div>
                                                <div class="vehicle-meta">${esc(meta)}</div>
                                            </div>
                                            <div class="vehicle-status">${esc(vehiculo.estatus)}</div>
                                        </div>
                                    `);
                                    item.attr({
                                        'data-id': vehiculo.id,
                                        'data-eco': vehiculo.eco,
                                        'data-placas': vehiculo.placas || '',
                                        'data-marca': vehiculo.marca || '',
                                        'data-modelo': vehiculo.modelo || '',
                                        'data-anio': vehiculo.anio || '',
                                        'data-kilometraje': vehiculo.kilometraje || '',
                                        'data-conductor': vehiculo.conductor_habitual || ''
                                    });
                                    results.append(item);
                                });
                                results.show();
                            } else {
                                results.html('<div class="vehicle-result-item text-body-secondary">No se encontraron vehículos</div>').show();
                            }
                        },
                        error: function () {
                            $('#vehicleResultsDropdown').html('<div class="vehicle-result-item text-danger">Error en la búsqueda</div>').show();
                        }
                    });
                }, 300);
            }

            $('#materialSearch').on('input', function () {
                searchMaterials($(this).val().trim());
            });

            $('#vehicleSearch').on('input', function () {
                searchVehicles($(this).val().trim());
            });

            // Seleccionar con teclado (Enter) los resultados
            $(document).on('keydown', '.search-result-item, .vehicle-result-item', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); $(this).trigger('click'); }
            });

            // Selección de material
            $(document).on('click', '.search-result-item', function () {
                const id = $(this).data('id');
                if (id) {
                    selectedMaterial = {
                        id: id,
                        clave: $(this).data('clave'),
                        descripcion: $(this).data('descripcion')
                    };

                    $('#materialSearch').val(`${selectedMaterial.clave} - ${selectedMaterial.descripcion}`);
                    $('#searchResultsDropdown').hide();
                    $('#btnAgregarMaterial').prop('disabled', false).trigger('focus');
                }
            });

            // Selección de vehículo y autocompletado
            $(document).on('click', '.vehicle-result-item', function () {
                if (!$(this).data('eco')) return;
                const vehicleData = {
                    id: $(this).data('id'),
                    eco: $(this).data('eco'),
                    placas: $(this).data('placas'),
                    marca: $(this).data('marca'),
                    modelo: $(this).data('modelo'),
                    anio: $(this).data('anio'),
                    kilometraje: $(this).data('kilometraje'),
                    conductor: $(this).data('conductor')
                };

                selectedVehicle = vehicleData;

                $('#ecoField').val(vehicleData.eco || '');
                $('#placasField').val(vehicleData.placas || '');
                $('#marcaField').val(vehicleData.marca || '');
                $('#anoField').val(vehicleData.anio || '');
                $('#kmField').val(vehicleData.kilometraje || '');
                $('#conductorField').val(vehicleData.conductor || '');
                $('#vehicleIdField').val(vehicleData.id || '');

                $('#vehicleSearch').val(`ECO: ${vehicleData.eco} - ${vehicleData.marca || ''} ${vehicleData.modelo || ''}`);
                $('#vehicleResultsDropdown').hide();
            });

            // Ocultar listas al hacer clic fuera
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#materialSearch, #searchResultsDropdown').length) {
                    $('#searchResultsDropdown').hide();
                }
                if (!$(e.target).closest('#vehicleSearch, #vehicleResultsDropdown').length) {
                    $('#vehicleResultsDropdown').hide();
                }
            });

            // Materiales seleccionados
            function addMaterial(materialInfo) {
                let exists = false;
                $("#selectedMaterialsTable tbody tr").each(function () {
                    let id = $(this).data('id');
                    if (id == materialInfo.id) {
                        let quantityInput = $(this).find('input.cantidad');
                        quantityInput.val(parseInt(quantityInput.val()) + 1);
                        exists = true;
                    }
                });

                if (!exists) {
                    let row = $(`<tr>
                        <td class="align-middle"></td>
                        <td><input type="number" class="form-control form-control-sm cantidad" value="1" min="1" aria-label="Cantidad"></td>
                        <td class="ft-col-actions">
                            <div class="ft-table-actions">
                                <button type="button" class="btn btn-sm btn-ghost btn-icon btnDec" title="Disminuir" aria-label="Disminuir"><i class="bi bi-dash" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-sm btn-ghost btn-icon btnInc" title="Aumentar" aria-label="Aumentar"><i class="bi bi-plus" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-sm btn-ghost btn-icon text-danger btnRemove" title="Eliminar" aria-label="Quitar material"><i class="bi bi-trash3" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>`);
                    row.attr('data-id', materialInfo.id);
                    row.find('td:first').text(materialInfo.descripcion);
                    $("#selectedMaterialsTable tbody").append(row);
                }
                updateMaterialsData();
            }

            function updateMaterialsData() {
                let materials = [];
                $("#selectedMaterialsTable tbody tr").each(function () {
                    let id = $(this).data('id');
                    let description = $(this).find('td:first').text();
                    let quantity = $(this).find('input.cantidad').val();
                    materials.push({ id: id, descripcion: description, cantidad: quantity });
                });
                $('#materialsData').val(JSON.stringify(materials));
            }

            $("#btnAgregarMaterial").click(function () {
                if (selectedMaterial) {
                    addMaterial({
                        id: selectedMaterial.id,
                        descripcion: `${selectedMaterial.clave} - ${selectedMaterial.descripcion}`
                    });

                    $('#materialSearch').val('');
                    selectedMaterial = null;
                    $('#searchResultsDropdown').hide();
                    $('#btnAgregarMaterial').prop('disabled', true);
                } else {
                    FT.notice('Selecciona un material de la lista de búsqueda.', 'warning');
                }
            });

            $("#selectedMaterialsTable").on("click", ".btnInc", function () {
                let input = $(this).closest('tr').find('input.cantidad');
                input.val(parseInt(input.val()) + 1);
                updateMaterialsData();
            });

            $("#selectedMaterialsTable").on("click", ".btnDec", function () {
                let input = $(this).closest('tr').find('input.cantidad');
                if (parseInt(input.val()) > 1) {
                    input.val(parseInt(input.val()) - 1);
                    updateMaterialsData();
                }
            });

            $("#selectedMaterialsTable").on("click", ".btnRemove", function () {
                $(this).closest('tr').remove();
                updateMaterialsData();
            });

            $("#selectedMaterialsTable").on("input", "input.cantidad", updateMaterialsData);

            // Usuarios por lugar
            function loadUsersByPlace(placeId) {
                if (!placeId) {
                    $('#usuarioReporta').html('<option value="">Selecciona el usuario que reporta</option>');
                    return;
                }

                $.ajax({
                    url: `/lugares/${placeId}/usuarios`,
                    method: 'GET',
                    beforeSend: function () {
                        $('#usuarioReporta').html('<option value="">Cargando usuarios...</option>');
                    },
                    success: function (response) {
                        const select = $('#usuarioReporta').empty()
                            .append('<option value="">Selecciona el usuario que reporta</option>');
                        if (response.usuarios && Array.isArray(response.usuarios) && response.usuarios.length > 0) {
                            response.usuarios.forEach(function (usuario) {
                                $('<option>')
                                    .val(usuario.id)
                                    .attr({ 'data-nombre': usuario.name, 'data-correo': usuario.email })
                                    .text(`${usuario.name} (${usuario.email})`)
                                    .appendTo(select);
                            });
                        } else {
                            select.append('<option value="" disabled>No hay usuarios disponibles en este lugar</option>');
                        }
                    },
                    error: function () {
                        $('#usuarioReporta').html('<option value="" disabled>Error al cargar usuarios</option>');
                    }
                });
            }

            function loadVehiclesByPlace(placeId) {
                // Limpiar campos de vehículo cuando cambie el lugar
                $('#vehicleSearch, #ecoField, #placasField, #marcaField, #anoField, #kmField, #conductorField, #vehicleIdField').val('');
                $('#vehicleResultsDropdown').hide();
                selectedVehicle = null;
            }

            $('#id_lugar_reporte').on('change', function () {
                let placeId = $(this).val();
                loadUsersByPlace(placeId);
                loadVehiclesByPlace(placeId);
            });

            $('#usuarioReporta').on('change', function () {
                let selectedOption = $(this).find('option:selected');
                $('#nombreUsuarioReporta').val(selectedOption.data('nombre') || '');
                $('#correoUsuarioReporta').val(selectedOption.data('correo') || '');
            });

            $('#modalReporte').on('shown.bs.modal', function () {
                const currentPlace = $('#id_lugar_reporte').val();
                if (currentPlace) {
                    loadUsersByPlace(currentPlace);
                }
            });

            // Botones de reporte
            $('.btnDisminuir, #btnReporteFallas').click(function () {
                if ($(this).hasClass('btnDisminuir')) {
                    let row = $(this).closest('tr');
                    let materialId = $(this).data('id');
                    let materialCode = row.find('td:nth-child(2)').text().trim();
                    let materialDescription = row.find('td:nth-child(3)').text().trim();
                    $("#selectedMaterialsTable tbody").html("");
                    addMaterial({
                        id: materialId,
                        descripcion: `${materialCode} - ${materialDescription}`
                    });
                } else {
                    $("#selectedMaterialsTable tbody").html("");
                    updateMaterialsData();
                }
                $("#modalReporte").modal("show");
            });

            // Enviar reporte
            $('#formReporte').submit(function (e) {
                e.preventDefault();

                if (!$('#id_lugar_reporte').val()) {
                    FT.notice('Selecciona un lugar.', 'warning');
                    return;
                }
                if (!$('#usuarioReporta').val()) {
                    FT.notice('Selecciona el usuario que reporta.', 'warning');
                    return;
                }

                updateMaterialsData();
                const formData = $(this).serialize();

                $('#btnEnviarReporte').prop('disabled', true).addClass('loading');

                $.post('/notificaciones', formData)
                    .done(function (response) {
                        if (response.success) {
                            $('#modalReporte').modal('hide');
                            showNotificationSuccess(response.message || '¡Notificación enviada correctamente!');

                            $('#formReporte')[0].reset();
                            $("#selectedMaterialsTable tbody").html("");
                            $('#usuarioReporta').html('<option value="">Selecciona el usuario que reporta</option>');
                            selectedMaterial = null;
                            selectedVehicle = null;
                            $('#btnAgregarMaterial').prop('disabled', true);
                        } else {
                            FT.notice(response.message || 'Error desconocido', 'error');
                        }
                    })
                    .fail(function (xhr) {
                        handleAjaxError(xhr);
                    })
                    .always(function () {
                        $('#btnEnviarReporte').prop('disabled', false).removeClass('loading');
                    });
            });

            // Ver material
            $('.btnVer').click(function () {
                let id = $(this).data('id');
                $.get(`/materiales/${id}`, function (response) {
                    openMaterialModal('view', response.data);
                });
            });

            // Limpiar al cerrar el modal de reporte
            $('#modalReporte').on('hidden.bs.modal', function () {
                $('#formReporte')[0].reset();
                $("#selectedMaterialsTable tbody").html("");
                $('#usuarioReporta').html('<option value="">Selecciona el usuario que reporta</option>');
                $('#btnEnviarReporte').prop('disabled', false).removeClass('loading');
                $('#materialSearch, #vehicleSearch').val('');
                $('#searchResultsDropdown, #vehicleResultsDropdown').hide();
                $('#ecoField, #placasField, #marcaField, #anoField, #kmField, #conductorField, #vehicleIdField').val('');
                selectedMaterial = null;
                selectedVehicle = null;
                $('#btnAgregarMaterial').prop('disabled', true);
            });

            // Vista previa de foto
            $('#nuevaFoto').change(function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#previewFoto').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Formularios de perfil
            $('#formCambiarNombre').submit(function (e) {
                e.preventDefault();
                const formData = $(this).serialize();
                const submitBtn = $(this).find('button[type="submit"]').prop('disabled', true).addClass('loading');

                $.post('/usuario/update-name', formData)
                    .done(function (response) {
                        $('#modalCambiarNombre').modal('hide');
                        showNotificationSuccess(response.message);
                        setTimeout(() => location.reload(), 2000);
                    })
                    .fail(handleAjaxError)
                    .always(function () {
                        submitBtn.prop('disabled', false).removeClass('loading');
                    });
            });

            $('#formCambiarPassword').submit(function (e) {
                e.preventDefault();

                const newPassword = $('#passwordNueva').val();
                const confirmPassword = $('#passwordConfirmar').val();

                if (newPassword !== confirmPassword) {
                    $('#passwordConfirmar').addClass('is-invalid').trigger('focus');
                    FT.notice('Las contraseñas no coinciden.', 'warning');
                    return;
                }
                $('#passwordConfirmar').removeClass('is-invalid');

                const formData = $(this).serialize();
                const submitBtn = $(this).find('button[type="submit"]').prop('disabled', true).addClass('loading');

                $.post('/usuario/update-password', formData)
                    .done(function (response) {
                        $('#modalCambiarPassword').modal('hide');
                        $('#formCambiarPassword')[0].reset();
                        showNotificationSuccess(response.message);
                    })
                    .fail(handleAjaxError)
                    .always(function () {
                        submitBtn.prop('disabled', false).removeClass('loading');
                    });
            });

            $('#formCambiarFoto').submit(function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = $(this).find('button[type="submit"]').prop('disabled', true).addClass('loading');

                $.ajax({
                    url: '/usuario/update-photo',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false
                })
                    .done(function (response) {
                        $('#modalCambiarFoto').modal('hide');
                        showNotificationSuccess(response.message);
                        if (response.foto_url) {
                            $('.user-avatar-new, #previewFoto').attr('src', response.foto_url);
                        }
                    })
                    .fail(handleAjaxError)
                    .always(function () {
                        submitBtn.prop('disabled', false).removeClass('loading');
                    });
            });
        });
    </script>
@endpush
