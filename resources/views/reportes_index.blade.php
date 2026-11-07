@extends('layouts.app')

@section('title', 'Reportes de fallas')

@php
    $columnas = [
        'lugar' => 'Lugar', 'fecha' => 'Fecha', 'conductor' => 'Conductor', 'eco' => 'ECO',
        'placas' => 'Placas', 'marca' => 'Marca', 'anio' => 'Año', 'km' => 'KM',
        'descripcion' => 'Descripción', 'observaciones' => 'Observaciones', 'material' => 'Material',
        'cantidad' => 'Cant.', 'autorizado' => 'Autorizado por', 'reviso' => 'Revisó',
        'reporta' => 'Reporta', 'correo' => 'Correo',
    ];
@endphp

@section('content')
    @if(Auth::user()->id_lugar || Auth::user()->tipo_usuario == 1)
        <x-page-header
            eyebrow="Operación"
            icon="bi-clipboard2-pulse"
            title="Reportes de fallas"
            :description="'Historial de fallas y uso de materiales · ' . (Auth::user()->lugar->nombre ?? 'Todos los lugares')" />

        <section class="ft-panel" aria-label="Reportes de fallas">
            <div class="ft-toolbar">
                <div class="ft-toolbar__grow ft-search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <label for="searchFallas" class="visually-hidden">Buscar reportes</label>
                    <input type="search" id="searchFallas" class="form-control search-input"
                        placeholder="Buscar por lugar, conductor, descripción, ECO, placas o marca" autocomplete="off">
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="bi bi-layout-three-columns me-1" aria-hidden="true"></i> Columnas
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-3" style="width: min(22rem, 90vw)">
                        <p class="small fw-semibold text-body-secondary mb-2" id="columnasLabel">Mostrar u ocultar columnas</p>
                        <div class="d-flex flex-wrap gap-2 column-controls" role="group" aria-labelledby="columnasLabel">
                            @foreach($columnas as $clave => $texto)
                                <button type="button" class="btn btn-sm column-toggle active" data-column="{{ $clave }}" aria-pressed="true">{{ $texto }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <div class="scroll-indicator" id="scrollIndicator">
                    <i class="bi bi-arrow-left-right" aria-hidden="true"></i> Desplázate horizontalmente para ver más columnas
                </div>
                <div class="table-wrapper" id="tableWrapper" tabindex="0" aria-label="Tabla de reportes, desplazable">
                    <table class="table table-hover" id="tablaFallas">
                        <thead>
                            <tr>
                                @foreach($columnas as $clave => $texto)
                                    <th scope="col" class="col-{{ $clave }} {{ in_array($clave, ['cantidad']) ? 'text-end' : '' }}">{{ $texto }}</th>
                                @endforeach
                                <th scope="col" class="col-acciones ft-col-actions"><span class="visually-hidden">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody id="bodyFallas">
                            @foreach($fallas->where('id_lugar', Auth::user()->id_lugar) as $falla)
                                <tr data-id="{{ $falla->id }}">
                                    <td class="col-lugar">
                                        <div class="expandable-cell" tabindex="0">
                                            <span>{{ Str::limit(optional($falla->lugar)->nombre ?: 'N/A', 15) }}</span>
                                            @if(strlen(optional($falla->lugar)->nombre ?: '') > 15)
                                                <div class="full-content">{{ optional($falla->lugar)->nombre }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-fecha text-nowrap">{{ $falla->fecha ?: 'N/A' }}</td>
                                    <td class="col-conductor">
                                        <div class="expandable-cell" tabindex="0">
                                            <span>{{ Str::limit($falla->nombre_conductor ?: 'N/A', 15) }}</span>
                                            @if(strlen($falla->nombre_conductor ?: '') > 15)
                                                <div class="full-content">{{ $falla->nombre_conductor }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-eco"><span class="badge badge-eco">{{ $falla->eco ?: 'N/A' }}</span></td>
                                    <td class="col-placas"><span class="badge badge-placas">{{ $falla->placas ?: 'N/A' }}</span></td>
                                    <td class="col-marca">{{ $falla->marca ?: 'N/A' }}</td>
                                    <td class="col-anio">{{ $falla->anio ?: 'N/A' }}</td>
                                    <td class="col-km text-nowrap">{{ $falla->km ?: 'N/A' }}</td>
                                    <td class="col-descripcion">
                                        <div class="expandable-cell" tabindex="0">
                                            <span>{{ Str::limit($falla->descripcion ?: 'N/A', 20) }}</span>
                                            @if(strlen($falla->descripcion ?: '') > 20)
                                                <div class="full-content">{{ $falla->descripcion }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-observaciones">
                                        <div class="expandable-cell" tabindex="0">
                                            <span>{{ Str::limit($falla->observaciones ?: 'N/A', 20) }}</span>
                                            @if(strlen($falla->observaciones ?: '') > 20)
                                                <div class="full-content">{{ $falla->observaciones }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-material">
                                        <div class="expandable-cell" tabindex="0">
                                            <span class="ft-code">{{ Str::limit($falla->material ?: 'N/A', 20) }}</span>
                                            @if(strlen($falla->material ?: '') > 20)
                                                <div class="full-content">{{ $falla->material }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-cantidad text-end">
                                        <span class="badge badge-cantidad">{{ $falla->cantidad ?: '0' }}</span>
                                    </td>
                                    <td class="col-autorizado">
                                        <div class="expandable-cell" tabindex="0">
                                            <span>{{ Str::limit($falla->autorizado_por ?: 'N/A', 15) }}</span>
                                            @if(strlen($falla->autorizado_por ?: '') > 15)
                                                <div class="full-content">{{ $falla->autorizado_por }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-reviso">
                                        <div class="expandable-cell" tabindex="0">
                                            <span>{{ Str::limit($falla->reviso_por ?: 'N/A', 15) }}</span>
                                            @if(strlen($falla->reviso_por ?: '') > 15)
                                                <div class="full-content">{{ $falla->reviso_por }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-reporta">
                                        <div class="expandable-cell" tabindex="0">
                                            <span>{{ Str::limit($falla->nombre_usuario_reporta ?: 'N/A', 15) }}</span>
                                            @if(strlen($falla->nombre_usuario_reporta ?: '') > 15)
                                                <div class="full-content">{{ $falla->nombre_usuario_reporta }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-correo">
                                        <div class="expandable-cell" tabindex="0">
                                            <span>{{ Str::limit($falla->correo_usuario_reporta ?: 'N/A', 20) }}</span>
                                            @if(strlen($falla->correo_usuario_reporta ?: '') > 20)
                                                <div class="full-content">{{ $falla->correo_usuario_reporta }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="col-acciones ft-col-actions">
                                        <div class="ft-table-actions action-buttons">
                                            <a href="{{ route('fallas.pdf', $falla->id) }}" class="btn btn-ghost btn-sm btn-icon"
                                                title="Generar PDF" aria-label="Generar PDF del reporte {{ $falla->id }}">
                                                <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>
                                            </a>
                                            <button class="btn btn-ghost btn-sm btn-icon btnEnviar" data-id="{{ $falla->id }}"
                                                title="Enviar por correo" aria-label="Enviar por correo el reporte {{ $falla->id }}">
                                                <i class="bi bi-envelope" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($fallas->where('id_lugar', Auth::user()->id_lugar)->count() == 0)
                                <tr>
                                    <td colspan="17" class="empty-state">
                                        <i class="bi bi-clipboard2-check" aria-hidden="true"></i>
                                        <h4>Sin reportes</h4>
                                        <p>No hay reportes de fallas registrados para tu lugar.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="ft-panel__footer">
                <span class="small text-body-secondary ft-num" id="fallaCount" aria-live="polite"></span>
                <nav aria-label="Paginación de reportes">
                    <ul class="pagination" id="fallaPagination"></ul>
                </nav>
            </div>
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
    <!-- Modal: Enviar Correo -->
    <div class="modal fade" id="modalEnviar" tabindex="-1" aria-labelledby="modalEnviarTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formEnviar" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title" id="modalEnviarTitle">
                        <i class="bi bi-envelope" aria-hidden="true"></i> Enviar reporte por correo
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="fallaId">
                    <div class="mb-3">
                        <label class="form-label required" for="correoDestino">Correo de destino</label>
                        <input type="email" id="correoDestino" name="correo_destino"
                            class="form-control" placeholder="usuario@dominio.com" required autocomplete="email">
                        <div class="invalid-feedback">Escribe un correo válido.</div>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-paperclip" aria-hidden="true"></i>
                        <div>El reporte se enviará como archivo PDF adjunto.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-1" aria-hidden="true"></i> Enviar reporte
                    </button>
                </div>
            </form>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        $(function () {
            // Inicializar filas
            let all = $('#bodyFallas tr[data-id]').toArray().map(r => ({ html: r.outerHTML, id: $(r).data('id') })),
                filtered = [...all], current = 1, perPage = 10;
            const emptyRow = $('#bodyFallas tr:not([data-id])').prop('outerHTML') || '';

            // Control del indicador de scroll
            const $tableWrapper = $('#tableWrapper');
            const $scrollIndicator = $('#scrollIndicator');

            function checkScrollIndicator() {
                const wrapper = $tableWrapper[0];
                if (!wrapper) return;
                if (wrapper.scrollWidth > wrapper.clientWidth) {
                    $scrollIndicator.removeClass('hidden');
                } else {
                    $scrollIndicator.addClass('hidden');
                }
            }

            // Ocultar indicador cuando se hace scroll
            $tableWrapper.on('scroll', function () {
                if ($(this).scrollLeft() > 50) {
                    $scrollIndicator.fadeOut();
                } else {
                    $scrollIndicator.fadeIn();
                }
            });

            function showPage(page) {
                current = page;
                let start = (page - 1) * perPage, end = start + perPage;

                $('#bodyFallas').addClass('loading');

                setTimeout(() => {
                    const rows = filtered.slice(start, end).map(u => u.html).join('');
                    $('#bodyFallas').html(rows || (all.length ? `
                        <tr><td colspan="17" class="empty-state">
                            <i class="bi bi-search" aria-hidden="true"></i>
                            <h4>Sin coincidencias</h4>
                            <p>Ningún reporte coincide con la búsqueda.</p>
                        </td></tr>` : emptyRow));
                    $('#bodyFallas').removeClass('loading');
                    renderPagination();
                    attachListeners();
                    applyColumnVisibility();
                    checkScrollIndicator();
                    $('#fallaCount').text(filtered.length === all.length
                        ? `${all.length} reportes`
                        : `${filtered.length} de ${all.length} reportes`);
                }, 150);
            }

            function renderPagination() {
                let total = Math.ceil(filtered.length / perPage), nav = '';
                if (total <= 1) {
                    $('#fallaPagination').html('');
                    return;
                }

                nav += `<li class="page-item ${current === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${current - 1}" aria-label="Página anterior">
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                    </a>
                </li>`;

                for (let i = 1; i <= total; i++) {
                    if (i === 1 || i === total || (i >= current - 2 && i <= current + 2)) {
                        nav += `<li class="page-item ${i === current ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}" ${i === current ? 'aria-current="page"' : ''}>${i}</a>
                        </li>`;
                    } else if (i === current - 3 || i === current + 3) {
                        nav += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
                    }
                }

                nav += `<li class="page-item ${current === total ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${current + 1}" aria-label="Página siguiente">
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                    </a>
                </li>`;

                $('#fallaPagination').html(nav);
            }

            // Control de columnas
            function applyColumnVisibility() {
                $('.column-toggle').each(function () {
                    const column = $(this).data('column');
                    const isActive = $(this).hasClass('active');
                    $(this).attr('aria-pressed', isActive ? 'true' : 'false');

                    if (isActive) {
                        $(`.col-${column}`).removeClass('hidden-col');
                    } else {
                        $(`.col-${column}`).addClass('hidden-col');
                    }
                });
                checkScrollIndicator();
            }

            $('.column-toggle').on('click', function () {
                $(this).toggleClass('active');
                applyColumnVisibility();
            });

            // Búsqueda en todos los campos del reporte
            let searchTimeout;
            $('#searchFallas').on('input', function () {
                clearTimeout(searchTimeout);
                const searchInput = $(this);

                searchTimeout = setTimeout(() => {
                    let q = searchInput.val().toLowerCase().trim();

                    if (q) {
                        filtered = all.filter(u => $(u.html).text().toLowerCase().includes(q));
                    } else {
                        filtered = [...all];
                    }

                    showPage(1);
                }, 300);
            });

            // Paginación
            $(document).on('click', '#fallaPagination .page-link', function (e) {
                e.preventDefault();
                let p = +$(this).data('page');
                if (p > 0 && p <= Math.ceil(filtered.length / perPage)) {
                    showPage(p);
                }
            });

            // Modal enviar correo
            let modal = new bootstrap.Modal($('#modalEnviar'));

            function attachListeners() {
                $('.btnEnviar').off('click').on('click', function () {
                    const fallaId = $(this).data('id');
                    $('#fallaId').val(fallaId);
                    $('#correoDestino').val('').removeClass('is-valid is-invalid');
                    modal.show();
                });
            }

            // Validación de email en tiempo real
            $('#correoDestino').on('input', function () {
                const email = $(this).val();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email && emailRegex.test(email)) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                } else if (email) {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                } else {
                    $(this).removeClass('is-valid is-invalid');
                }
            });

            // Envío del formulario
            $('#formEnviar').on('submit', function (e) {
                e.preventDefault();

                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).addClass('loading');

                let id = $('#fallaId').val();
                let to = $('#correoDestino').val();

                $.post(
                    `{{ route('fallas.enviar', ['id' => '__ID']) }}`.replace('__ID', id),
                    {
                        correo_destino: to,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                )
                    .done(function (json) {
                        modal.hide();
                        FT.toast(json.message || 'Reporte enviado', 'success', 5000);
                    })
                    .fail(function () {
                        FT.toast('No se pudo enviar el correo. Inténtalo de nuevo.', 'error', 6000);
                    })
                    .always(function () {
                        submitBtn.prop('disabled', false).removeClass('loading');
                    });
            });

            $(window).on('resize', function () {
                checkScrollIndicator();
            });

            // Inicializar
            attachListeners();
            applyColumnVisibility();
            showPage(1);
            checkScrollIndicator();
        });
    </script>
@endpush
