@extends('layouts.app')

@section('title', 'Lugares')

@section('content')
    <x-page-header
        eyebrow="Administración"
        icon="bi-geo-alt"
        title="Lugares"
        description="Sedes y centros de distribución. Cada usuario, material y vehículo pertenece a un lugar.">
        <x-slot:actions>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistro" id="btnNuevoLugar">
                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Nuevo lugar
            </button>
        </x-slot:actions>
    </x-page-header>

    @if(session('success'))
        <div class="alert alert-success" role="status">
            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <section class="ft-panel" aria-label="Lista de lugares">
        @if($lugares->isEmpty())
            <x-empty-state icon="bi-geo-alt" title="Aún no hay lugares"
                text="Registra el primer lugar para asignarle usuarios, materiales y vehículos." />
        @else
            <div class="ft-table-wrap">
                <table class="table table-hover ft-table-stack">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Estado</th>
                            <th scope="col" class="ft-col-actions"><span class="visually-hidden">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lugares as $lugar)
                            <tr data-id="{{ $lugar->id_lugar }}">
                                <td data-label="Nombre">
                                    <span class="d-inline-flex align-items-center gap-2">
                                        <i class="bi bi-geo-alt-fill text-primary" aria-hidden="true"></i>
                                        <span class="ft-cell-title">{{ $lugar->nombre }}</span>
                                    </span>
                                </td>
                                <td data-label="Estado">
                                    @if($lugar->estado)
                                        {{ $lugar->estado }}
                                    @else
                                        <span class="text-body-secondary">Sin especificar</span>
                                    @endif
                                </td>
                                <td class="ft-col-actions">
                                    <div class="ft-table-actions action-buttons">
                                        <button class="btn btn-ghost btn-sm btn-icon btnVer" data-id="{{ $lugar->id_lugar }}" title="Ver detalles" aria-label="Ver {{ $lugar->nombre }}">
                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-ghost btn-sm btn-icon btnEditar" data-id="{{ $lugar->id_lugar }}" title="Editar lugar" aria-label="Editar {{ $lugar->nombre }}">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-ghost btn-sm btn-icon text-danger btnEliminar" data-id="{{ $lugar->id_lugar }}" title="Eliminar lugar" aria-label="Eliminar {{ $lugar->nombre }}">
                                            <i class="bi bi-trash3" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="ft-panel__footer">
                <span class="small text-body-secondary ft-num">{{ $lugares->count() }} {{ $lugares->count() === 1 ? 'lugar' : 'lugares' }}</span>
            </div>
        @endif
    </section>
@endsection

@push('modals')
    <!-- Modal Registro/Edición -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalLugarTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formLugar">
                    @csrf
                    <input type="hidden" id="lugarId">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalLugarTitle">
                            <i class="bi bi-geo-alt" aria-hidden="true"></i> <span id="modalLugarTitleText">Nuevo lugar</span>
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label required">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required placeholder="Ej. CEDIS Toluca">
                        </div>
                        <div class="mb-1">
                            <label for="estado" class="form-label">Estado</label>
                            <input type="text" id="estado" name="estado" class="form-control" placeholder="Ej. Estado de México">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2 me-1" aria-hidden="true"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ver Lugar -->
    <div class="modal fade" id="modalVer" tabindex="-1" aria-labelledby="modalVerLugarTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="modalVerLugarTitle">
                        <i class="bi bi-eye" aria-hidden="true"></i> Detalles del lugar
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <dl class="ft-detail-list">
                        <dt>Nombre</dt><dd id="verNombre"></dd>
                        <dt>Estado</dt><dd id="verEstado"></dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            // Nuevo lugar: formulario limpio
            $('#btnNuevoLugar').click(function () {
                $('#formLugar')[0].reset();
                $('#lugarId').val('');
                $('#modalLugarTitleText').text('Nuevo lugar');
            });

            // Editar lugar
            $('.btnEditar').click(function () {
                let id = $(this).data('id');
                $.get(`/lugares/${id}/edit`, function (response) {
                    $('#lugarId').val(response.data.id_lugar);
                    $('#nombre').val(response.data.nombre);
                    $('#estado').val(response.data.estado);
                    $('#modalLugarTitleText').text('Editar lugar');
                    $('#modalRegistro').modal('show');
                });
            });

            // Ver lugar
            $('.btnVer').click(function () {
                let id = $(this).data('id');
                $.get(`/lugares/${id}`, function (response) {
                    $('#verNombre').text(response.data.nombre);
                    $('#verEstado').text(response.data.estado || 'Sin especificar');
                    $('#modalVer').modal('show');
                });
            });

            // Eliminar lugar
            $('.btnEliminar').click(function () {
                let id = $(this).data('id');
                FT.confirm('Esta acción no se puede deshacer.', {
                    title: '¿Eliminar este lugar?',
                    confirmText: 'Eliminar',
                    danger: true
                }).then(function (ok) {
                    if (!ok) return;
                    $.ajax({
                        url: `/lugares/${id}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            FT.notice(response.message).then(() => location.reload());
                        },
                        error: function (xhr) {
                            FT.notice('Error al eliminar lugar: ' + xhr.responseText, 'error');
                        }
                    });
                });
            });

            // Guardar lugar (crear/editar)
            $('#formLugar').submit(function (event) {
                event.preventDefault();
                let id = $('#lugarId').val();
                let url = id ? `/lugares/${id}` : `/lugares`;
                let method = id ? 'PUT' : 'POST';
                const submitBtn = $(this).find('button[type="submit"]').addClass('loading').prop('disabled', true);

                $.ajax({
                    url: url,
                    type: method,
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        FT.notice(response.message).then(() => location.reload());
                    },
                    error: function (xhr) {
                        FT.notice('Error al guardar: ' + xhr.responseText, 'error');
                    },
                    complete: function () {
                        submitBtn.removeClass('loading').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
