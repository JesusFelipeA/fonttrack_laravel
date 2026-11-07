@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <x-page-header
        eyebrow="Administración"
        icon="bi-people"
        title="Usuarios"
        description="Cuentas con acceso a FontTrack, su tipo y el lugar al que pertenecen.">
        <x-slot:actions>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistro" id="btnNuevoUsuario">
                <i class="bi bi-person-plus me-1" aria-hidden="true"></i> Registrar usuario
            </button>
        </x-slot:actions>
    </x-page-header>

    <section class="ft-panel" aria-label="Lista de usuarios">
        <div class="ft-toolbar">
            <div class="ft-toolbar__grow ft-search">
                <i class="bi bi-search" aria-hidden="true"></i>
                <label for="searchUsers" class="visually-hidden">Buscar usuarios</label>
                <input type="search" id="searchUsers" class="form-control"
                    placeholder="Buscar por nombre, correo, tipo o lugar" autocomplete="off">
            </div>
            <span class="text-body-secondary small ft-num" id="userCount" aria-live="polite"></span>
        </div>

        <div class="ft-table-wrap">
            <table class="table table-hover ft-table-stack" id="tablaUsuarios">
                <thead>
                    <tr>
                        <th scope="col">Usuario</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Lugar</th>
                        <th scope="col" class="ft-col-actions"><span class="visually-hidden">Acciones</span></th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @foreach($usuarios as $usuario)
                        <tr data-id="{{ $usuario->id_usuario }}">
                            <td data-label="Usuario">
                                <div class="d-flex align-items-center gap-2 text-start">
                                    <img src="{{ $usuario->foto_usuario_url ?? asset('img/usuario_default.png') }}" alt="" class="ft-avatar" loading="lazy" width="36" height="36">
                                    <span class="ft-cell-title">{{ $usuario->nombre }}</span>
                                </div>
                            </td>
                            <td data-label="Correo" class="text-break">{{ $usuario->correo }}</td>
                            <td data-label="Tipo">
                                <span class="ft-badge {{ $usuario->tipo_usuario == 1 ? 'ft-badge--brand' : 'ft-badge--neutral' }} ft-badge--plain">{{ $usuario->tipo_usuario == 1 ? 'Admin' : 'Usuario' }}</span>
                            </td>
                            <td data-label="Lugar">{{ optional($usuario->lugar)->nombre ?? 'Sin asignar' }}</td>
                            <td class="ft-col-actions">
                                <div class="ft-table-actions">
                                    <button class="btn btn-ghost btn-sm btn-icon btnVer" data-id="{{ $usuario->id_usuario }}" title="Ver detalles" aria-label="Ver a {{ $usuario->nombre }}">
                                        <i class="bi bi-eye" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-ghost btn-sm btn-icon btnEditar" data-id="{{ $usuario->id_usuario }}" title="Editar" aria-label="Editar a {{ $usuario->nombre }}">
                                        <i class="bi bi-pencil" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-ghost btn-sm btn-icon text-danger btnEliminar" data-id="{{ $usuario->id_usuario }}" title="Eliminar" aria-label="Eliminar a {{ $usuario->nombre }}">
                                        <i class="bi bi-trash3" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="userEmpty" class="d-none">
            <x-empty-state icon="bi-search" title="Sin coincidencias" text="Ningún usuario coincide con la búsqueda." />
        </div>

        <div class="ft-panel__footer">
            <nav aria-label="Paginación de usuarios" class="ms-auto">
                <ul class="pagination" id="userPagination"></ul>
            </nav>
        </div>
    </section>
@endsection

