@extends('layouts.app')

@section('title', 'Vehículos')

@guest
    @push('styles')
        <script>
            window.location.href = '/login';
        </script>
    @endpush
@endguest

@section('content')
    <x-page-header
        eyebrow="Inventario"
        icon="bi-truck"
        title="Vehículos"
        description="Unidades de la flota por lugar, con su kilometraje, conductor habitual y estado.">
        <x-slot:actions>
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
                <i class="bi bi-file-earmark-spreadsheet me-1" aria-hidden="true"></i> Importar Excel
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVehiculo">
                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Nuevo vehículo
            </button>
        </x-slot:actions>
    </x-page-header>

    @if(session('success'))
        <div class="alert alert-success" role="status">
            <i class="bi bi-check-circle-fill" aria-hidden="true"></i><div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i><div>{{ session('error') }}</div>
        </div>
    @endif

    <section class="ft-panel" aria-label="Lista de vehículos">
        <div class="ft-toolbar search-filters">
            <div class="ft-toolbar__grow d-flex gap-2">
                <div class="ft-search flex-grow-1">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <label for="searchInput" class="visually-hidden">Buscar vehículos</label>
                    <input type="search" id="searchInput" class="form-control search-input"
                        placeholder="Buscar por ECO, placas o marca (mín. 2 caracteres)" autocomplete="off">
                </div>
                <button class="btn btn-outline-secondary" type="button" onclick="performSearch()">Buscar</button>
            </div>
            @if(Auth::user()->tipo_usuario == 1)
                <div style="min-width: 12rem">
                    <label for="lugarFilter" class="visually-hidden">Filtrar por lugar</label>
                    <select id="lugarFilter" class="form-select search-input" onchange="filterByLugar()">
                        <option value="">Todos los lugares</option>
                        @foreach($lugares as $lugar)
                            <option value="{{ $lugar->id_lugar }}" @selected(request('lugar') == $lugar->id_lugar)>{{ $lugar->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="ft-table-wrap" id="vehiclesTableContainer">
            <table class="table table-hover ft-table-stack">
                <thead>
                    <tr>
                        <th scope="col">ECO</th>
                        <th scope="col">Placas</th>
                        <th scope="col">Marca / modelo</th>
                        <th scope="col">Año</th>
                        <th scope="col" class="text-end">Kilometraje</th>
                        <th scope="col">Conductor</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Lugar</th>
                        <th scope="col" class="ft-col-actions"><span class="visually-hidden">Acciones</span></th>
                    </tr>
                </thead>
                <tbody id="vehiculosTableBody">
                    @if(isset($vehiculos) && $vehiculos->count() > 0)
                        @foreach($vehiculos as $vehiculo)
                            <tr data-id="{{ $vehiculo->id }}">
                                <td data-label="ECO"><span class="ft-code ft-cell-title">{{ $vehiculo->eco }}</span></td>
                                <td data-label="Placas"><span class="ft-code">{{ $vehiculo->placas ?? 'N/A' }}</span></td>
                                <td data-label="Marca / modelo">
                                    <span class="ft-cell-title">{{ $vehiculo->marca ?? 'N/A' }}</span>
                                    @if($vehiculo->modelo)
                                        <span class="ft-cell-sub">{{ $vehiculo->modelo }}</span>
                                    @endif
                                </td>
                                <td data-label="Año">{{ $vehiculo->anio ?? 'N/A' }}</td>
                                <td data-label="Kilometraje" class="text-end text-nowrap">{{ number_format($vehiculo->kilometraje) }} km</td>
                                <td data-label="Conductor">{{ $vehiculo->conductor_habitual ?? 'No asignado' }}</td>
                                <td data-label="Estado"><x-status-badge :status="$vehiculo->estatus" /></td>
                                <td data-label="Lugar">{{ $vehiculo->lugar->nombre ?? 'N/A' }}</td>
                                <td class="ft-col-actions">
                                    <div class="ft-table-actions action-buttons">
                                        <button class="btn btn-ghost btn-sm btn-icon" onclick="showVehiculo({{ $vehiculo->id }})" title="Ver" aria-label="Ver vehículo {{ $vehiculo->eco }}">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-ghost btn-sm btn-icon" onclick="editVehiculo({{ $vehiculo->id }})" title="Editar" aria-label="Editar vehículo {{ $vehiculo->eco }}">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-ghost btn-sm btn-icon text-danger" onclick="deleteVehiculo({{ $vehiculo->id }})" title="Eliminar" aria-label="Eliminar vehículo {{ $vehiculo->eco }}">
                                            <i class="bi bi-trash3" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="empty-state">
                                <i class="bi bi-truck" aria-hidden="true"></i>
                                <h4>No hay vehículos registrados</h4>
                                <p>Aún no hay vehículos en este lugar de trabajo.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if(isset($vehiculos) && method_exists($vehiculos, 'links') && $vehiculos->hasPages())
            <div class="ft-panel__footer ft-pagination">
                {{ $vehiculos->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
@endsection

@push('modals')
    <!-- Aviso flotante -->
    <div class="notification" id="notification" role="status" aria-live="polite">
        <span id="notificationMessage"></span>
    </div>

    <!-- Modal Vehículo (Create/Edit) -->
    <div class="modal fade" id="modalVehiculo" tabindex="-1" aria-labelledby="modalVehiculoTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formVehiculo">
                    @csrf
                    <input type="hidden" id="vehiculoId" name="vehiculo_id">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalVehiculoTitle">
                            <i class="bi bi-truck" aria-hidden="true"></i> Nuevo vehículo
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <h3 class="form-section-title">Identificación</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="id_lugar" class="form-label required">Lugar</label>
                                <select id="id_lugar" name="id_lugar" class="form-select" required>
                                    <option value="">Selecciona un lugar</option>
                                    @foreach($lugares as $lugar)
                                        <option value="{{ $lugar->id_lugar }}">{{ $lugar->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="eco" class="form-label required">No. ECO</label>
                                <input type="text" id="eco" name="eco" class="form-control ft-code" required>
                            </div>
                            <div class="col-md-6">
                                <label for="placas" class="form-label">Placas</label>
                                <input type="text" id="placas" name="placas" class="form-control ft-code">
                            </div>
                            <div class="col-md-6">
                                <label for="estatus" class="form-label required">Estado</label>
                                <select id="estatus" name="estatus" class="form-select" required>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                    <option value="mantenimiento">En Mantenimiento</option>
                                </select>
                            </div>
                        </div>

                        <h3 class="form-section-title">Unidad</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" id="marca" name="marca" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="modelo" class="form-label">Modelo</label>
                                <input type="text" id="modelo" name="modelo" class="form-control">
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="anio" class="form-label">Año</label>
                                <input type="text" id="anio" name="anio" class="form-control" maxlength="4" inputmode="numeric">
                            </div>
                            <div class="col-6 col-md-4">
                                <label for="kilometraje" class="form-label">Kilometraje</label>
                                <input type="number" id="kilometraje" name="kilometraje" class="form-control" min="0" inputmode="numeric">
                            </div>
                            <div class="col-md-4">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" id="color" name="color" class="form-control">
                            </div>
                            <div class="col-12">
                                <label for="conductor_habitual" class="form-label">Conductor habitual</label>
                                <input type="text" id="conductor_habitual" name="conductor_habitual" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnSubmitVehiculo" class="btn btn-primary">
                            <i class="bi bi-check2 me-1" aria-hidden="true"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ver Vehículo -->
    <div class="modal fade" id="modalVerVehiculo" tabindex="-1" aria-labelledby="modalVerVehiculoTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="modalVerVehiculoTitle">
                        <i class="bi bi-eye" aria-hidden="true"></i> Detalles del vehículo
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="vehiculoDetails">
                    <!-- Los detalles se cargan aquí -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Importar Excel -->
    <div class="modal fade" id="modalImportExcel" tabindex="-1" aria-labelledby="modalImportTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formImportExcel" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalImportTitle">
                            <i class="bi bi-file-earmark-spreadsheet" aria-hidden="true"></i> Importar vehículos desde Excel
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="excel_file" class="form-label required">Archivo Excel</label>
                            <input type="file" id="excel_file" name="excel_file" class="form-control"
                                accept=".xlsx,.xls" required aria-describedby="excelHelp">
                            <div class="form-text" id="excelHelp">Formatos soportados: .xlsx, .xls</div>
                        </div>

                        <div class="mb-3">
                            <label for="lugar_import" class="form-label required">Lugar para los vehículos</label>
                            <select id="lugar_import" name="lugar_import" class="form-select" required>
                                <option value="">Selecciona un lugar</option>
                                @foreach($lugares as $lugar)
                                    <option value="{{ $lugar->id_lugar }}">{{ $lugar->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                            <div>
                                <strong>Columnas esperadas, en este orden:</strong>
                                <span class="d-block mt-1">ECO (obligatorio), Placas, Marca, Modelo, Año, Kilometraje, Color, Conductor habitual, Estado (activo/inactivo/mantenimiento).</span>
                            </div>
                        </div>

                        <div id="import-progress" class="mt-3" style="display: none;">
                            <label class="form-label" for="progress-bar">Progreso de importación</label>
                            <div class="progress" role="progressbar" aria-label="Progreso de importación">
                                <div id="progress-bar" class="progress-bar" style="width: 0%"></div>
                            </div>
                            <small id="progress-text" class="form-text" aria-live="polite">Preparando importación...</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnImportExcel" class="btn btn-primary">
                            <i class="bi bi-upload me-1" aria-hidden="true"></i> Importar
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

        const ESTATUS = {
            activo: ['success', 'Activo'],
            inactivo: ['neutral', 'Inactivo'],
            mantenimiento: ['warning', 'En mantenimiento']
        };
        function estatusBadge(estatus) {
            const [tono, texto] = ESTATUS[estatus] || ['neutral', esc(estatus)];
            return `<span class="ft-badge ft-badge--${tono}">${texto}</span>`;
        }

        $(document).ready(function () {
            // Set CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Utility functions
            function showNotification(message, type = 'success') {
                const notification = $('#notification');
                $('#notificationMessage').text(message);
                notification.removeClass('success error').addClass(type + ' show');
                setTimeout(() => notification.removeClass('show'), 4000);
            }

            function handleAjaxError(xhr) {
                const res = xhr.responseJSON;
                if (res?.errors) {
                    showNotification('Errores: ' + Object.values(res.errors).flat().join(', '), 'error');
                } else if (res?.message) {
                    showNotification('Error: ' + res.message, 'error');
                } else {
                    showNotification('Error: ' + xhr.responseText, 'error');
                }
            }

            // Búsqueda AJAX
            let searchTimeout;

            $('#searchInput').on('input', function () {
                clearTimeout(searchTimeout);
                const query = $(this).val().trim();

                // Si el campo está vacío, recargar la página para mostrar todos los vehículos
                if (query.length === 0) {
                    searchTimeout = setTimeout(() => {
                        location.reload();
                    }, 300);
                    return;
                }

                // Buscar después de 500ms de inactividad
                if (query.length >= 2) {
                    searchTimeout = setTimeout(() => {
                        performAjaxSearch(query);
                    }, 500);
                }
            });

            function performAjaxSearch(query) {
                $('#vehiclesTableContainer').addClass('table-loading');
                $('#vehiculosTableBody').html(`
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="loading-spinner"></div>
                            <p class="mt-2 mb-0 text-body-secondary">Buscando vehículos...</p>
                        </td>
                    </tr>
                `);

                $.ajax({
                    url: '/vehiculos/search',
                    method: 'GET',
                    data: { q: query },
                    success: function (vehiculos) {
                        updateVehiclesTable(vehiculos);
                    },
                    error: function (xhr) {
                        $('#vehiculosTableBody').html(`
                            <tr>
                                <td colspan="9" class="empty-state">
                                    <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
                                    <h4>Error al buscar vehículos</h4>
                                    <p>Intenta de nuevo en unos segundos.</p>
                                </td>
                            </tr>
                        `);
                        handleAjaxError(xhr);
                    },
                    complete: function () {
                        $('#vehiclesTableContainer').removeClass('table-loading');
                    }
                });
            }

            // Actualizar tabla con resultados
            function updateVehiclesTable(vehiculos) {
                let html = '';

                if (vehiculos && vehiculos.length > 0) {
                    vehiculos.forEach(vehiculo => {
                        const eco = esc(vehiculo.eco);
                        html += `
                            <tr data-id="${vehiculo.id}">
                                <td data-label="ECO"><span class="ft-code ft-cell-title">${eco}</span></td>
                                <td data-label="Placas"><span class="ft-code">${esc(vehiculo.placas || 'N/A')}</span></td>
                                <td data-label="Marca / modelo">
                                    <span class="ft-cell-title">${esc(vehiculo.marca || 'N/A')}</span>
                                    ${vehiculo.modelo ? '<span class="ft-cell-sub">' + esc(vehiculo.modelo) + '</span>' : ''}
                                </td>
                                <td data-label="Año">${esc(vehiculo.anio || 'N/A')}</td>
                                <td data-label="Kilometraje" class="text-end text-nowrap">${vehiculo.kilometraje ? Number(vehiculo.kilometraje).toLocaleString('es-MX') + ' km' : '0 km'}</td>
                                <td data-label="Conductor">${esc(vehiculo.conductor_habitual || 'No asignado')}</td>
                                <td data-label="Estado">${estatusBadge(vehiculo.estatus)}</td>
                                <td data-label="Lugar">${vehiculo.lugar ? esc(vehiculo.lugar.nombre) : 'N/A'}</td>
                                <td class="ft-col-actions">
                                    <div class="ft-table-actions action-buttons">
                                        <button class="btn btn-ghost btn-sm btn-icon" onclick="showVehiculo(${vehiculo.id})" title="Ver" aria-label="Ver vehículo ${eco}">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-ghost btn-sm btn-icon" onclick="editVehiculo(${vehiculo.id})" title="Editar" aria-label="Editar vehículo ${eco}">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-ghost btn-sm btn-icon text-danger" onclick="deleteVehiculo(${vehiculo.id})" title="Eliminar" aria-label="Eliminar vehículo ${eco}">
                                            <i class="bi bi-trash3" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = `
                        <tr>
                            <td colspan="9" class="empty-state">
                                <i class="bi bi-search" aria-hidden="true"></i>
                                <h4>No se encontraron resultados</h4>
                                <p>No hay vehículos que coincidan con tu búsqueda.</p>
                            </td>
                        </tr>
                    `;
                }

                $('#vehiculosTableBody').html(html);
            }

            // Form submission para vehículos
            $('#formVehiculo').submit(function (e) {
                e.preventDefault();

                const vehiculoId = $('#vehiculoId').val();
                const isEditing = vehiculoId && vehiculoId !== '';
                const url = isEditing ? `/vehiculos/${vehiculoId}` : '/vehiculos';
                const method = isEditing ? 'PUT' : 'POST';

                const formData = $(this).serialize();

                $('#btnSubmitVehiculo').prop('disabled', true).addClass('loading');

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            $('#modalVehiculo').modal('hide');
                            showNotification(response.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification('Error: ' + (response.message || 'Error desconocido'), 'error');
                        }
                    },
                    error: function (xhr) {
                        handleAjaxError(xhr);
                    },
                    complete: function () {
                        $('#btnSubmitVehiculo').prop('disabled', false).removeClass('loading');
                    }
                });
            });

            // Form submission para importar Excel
            $('#formImportExcel').submit(function (e) {
                e.preventDefault();

                const formData = new FormData(this);

                $('#btnImportExcel').prop('disabled', true).addClass('loading');
                $('#import-progress').show();

                // Progreso estimado mientras responde el servidor
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress = Math.min(progress + 10, 90);
                    $('#progress-bar').css('width', progress + '%');
                    if (progress < 50) {
                        $('#progress-text').text('Leyendo archivo Excel...');
                    } else if (progress < 80) {
                        $('#progress-text').text('Procesando datos...');
                    } else {
                        $('#progress-text').text('Guardando vehículos...');
                    }
                }, 200);

                $.ajax({
                    url: '/vehiculos/import-excel',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        clearInterval(progressInterval);
                        $('#progress-bar').css('width', '100%');
                        $('#progress-text').text('Importación completada');

                        if (response.success) {
                            setTimeout(() => {
                                $('#modalImportExcel').modal('hide');
                                showNotification(response.message);
                                location.reload();
                            }, 1000);
                        } else {
                            showNotification('Error: ' + (response.message || 'Error en la importación'), 'error');
                        }
                    },
                    error: function (xhr) {
                        clearInterval(progressInterval);
                        handleAjaxError(xhr);
                    },
                    complete: function () {
                        $('#btnImportExcel').prop('disabled', false).removeClass('loading');
                        setTimeout(() => {
                            $('#import-progress').hide();
                            $('#progress-bar').css('width', '0%');
                        }, 2000);
                    }
                });
            });

            // Limpiar formularios al cerrar los modales
            $('#modalVehiculo').on('hidden.bs.modal', function () {
                $('#formVehiculo')[0].reset();
                $('#vehiculoId').val('');
                $('#modalVehiculoTitle').html('<i class="bi bi-truck" aria-hidden="true"></i> Nuevo vehículo');
            });

            $('#modalImportExcel').on('hidden.bs.modal', function () {
                $('#formImportExcel')[0].reset();
                $('#import-progress').hide();
                $('#progress-bar').css('width', '0%');
            });

            // Buscar con Enter
            $('#searchInput').keypress(function (e) {
                if (e.which == 13) {
                    e.preventDefault();
                    const query = $(this).val().trim();
                    if (query.length >= 2) {
                        performAjaxSearch(query);
                    }
                }
            });

            // Exponer función para uso global
            window.showNotification = showNotification;
        });

        // Funciones globales usadas por los botones
        function performSearch() {
            const query = $('#searchInput').val().trim();
            if (query.length >= 2) {
                $('#searchInput').trigger('input');
            } else if (query.length === 0) {
                location.reload();
            } else {
                showNotification('Ingresa al menos 2 caracteres para buscar', 'error');
            }
        }

        function showVehiculo(id) {
            $.get(`/vehiculos/${id}`, function (response) {
                if (response.data) {
                    const v = response.data;
                    const filas = [
                        ['ECO', `<span class="ft-code">${esc(v.eco)}</span>`],
                        ['Placas', `<span class="ft-code">${esc(v.placas || 'N/A')}</span>`],
                        ['Marca', esc(v.marca || 'N/A')],
                        ['Modelo', esc(v.modelo || 'N/A')],
                        ['Año', esc(v.anio || 'N/A')],
                        ['Kilometraje', v.kilometraje ? Number(v.kilometraje).toLocaleString('es-MX') + ' km' : 'N/A'],
                        ['Color', esc(v.color || 'N/A')],
                        ['Conductor', esc(v.conductor_habitual || 'No asignado')],
                        ['Estado', estatusBadge(v.estatus)],
                        ['Lugar', v.lugar ? esc(v.lugar.nombre) : 'N/A'],
                    ];
                    $('#vehiculoDetails').html(
                        '<dl class="ft-detail-list">' +
                        filas.map(([k, val]) => `<dt>${k}</dt><dd>${val}</dd>`).join('') +
                        '</dl>'
                    );
                    $('#modalVerVehiculo').modal('show');
                }
            }).fail(function () {
                showNotification('Error al cargar los detalles del vehículo', 'error');
            });
        }

        function editVehiculo(id) {
            $.get(`/vehiculos/${id}/edit`, function (response) {
                if (response.data) {
                    const vehiculo = response.data;

                    $('#vehiculoId').val(vehiculo.id);
                    $('#id_lugar').val(vehiculo.id_lugar);
                    $('#eco').val(vehiculo.eco);
                    $('#placas').val(vehiculo.placas);
                    $('#marca').val(vehiculo.marca);
                    $('#modelo').val(vehiculo.modelo);
                    $('#anio').val(vehiculo.anio);
                    $('#kilometraje').val(vehiculo.kilometraje);
                    $('#color').val(vehiculo.color);
                    $('#conductor_habitual').val(vehiculo.conductor_habitual);
                    $('#estatus').val(vehiculo.estatus);

                    $('#modalVehiculoTitle').html('<i class="bi bi-pencil" aria-hidden="true"></i> Editar vehículo');
                    $('#modalVehiculo').modal('show');
                }
            }).fail(function () {
                showNotification('Error al cargar los datos del vehículo', 'error');
            });
        }

        function deleteVehiculo(id) {
            FT.confirm('Esta acción no se puede deshacer.', {
                title: '¿Eliminar este vehículo?',
                confirmText: 'Eliminar',
                danger: true
            }).then(function (ok) {
                if (!ok) return;
                $.ajax({
                    url: `/vehiculos/${id}`,
                    method: 'DELETE',
                    success: function (response) {
                        if (response.success) {
                            showNotification(response.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification('Error: ' + (response.message || 'Error desconocido'), 'error');
                        }
                    },
                    error: function () {
                        showNotification('Error al eliminar el vehículo', 'error');
                    }
                });
            });
        }

        function filterByLugar() {
            const lugar = $('#lugarFilter').val();
            const currentUrl = new URL(window.location);
            if (lugar) {
                currentUrl.searchParams.set('lugar', lugar);
            } else {
                currentUrl.searchParams.delete('lugar');
            }
            currentUrl.searchParams.delete('page');
            window.location.href = currentUrl.toString();
        }
    </script>
@endpush
