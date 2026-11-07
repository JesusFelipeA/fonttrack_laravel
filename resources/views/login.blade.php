@extends('layouts.guest')

@section('title', 'Iniciar sesión')
@section('body-class', 'ft-auth--login')

@section('content')
    <div class="transition-circle" id="transitionCircle" aria-hidden="true"></div>

    <div class="ft-auth">
        <aside class="ft-auth__aside" aria-hidden="true">
            <div class="ft-auth__logo">
                <img src="{{ asset('img/brand/fonttrack-mark-192.webp') }}" alt="" width="44" height="44">
                FontTrack
            </div>

            <div>
                <h1>Inventario y flota, en un solo lugar.</h1>
                <p>Controla existencias, registra el uso de materiales por vehículo y da seguimiento a cada reporte de falla.</p>
                <ul class="ft-auth__facts">
                    <li><i class="bi bi-box-seam"></i> Existencias por lugar y Kardex</li>
                    <li><i class="bi bi-truck"></i> Vehículos, kilometraje y conductores</li>
                    <li><i class="bi bi-clipboard2-pulse"></i> Reportes de fallas en PDF y por correo</li>
                </ul>
            </div>

            <img src="{{ asset('img/brand/bonafont-x-fonttrack.webp') }}" alt="" class="ft-auth__partner" width="240" height="75" loading="lazy" style="filter: brightness(0) invert(1); opacity: .75;">
        </aside>

        <main class="ft-auth__main">
            <div class="ft-auth-card login-card">
                <div class="ft-auth-card__brand">
                    <img src="{{ asset('img/brand/fonttrack-mark-192.webp') }}" alt="" width="40" height="40">
                    FontTrack
                </div>

                <div class="user-type-indicator" id="userTypeIndicator" aria-hidden="true"></div>
                <h2>Inicia sesión</h2>
                <p class="ft-auth-card__lead">Usa tu correo corporativo @bonafont.com o @danone.com.</p>

                <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="logemail" class="form-label">Correo</label>
                        <div class="input-container">
                            <input id="logemail" name="correo" type="email" class="form-control input-field @error('correo') is-invalid @enderror"
                                placeholder="nombre@bonafont.com" required autocomplete="username" value="{{ old('correo') }}"
                                aria-describedby="emailError" inputmode="email">
                        </div>
                        @error('correo')
                            <div class="error-bubble show" id="emailError" role="alert">
                                {{ $message }}
                                <button type="button" class="close-btn" onclick="closeBubble(this)" aria-label="Cerrar mensaje">×</button>
                            </div>
                        @else
                            <div class="error-bubble" id="emailError" role="alert">
                                Solo se admiten correos con el dominio @bonafont.com o @danone.com
                                <button type="button" class="close-btn" onclick="closeBubble(this)" aria-label="Cerrar mensaje">×</button>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="logpass" class="form-label">Contraseña</label>
                        <div class="input-container">
                            <input id="logpass" name="password" type="password" class="form-control input-field has-icon @error('password') is-invalid @enderror"
                                placeholder="Tu contraseña" required autocomplete="current-password" aria-describedby="passwordError">
                            <button type="button" class="password-toggle" onclick="togglePassword('logpass', this)" aria-label="Mostrar contraseña">
                                <i class="bi bi-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="error-bubble show" id="passwordError" role="alert">
                                {{ $message }}
                                <button type="button" class="close-btn" onclick="closeBubble(this)" aria-label="Cerrar mensaje">×</button>
                            </div>
                        @else
                            <div class="error-bubble" id="passwordError" role="alert">
                                La contraseña no cumple con los requisitos
                                <button type="button" class="close-btn" onclick="closeBubble(this)" aria-label="Cerrar mensaje">×</button>
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 button-container">
                        <button type="submit" class="btn btn-primary btn-lg fs-6 py-2">Entrar</button>
                        <div class="d-flex gap-2">
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary flex-fill">Regresar</a>
                            <button type="button" class="btn btn-outline-secondary flex-fill" data-bs-toggle="modal" data-bs-target="#modalRegistro" id="btnNuevoUsuario">Registrarme</button>
                        </div>
                    </div>
                </form>

                <div class="ft-auth-card__footer">
                    <a id="btnWhatsApp" target="_blank" rel="noopener" href="#">
                        <i class="bi bi-whatsapp" aria-hidden="true"></i> ¿Olvidaste tu contraseña?
                    </a>
                    <a href="https://drive.google.com/drive/folders/10drFBDLZxBWP1Zbyw1_cdAD0mfH1PEE2?usp=drive_link" target="_blank" rel="noopener">
                        <i class="bi bi-phone" aria-hidden="true"></i> App móvil
                    </a>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('modals')
    <!-- Modal de Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formRegistro" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="usuarioId" name="id_usuario">

                    <div class="modal-header">
                        <h2 class="modal-title" id="modalRegistroLabel"><i class="bi bi-person-plus" aria-hidden="true"></i> Crear cuenta</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label required">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required autocomplete="name">
                        </div>
                        <div class="mb-3">
                            <label for="correoRegistro" class="form-label required">Correo</label>
                            <input type="email" id="correoRegistro" name="correo" class="form-control" required autocomplete="email" aria-describedby="emailModalError">
                            <div class="error-bubble" id="emailModalError" role="alert">
                                Debe ser un correo @bonafont.com o @danone.com
                                <button type="button" class="close-btn" onclick="closeBubble(this)" aria-label="Cerrar mensaje">×</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="passwordRegistro" class="form-label required">Contraseña</label>
                            <div class="input-container">
                                <input type="password" id="passwordRegistro" name="password" class="form-control has-icon" required autocomplete="new-password" aria-describedby="passwordReqs">
                                <button type="button" class="password-toggle" onclick="togglePassword('passwordRegistro', this)" aria-label="Mostrar contraseña">
                                    <i class="bi bi-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="password-requirements" id="passwordReqs">
                                <span class="requirement" id="req-uppercase">• Al menos una letra mayúscula</span>
                                <span class="requirement" id="req-number">• Al menos un número</span>
                                <span class="requirement" id="req-length">• Mínimo 6 caracteres</span>
                                <span class="requirement" id="req-sequence">• No puede ser una secuencia numérica</span>
                            </div>
                        </div>
                        <input type="hidden" id="tipo_usuario" name="tipo_usuario" value="2">
                        <div class="mb-3">
                            <label for="id_lugar" class="form-label">Lugar</label>
                            <select id="id_lugar" name="id_lugar" class="form-select">
                                @foreach($lugares as $lugar)
                                    <option value="{{ $lugar->id_lugar }}">{{ $lugar->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1">
                            <label for="foto_usuario" class="form-label">Foto de perfil</label>
                            <input type="file" id="foto_usuario" name="foto_usuario" class="form-control" accept="image/png, image/jpeg" aria-describedby="fotoHelp">
                            <div class="form-text" id="fotoHelp">Opcional. Si no eliges una imagen se usa la foto por defecto.</div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear cuenta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => document.getElementById('transitionCircle').style.display = 'none', 1500);
        });

        // Cerrar mensajes de error
        function closeBubble(button) {
            const bubble = button.parentElement;
            bubble.classList.remove('show');
            bubble.classList.add('hidden');
            bubble.style.display = 'none';
            const field = bubble.parentElement.querySelector('.input-field, .form-control');
            if (field) field.classList.remove('is-invalid');
        }

        // Mostrar/ocultar contraseña
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
                button.setAttribute('aria-label', 'Ocultar contraseña');
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
                button.setAttribute('aria-label', 'Mostrar contraseña');
            }
        }

        function isValidEmail(email) {
            if (!email || email.trim() === '') return false;
            const emailLower = email.toLowerCase().trim();
            return emailLower.endsWith('@bonafont.com') || emailLower.endsWith('@danone.com');
        }

        function validatePassword(password) {
            const hasUppercase = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasMinLength = password.length >= 6;
            const isNotSequence = !isSequentialPassword(password);
            return { hasUppercase, hasNumber, hasMinLength, isNotSequence, isValid: hasUppercase && hasNumber && hasMinLength && isNotSequence };
        }

        function isSequentialPassword(password) {
            if (!/^\d+$/.test(password)) return false;
            let isAscending = true, isDescending = true;
            for (let i = 1; i < password.length; i++) {
                const current = parseInt(password[i]), previous = parseInt(password[i - 1]);
                if (current !== previous + 1) isAscending = false;
                if (current !== previous - 1) isDescending = false;
            }
            return isAscending || isDescending;
        }

        function showError(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.classList.remove('hidden');
                element.classList.add('show');
                element.style.display = 'block';
            }
        }

        function hideError(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.classList.remove('show');
                element.classList.add('hidden');
                element.style.display = 'none';
            }
        }

        // Indicador de tipo de usuario
        function showUserTypeIndicator(email) {
            const indicator = document.getElementById('userTypeIndicator');
            if (!indicator) return;

            if (isValidEmail(email)) {
                const isAdmin = email.toLowerCase().includes('admin') ||
                    email.toLowerCase().includes('supervisor') ||
                    email.toLowerCase().includes('gerente');

                if (isAdmin) {
                    indicator.textContent = 'ADMIN';
                    indicator.className = 'user-type-indicator admin show';
                } else {
                    indicator.textContent = '';
                    indicator.className = 'user-type-indicator user show';
                }

                setTimeout(() => {
                    indicator.classList.remove('show');
                }, 3000);
            } else {
                indicator.classList.remove('show');
            }
        }

        // Validación de login
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            const email = document.getElementById('logemail').value;
            const password = document.getElementById('logpass').value;
            let hasErrors = false;

            if (email && !isValidEmail(email)) {
                e.preventDefault();
                showError('emailError');
                document.getElementById('logemail').classList.add('is-invalid');
                hasErrors = true;
            } else {
                hideError('emailError');
                document.getElementById('logemail').classList.remove('is-invalid');
            }

            if (password) {
                const passwordValidation = validatePassword(password);
                if (!passwordValidation.isValid) {
                    e.preventDefault();
                    showError('passwordError');
                    document.getElementById('logpass').classList.add('is-invalid');
                    hasErrors = true;
                } else {
                    hideError('passwordError');
                    document.getElementById('logpass').classList.remove('is-invalid');
                }
            }

            if (!hasErrors) {
                const btn = this.querySelector('button[type="submit"]');
                btn.classList.add('loading');
            }
            return !hasErrors;
        });

        // Validación en tiempo real
        document.getElementById('logemail').addEventListener('input', function () {
            const email = this.value;
            if (email && !isValidEmail(email)) {
                showError('emailError');
                this.classList.add('is-invalid');
            } else {
                hideError('emailError');
                this.classList.remove('is-invalid');
                if (email) {
                    showUserTypeIndicator(email);
                }
            }
        });

        document.getElementById('logpass').addEventListener('input', function () {
            if (this.value) {
                const validation = validatePassword(this.value);
                if (!validation.isValid) {
                    showError('passwordError');
                    this.classList.add('is-invalid');
                } else {
                    hideError('passwordError');
                    this.classList.remove('is-invalid');
                }
            }
        });

        // Validaciones del registro
        $('#correoRegistro').on('input', function () {
            const email = $(this).val();
            if (email && !isValidEmail(email)) {
                $(this).addClass('is-invalid');
                $('#emailModalError').addClass('show').show();
            } else {
                $(this).removeClass('is-invalid');
                $('#emailModalError').removeClass('show').hide();
            }
        });

        $('#passwordRegistro').on('input', function () {
            const password = $(this).val();
            const validation = validatePassword(password);
            $('#req-uppercase').toggleClass('valid', validation.hasUppercase);
            $('#req-number').toggleClass('valid', validation.hasNumber);
            $('#req-length').toggleClass('valid', validation.hasMinLength);
            $('#req-sequence').toggleClass('valid', validation.isNotSequence);
            $(this).toggleClass('is-invalid', password && !validation.isValid);
        });

        $('#formRegistro').submit(function (e) {
            e.preventDefault();
            const correoRegistro = $('#correoRegistro').val();
            const passwordRegistro = $('#passwordRegistro').val();

            if (!isValidEmail(correoRegistro)) {
                FT.notice('El correo debe ser del dominio @bonafont.com o @danone.com', 'warning');
                return false;
            }

            const passwordValidation = validatePassword(passwordRegistro);
            if (!passwordValidation.isValid) {
                FT.notice('La contraseña debe cumplir con todos los requisitos mostrados', 'warning');
                return false;
            }

            const id = $('#usuarioId').val();
            const url = id ? `/modal/update_user/${id}` : `/modal/register_user`;
            const method = id ? 'PUT' : 'POST';
            const data = new FormData(this);

            if (!$('#foto_usuario')[0].files.length) {
                data.append('foto_default', 'Sin_Foto.png');
            }

            const submitBtn = $(this).find('button[type="submit"]').addClass('loading').prop('disabled', true);

            $.ajax({
                url, method, data,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success(res) { FT.notice(res.message).then(() => location.reload()); },
                error(err) {
                    const msgs = err.responseJSON?.errors || {};
                    FT.notice(Object.values(msgs).flat().join('\n') || 'No se pudo crear la cuenta', 'error');
                },
                complete() { submitBtn.removeClass('loading').prop('disabled', false); }
            });
        });

        $('#btnNuevoUsuario').on('click', function () {
            $('#formRegistro')[0].reset();
            $('#usuarioId').val('');
            $('#tipo_usuario').val('2');
            $('.requirement').removeClass('valid');
            $('#formRegistro .form-control').removeClass('is-invalid');
            $('#emailModalError').removeClass('show').hide();
        });

        // Recuperar contraseña por WhatsApp
        document.getElementById('btnWhatsApp').addEventListener('click', function (e) {
            const emailInput = document.getElementById('logemail');
            const correo = emailInput.value.trim();
            if (!correo) {
                e.preventDefault();
                FT.notice('Escribe tu correo antes de solicitar ayuda por WhatsApp.', 'info');
                emailInput.focus();
                return;
            }
            const mensaje = encodeURIComponent(`Hola, olvidé mi contraseña. Mi correo es ${correo}`);
            const numero = '7223648734';
            const url = `https://wa.me/${numero}?text=${mensaje}`;
            this.setAttribute('href', url);
        });
    </script>
@endpush