@push('modals')
    <!-- Modal Registro/Edición -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formRegistro" enctype="multipart/form-data" novalidate>
                    <input type="hidden" id="usuarioId" name="id_usuario">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalTitle">Registrar Usuario</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label required">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required autocomplete="name">
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label required">Correo</label>
                            <input type="email" id="correo" name="correo" class="form-control" required autocomplete="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control"
                                aria-describedby="passwordHelp" autocomplete="new-password">
                            <div class="form-text" id="passwordHelp">Mínimo 6 caracteres. Al editar, déjala vacía para conservar la actual.</div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label for="tipo_usuario" class="form-label required">Tipo de usuario</label>
                                <select id="tipo_usuario" name="tipo_usuario" class="form-select" required>
                                    <option value="">Selecciona un tipo</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Usuario</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="id_lugar" class="form-label required">Lugar</label>
                                <select id="id_lugar" name="id_lugar" class="form-select" required aria-describedby="lugarHelp">
                                    <option value="">Selecciona un lugar</option>
                                    @foreach($lugares as $lugar)
                                        <option value="{{ $lugar->id_lugar }}">{{ $lugar->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="form-text mt-0" id="lugarHelp">El lugar asignado determina los permisos y accesos del usuario.</div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <label for="foto_usuario" class="form-label">Foto</label>
                            <div class="d-flex align-items-center gap-3">
                                <div id="imagePreview" style="display: none;">
                                    <img id="previewImg" src="" alt="Vista previa de la foto" class="image-preview" style="width: 4rem; height: 4rem;">
                                </div>
                                <input type="file" id="foto_usuario" name="foto_usuario" class="form-control" accept="image/png, image/jpeg">
                            </div>
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

    <!-- Modal Ver Usuario -->
    <div class="modal fade" id="modalVer" tabindex="-1" aria-labelledby="modalVerTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="modalVerTitle"><i class="bi bi-person-vcard" aria-hidden="true"></i> Detalles del usuario</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img id="verFoto" src="" alt="Foto del usuario" class="image-preview" style="width: 4.5rem; height: 4.5rem;">
                        <div>
                            <div class="fw-semibold fs-5" id="verNombre"></div>
                            <div class="text-body-secondary" id="verCorreo"></div>
                        </div>
                    </div>
                    <dl class="ft-detail-list">
                        <dt>Tipo</dt><dd id="verTipo"></dd>
                        <dt>Lugar</dt><dd id="verLugar"></dd>
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
            // Variables para paginación
            let currentPage = 1;
            const itemsPerPage = 10;
            let filteredUsers = [];
            let allUsers = [];

            // Recopilar todos los usuarios al cargar la página
            function initializeUsers() {
                allUsers = [];
                $('#userTableBody tr').each(function () {
                    const row = $(this);
                    allUsers.push({
                        id: row.data('id'),
                        nombre: row.find('td:eq(0)').text().trim(),
                        correo: row.find('td:eq(1)').text().trim(),
                        tipo: row.find('td:eq(2)').text().trim(),
                        lugar: row.find('td:eq(3)').text().trim(),
                        element: row[0].outerHTML
                    });
                });
                filteredUsers = [...allUsers];
                updatePagination();
                showPage(1);
            }

            // Función de búsqueda
            $('#searchUsers').on('input', function () {
                const searchTerm = $(this).val().toLowerCase().trim();

                if (searchTerm === '') {
                    filteredUsers = [...allUsers];
                } else {
                    filteredUsers = allUsers.filter(user => {
                        return user.nombre.toLowerCase().includes(searchTerm) ||
                            user.correo.toLowerCase().includes(searchTerm) ||
                            user.tipo.toLowerCase().includes(searchTerm) ||
                            user.lugar.toLowerCase().includes(searchTerm);
                    });
                }

                currentPage = 1;
                updatePagination();
                showPage(1);
            });

            // Mostrar página específica
            function showPage(page) {
                currentPage = page;
                const startIndex = (page - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const pageUsers = filteredUsers.slice(startIndex, endIndex);

                $('#userTableBody').empty();
                pageUsers.forEach(user => {
                    $('#userTableBody').append($(user.element));
                });

                $('#tablaUsuarios').toggleClass('d-none', filteredUsers.length === 0);
                $('#userEmpty').toggleClass('d-none', filteredUsers.length !== 0);
                $('#userCount').text(filteredUsers.length === allUsers.length
                    ? `${allUsers.length} usuarios`
                    : `${filteredUsers.length} de ${allUsers.length} usuarios`);

                // Reattach event listeners
                attachEventListeners();
                updatePagination();
                updatePaginationButtons();
            }

            // Actualizar paginación
            function updatePagination() {
                const totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
                if (totalPages <= 1) {
                    $('#userPagination').html('');
                    return;
                }
                let paginationHtml = '';

                paginationHtml += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Página anterior">
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                    </a>
                </li>`;

                for (let i = 1; i <= totalPages; i++) {
                    if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                        paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}" ${i === currentPage ? 'aria-current="page"' : ''}>${i}</a>
                        </li>`;
                    } else if (i === currentPage - 3 || i === currentPage + 3) {
                        paginationHtml += '<li class="page-item disabled"><span class="page-link">…</span></li>';
                    }
                }

                paginationHtml += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Página siguiente">
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                    </a>
                </li>`;

                $('#userPagination').html(paginationHtml);
            }

            // Actualizar botones de paginación
            function updatePaginationButtons() {
                $('#userPagination .page-link').off('click').on('click', function (e) {
                    e.preventDefault();
                    const page = parseInt($(this).data('page'));
                    if (page && page !== currentPage) {
                        showPage(page);
                    }
                });
            }

            // Adjuntar event listeners
            function attachEventListeners() {
                // Evento para ver detalles del usuario
                $('.btnVer').off('click').on('click', function () {
                    let id = $(this).data('id');
                    $.get(`/modal/user/${id}`, function (response) {
                        let data = response.data;
                        $('#verNombre').text(data.nombre);
                        $('#verCorreo').text(data.correo);
                        $('#verTipo').text(data.tipo_usuario == 1 ? 'Admin' : 'Usuario');
                        $('#verLugar').text(data.lugar ? data.lugar.nombre : 'Sin asignar');
                        let fotoUrl = data.foto_usuario_url || '{{ asset('img/usuario_default.png') }}';
                        $('#verFoto').attr('src', fotoUrl);
                        $('#modalVer').modal('show');
                    }).fail(function () {
                        FT.notice('No se pudieron cargar los datos del usuario.', 'error');
                    });
                });

                // Evento para cargar datos en el modal de edición
                $('.btnEditar').off('click').on('click', function () {
                    let id = $(this).data('id');
                    $.get(`/modal/edit_user/${id}`, function (response) {
                        let data = response.data;
                        let lugares = response.lugares;

                        $('#modalTitle').text('Editar usuario');
                        $('#usuarioId').val(data.id_usuario);
                        $('#nombre').val(data.nombre);
                        $('#correo').val(data.correo);
                        $('#tipo_usuario').val(data.tipo_usuario);
                        $('#password').val('');

                        // Poblar dinámicamente el select de lugares
                        $('#id_lugar').empty();
                        $('#id_lugar').append('<option value="">Selecciona un lugar</option>');

                        lugares.forEach(function (lugar) {
                            const option = $('<option>').val(lugar.id_lugar).text(lugar.nombre);
                            if (lugar.id_lugar == data.id_lugar) option.prop('selected', true);
                            $('#id_lugar').append(option);
                        });

                        // Mostrar imagen actual si existe
                        if (data.foto_usuario_url) {
                            $('#imagePreview').show();
                            $('#previewImg').attr('src', data.foto_usuario_url);
                        } else {
                            $('#imagePreview').hide();
                        }

                        $('#modalRegistro').modal('show');
                    }).fail(function () {
                        FT.notice('Error al cargar los datos del usuario', 'error');
                    });
                });

                // Evento para eliminar usuario
                $('.btnEliminar').off('click').on('click', function () {
                    let id = $(this).data('id');
                    FT.confirm('Esta acción no se puede deshacer.', {
                        title: '¿Eliminar este usuario?',
                        confirmText: 'Eliminar',
                        danger: true
                    }).then(function (ok) {
                        if (!ok) return;
                        $.ajax({
                            url: `/modal/delete_user/${id}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                FT.notice(response.message).then(() => location.reload());
                            },
                            error: function () {
                                FT.notice('Error al eliminar el usuario', 'error');
                            }
                        });
                    });
                });
            }

            // Vista previa de imagen
            $('#foto_usuario').on('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#previewImg').attr('src', e.target.result);
                        $('#imagePreview').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#imagePreview').hide();
                }
            });

            // Envío del formulario (crear/actualizar usuario) mediante AJAX
            $('#formRegistro').submit(function (e) {
                e.preventDefault();
                const form = this;
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }
                let usuarioId = $('#usuarioId').val();
                let formData = new FormData(form);
                let url = usuarioId ? `/modal/update_user/${usuarioId}` : `/modal/register_user`;
                let method = usuarioId ? 'PUT' : 'POST';

                // Para métodos PUT en FormData
                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                }

                const submitBtn = $(form).find('button[type="submit"]').addClass('loading').prop('disabled', true);

                $.ajax({
                    url: url,
                    type: 'POST', // Siempre POST para FormData
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#modalRegistro').modal('hide');
                        FT.notice(response.message).then(() => location.reload());
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON?.errors || { general: 'Error al procesar la solicitud' };
                        let errorMsg = '';
                        $.each(errors, function (key, value) {
                            errorMsg += (Array.isArray(value) ? value.join(', ') : value) + '\n';
                        });
                        FT.notice(errorMsg.trim(), 'error');
                    },
                    complete: function () {
                        submitBtn.removeClass('loading').prop('disabled', false);
                    }
                });
            });

            // Al abrir el modal para nuevo usuario, se resetea el formulario y se cargan los lugares
            $('#btnNuevoUsuario').click(function () {
                $('#modalTitle').text('Registrar usuario');
                $('#formRegistro')[0].reset();
                $('#formRegistro').removeClass('was-validated');
                $('#usuarioId').val('');
                $('#imagePreview').hide();

                // Los lugares ya vienen en el HTML del formulario (la ruta /lugares/all no existe).
            });

            // Inicializar la tabla con paginación
            initializeUsers();
        });
    </script>
@endpush
