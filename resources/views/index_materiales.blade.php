<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>Lista de Materiales - FontTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-orange: #F6B88F;
            --secondary-orange: #E38B5B;
            --dark-brown: #634D3B;
            --light-cream: #FCE8D5;
            --accent-blue: #88C0D0;
            --accent-yellow: #E5A34D;
            --accent-red: #D9534F;
            --shadow-light: rgba(0, 0, 0, 0.1);
            --shadow-medium: rgba(0, 0, 0, 0.15);
            --shadow-heavy: rgba(0, 0, 0, 0.3);
        }

        * {
            box-sizing: border-box;
        }

        /* Animaciones globales */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Animación para la campana */
        @keyframes bellRing {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(15deg);
            }

            75% {
                transform: rotate(-15deg);
            }
        }

        /* Body & Background */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--light-cream) 0%, #f8f1e8 100%);
            color: var(--dark-brown);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(246, 184, 143, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(227, 139, 91, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(136, 192, 208, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        /* Navbar mejorada */
        .navbar {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--secondary-orange) 100%);
            box-shadow: 0 8px 32px var(--shadow-medium);
            padding: 15px 25px;
            border-bottom: none;
            position: sticky;
            top: 0;
            z-index: 1020;
            backdrop-filter: blur(10px);
        }

        .navbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            pointer-events: none;
        }

        .navbar .logo {
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        }

        .navbar .logo:hover {
            transform: scale(1.08) rotate(2deg);
            filter: drop-shadow(0 8px 16px rgba(0, 0, 0, 0.3)) brightness(1.1);
        }

        .navbar .nav-link {
            color: white !important;
            font-size: 1.1em;
            font-weight: 600;
            padding: 12px 20px !important;
            margin: 0 5px;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
        }

        .navbar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .navbar .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .navbar .nav-link:hover::before {
            left: 100%;
        }

        /* ✅ NUEVO: Contenedor para campana y usuario */
        .navbar-right-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Campana de notificaciones */
        .notification-bell {
            position: relative;
            display: inline-block;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-bell:hover {
            transform: scale(1.1);
        }

        .notification-bell.has-notifications {
            animation: bellRing 2s infinite;
        }

        .notification-bell .bell-icon {
            font-size: 1.8rem;
            color: white;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #ff4757, #ff3742);
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4);
            animation: pulse 2s infinite;
        }

        .notification-badge.hidden {
            display: none;
        }

        /* User profile */
        .user-profile {
            display: flex;
            align-items: center;
            position: relative;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .user-profile:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .user-profile img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            margin-right: 12px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            object-fit: cover;
        }

        .user-profile:hover img {
            border-color: white;
            transform: scale(1.1);
        }

        .user-name {
            font-weight: 600;
            color: white;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .user-dropdown {
            position: absolute;
            top: 120%;
            right: 0;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px var(--shadow-heavy);
            padding: 15px 0;
            min-width: 200px;
            display: none;
            z-index: 1000;
            border: 1px solid rgba(227, 139, 91, 0.2);
        }

        .user-dropdown.show {
            display: block;
            animation: fadeInScale 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .user-dropdown a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--dark-brown);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .user-dropdown a i {
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .user-dropdown a:hover {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            transform: translateX(5px);
        }

        /* Container mejorado */
        .container {
            background: rgba(255, 255, 255, 0.95);
            margin: 30px auto;
            padding: 40px;
            max-width: 1400px;
            border-radius: 25px;
            box-shadow: 0 20px 60px var(--shadow-light);
            border: 1px solid rgba(246, 184, 143, 0.2);
            backdrop-filter: blur(10px);
            animation: slideInUp 0.6s ease-out;
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(246, 184, 143, 0.05) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
            pointer-events: none;
        }

        h2 {
            font-size: 2.5em;
            font-weight: 700;
            background: linear-gradient(135deg, var(--secondary-orange), var(--primary-orange));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, var(--secondary-orange), var(--primary-orange));
            border-radius: 2px;
        }

        /* Modal de notificaciones */
        .notification-modal {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
        }

        .notification-modal .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #fff 0%, #FCE8D5 100%);
        }

        .notification-item {
            border: 1px solid rgba(227, 139, 91, 0.2);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .notification-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
        }

        .notification-item:hover {
            background: rgba(246, 184, 143, 0.1);
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .notification-item.selected {
            background: linear-gradient(135deg, rgba(246, 184, 143, 0.2), rgba(227, 139, 91, 0.1));
            border-color: var(--secondary-orange);
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .notification-title {
            font-weight: bold;
            color: var(--dark-brown);
            font-size: 1.1rem;
        }

        .notification-time {
            font-size: 0.85rem;
            color: #666;
            font-style: italic;
        }

        .notification-details {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.4;
        }

        .notification-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }

        .btn-approve {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-approve:hover {
            background: linear-gradient(135deg, #20c997, #17a2b8);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-reject {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-reject:hover {
            background: linear-gradient(135deg, #c82333, #bd2130);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-view {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        /* Loading states */
        .btn.loading {
            position: relative;
            pointer-events: none;
        }

        .btn.loading::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            margin: -8px 0 0 -8px;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .btn.loading span {
            opacity: 0;
        }

        /* Password validation states */
        .password-validation-success {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .password-validation-error {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        /* User authorization info */
        #datosUsuarioAutoriza {
            transition: all 0.4s ease;
            transform-origin: top;
        }

        #datosUsuarioAutoriza .alert-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
            border: 1px solid rgba(40, 167, 69, 0.3);
            border-radius: 10px;
        }

        /* Success message animation */
        .success-message {
            animation: successPulse 0.6s ease-in-out;
        }

        @keyframes successPulse {
            0% {
                transform: translate(-50%, -50%) scale(0.8);
                opacity: 0;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.05);
                opacity: 1;
            }

            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }

        /* No notifications state */
        .no-notifications {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .no-notifications i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-right-controls {
                gap: 10px;
            }

            .notification-bell .bell-icon {
                font-size: 1.5rem;
            }

            .notification-badge {
                width: 20px;
                height: 20px;
                font-size: 0.65rem;
            }

            .notification-item {
                padding: 15px;
            }

            .notification-actions {
                flex-direction: column;
            }

            .notification-actions .btn {
                width: 100%;
                margin-bottom: 5px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 3px;
            }

            .action-buttons .btn {
                width: 100%;
                height: 35px;
            }
        }

        /* Campo de correo deshabilitado */
        .campo-correo-disabled {
            background-color: #f8f9fa;
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Animación para habilitar campo */
        .campo-correo-enabled {
            background-color: white;
            transition: all 0.3s ease;
            animation: fieldHighlight 0.5s ease-in-out;
        }

        @keyframes fieldHighlight {
            0% {
                background-color: #ffffcc;
            }

            100% {
                background-color: white;
            }
        }
    </style>


    
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <img src="{{ asset('img/FontTrack.png') }}" alt="logo" height="70px" width="100px" class="logo"
                onclick="window.location.href='{{ route('users') }}'">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('users') }}">
                            <i class="bi bi-house me-2"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('lugares.index') }}">
                            <i class="bi bi-geo-alt me-2"></i>Lugares
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reportes.index') }}">
                            <i class="bi bi-file-text me-2"></i>Reportes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('vehiculos.index') }}">
                            <i class="bi bi-truck me-2"></i>Vehículos
                        </a>
                    </li>
                </ul>

                <div class="navbar-right-controls">
                    @if(Auth::user()->id_lugar)
                    <!-- Campana de notificaciones -->
                    <div class="notification-bell" id="notificationBell" title="Notificaciones pendientes">
                        <i class="fas fa-bell bell-icon"></i>
                        <span class="notification-badge hidden" id="notificationBadge">0</span>
                    </div>
                    @endif

                    <!-- Perfil de usuario -->
                    <div class="user-profile" id="userProfileDropdown">
                        <img src="{{ Auth::user()->foto_usuario_url ?? asset('img/usuario_default.png') }}"
                            alt="Foto de perfil">
                        <span class="user-name">{{ Auth::user()->nombre }}</span>
                        <div class="user-dropdown" id="userDropdownMenu">
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Modal de Notificaciones -->
    @if(Auth::user()->id_lugar)
    <div class="modal fade notification-modal" id="modalNotificaciones" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-bell"></i>
                        Notificaciones Pendientes
                        <span class="badge bg-warning" id="modalNotificationCount">0</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: 600px; overflow-y: auto;">
                    <div id="notificationsContainer">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p class="mt-2">Cargando notificaciones...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnRefreshNotifications">
                        <i class="fas fa-sync-alt"></i> Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles de notificación -->
    <div class="modal fade" id="modalDetalleNotificacion" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de la Notificación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detalleNotificacionBody">
                    <!-- Se carga via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para aprobar notificación -->
    <div class="modal fade" id="modalAprobarNotificacion" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aprobar Notificación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>¡Atención!</strong> Al aprobar esta notificación se creará automáticamente un reporte de
                        falla y se descontará el stock de los materiales.
                    </div>
                    <div class="mb-3">
                        <label for="passwordAprobar" class="form-label">Confirma tu contraseña:</label>
                        <div class="input-group">
                            <input type="password" id="passwordAprobar" class="form-control"
                                placeholder="Ingresa tu contraseña para confirmar" required>
                            <button type="button" class="btn btn-outline-secondary" id="btnTogglePasswordAprobar">
                                <i class="bi bi-eye" id="iconTogglePasswordAprobar"></i>
                            </button>
                        </div>
                        <small id="passwordValidating" class="text-info" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Validando contraseña...
                        </small>
                        <small id="passwordValidSuccess" class="text-success" style="display: none;">
                            <i class="fas fa-check-circle"></i> Contraseña válida
                        </small>
                    </div>

                    <div id="datosUsuarioAutoriza" class="mb-3" style="display: none;">
                        <div class="alert alert-success">
                            <h6><i class="fas fa-user-check"></i> Usuario que autoriza:</h6>
                            <p class="mb-1"><strong>Nombre:</strong> <span id="nombreUsuarioAutoriza"></span></p>
                            <p class="mb-0"><strong>Correo:</strong> <span id="correoUsuarioAutoriza"></span></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="comentariosAprobar" class="form-label">
                            <i class="fas fa-envelope"></i> Correo para envío del reporte:
                        </label>
                        <input type="email" id="comentariosAprobar" class="form-control"
                            placeholder="correo@ejemplo.com" required>
                        <small class="form-text text-muted">Se enviará el PDF del reporte a este correo
                            electrónico</small>
                    </div>
                    <div id="errorAprobar" class="alert alert-danger d-none">
                        <i class="fas fa-times-circle"></i>
                        <span id="errorAprobarMessage">Error al procesar</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnConfirmarAprobar" disabled>
                        <i class="fas fa-check"></i> Aprobar y Enviar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para rechazar notificación -->
    <div class="modal fade" id="modalRechazarNotificacion" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rechazar Notificación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Nota:</strong> Al rechazar esta notificación, se mantendrá en el registro para auditoría
                        pero no se procesará.
                    </div>
                    <div class="mb-3">
                        <label for="comentariosRechazar" class="form-label">Motivo del rechazo (requerido):</label>
                        <textarea id="comentariosRechazar" class="form-control" rows="4"
                            placeholder="Explica por qué se rechaza esta notificación" required></textarea>
                    </div>
                    <div id="errorRechazar" class="alert alert-danger d-none">
                        <i class="fas fa-times-circle"></i>
                        <span id="errorRechazarMessage">Error al procesar</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarRechazar">
                        <i class="fas fa-times"></i> Rechazar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal para subir archivos del Cardex -->
    <div class="modal fade" id="modalCardex" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formCardex" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">¿Archivos del Kardex?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="id_lugar" class="form-label">Lugar:</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->lugar->nombre ?? 'Sin lugar asignado' }}" readonly>
                            <input type="hidden" name="id_lugar" value="{{ Auth::user()->id_lugar }}">
                        </div>
                        <div class="mb-3">
                            <label for="archivo_cardex" class="form-label">Seleccionar Archivo Excel:</label>
                            <input type="file" id="archivo_cardex" name="archivo_cardex" class="form-control"
                                accept=".xlsx,.xls" required>
                            <div class="form-text">El archivo debe contener: Clave Material, Descripción, Genérico,
                                Clasificación, Existencia, Costo Promedio.</div>
                        </div>
                        <div class="alert alert-info">
                            <strong>Formato requerido:</strong>
                            <ul class="mb-0">
                                <li>Clave Material</li>
                                <li>Descripción</li>
                                <li>Genérico</li>
                                <li>Clasificación</li>
                                <li>Existencia</li>
                                <li>Costo Promedio</li>
                            </ul>
                        </div>
                        <div id="progreso" class="mb-3" style="display: none;">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small class="text-muted">Procesando archivo...</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="btnSubirCardex">Subir Archivo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Contenido principal de Materiales -->
    @if(Auth::user()->id_lugar || Auth::user()->tipo_usuario == 1)
    <div class="container mt-4">
        <h2 class="mb-3">Lista de Materiales - {{ Auth::user()->lugar->nombre ?? 'Todos los lugares' }}</h2>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCardex">🔑 Subir
                    Kardex</button>
                <button class="btn btn-warning" id="btnReporteFallas">📋 Reporte de Fallas</button>
            </div>
            <a href="{{ route('materials.export') }}" class="btn btn-outline-primary">📤 Exportar Excel (Existencia)</a>
            <form class="d-flex" action="{{ route('materials') }}" method="GET">
                <input class="form-control me-2" type="search" name="query" placeholder="Buscar material"
                    aria-label="Buscar" value="{{ request('query') }}">
                <button class="btn btn-outline-success me-2" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMaterial"
                id="btnNuevoMaterial">Registrar Material</button>
        </div>
        <div class="table-responsive">
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Clave</th>
                        <th>Descripción</th>
                        <th>Genérico</th>
                        <th>Clasificación</th>
                        <th>Existencia</th>
                        <th>Costo ($)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materiales->where('id_lugar', Auth::user()->id_lugar) as $material)
                        <tr data-id="{{ $material->id_material }}">
                            <td>{{ $material->id_material }}</td>
                            <td>{{ $material->clave_material }}</td>
                            <td>{{ $material->descripcion }}</td>
                            <td>{{ $material->generico }}</td>
                            <td>{{ $material->clasificacion }}</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <button class="btn btn-sm btn-success btnAumentar"
                                        data-id="{{ $material->id_material }}" title="Aumentar existencia"><i
                                            class="bi bi-plus"></i></button>
                                    <span class="mx-2">{{ $material->existencia }}</span>
                                    <button class="btn btn-sm btn-danger btnDisminuir"
                                        data-id="{{ $material->id_material }}" title="Reportar falla"><i
                                            class="bi bi-dash"></i></button>
                                </div>
                            </td>
                            <td>{{ $material->costo_promedio }}</td>
                            <td class="d-flex flex-column flex-md-row">
                                <button class="btn btn-info btnVer" data-id="{{ $material->id_material }}"
                                    data-bs-toggle="modal" data-bs-target="#modalMaterial"><i
                                        class="bi bi-eye"></i></button>
                                <button class="btn btn-warning btnEditar" data-id="{{ $material->id_material }}"
                                    data-bs-toggle="modal" data-bs-target="#modalMaterial"><i
                                        class="bi bi-pencil"></i></button>
                                <button class="btn btn-danger btn-sm btnEliminar" data-id="{{ $material->id_material }}"><i
                                        class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    @if($materiales->where('id_lugar', Auth::user()->id_lugar)->count() == 0)
                        <tr>
                            <td colspan="8" class="text-center">No hay materiales registrados para tu lugar.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex justify-content-center">
            {{ $materiales->appends(['query' => request('query')])->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @else
    <!-- Mensaje para usuarios sin lugar asignado -->
    <div class="container mt-4">
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
            <h4>Acceso Restringido</h4>
            <p>No tienes un lugar asignado para acceder a esta sección. Contacta al administrador para obtener acceso.</p>
        </div>
    </div>
    @endif

    <!-- Modal Material (Registro/Edición/Ver) -->
    <div class="modal fade" id="modalMaterial" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formMaterial">
                    @csrf
                    <input type="hidden" id="materialId">
                    <input type="hidden" name="id_lugar" value="{{ Auth::user()->id_lugar }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMaterialTitle"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="modalMaterialBody"></div>
                    <div class="modal-footer" id="modalMaterialFooter"></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirmación de Eliminación -->
    <div class="modal fade" id="modalEliminar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este material?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Aumentar Existencia -->
    <div class="modal fade" id="modalAumentar" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form id="formAumentar">
                    @csrf
                    <input type="hidden" id="materialIdAumentar">
                    <div class="modal-header">
                        <h5 class="modal-title">Aumentar Existencia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Material:</strong> <span id="materialNombreAumentar"></span></p>
                        <p><strong>Existencia actual:</strong> <span id="existenciaActualAumentar"></span></p>
                        <label for="cantidadAumentar">Cantidad a aumentar:</label>
                        <input type="number" id="cantidadAumentar" name="cantidad" class="form-control" min="1"
                            required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Aumentar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Reporte de Fallas -->
    <div class="modal fade" id="modalFalla" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formFalla">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">REPORTE DE FALLAS / USO DE MATERIALES</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="id_lugar_falla" class="form-label">Lugar:</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->lugar->nombre ?? 'Sin lugar asignado' }}" readonly>
                            <input type="hidden" name="id_lugar" value="{{ Auth::user()->id_lugar }}">
                        </div>

                        <div class="mb-3">
                            <label for="vehiculo_eco" class="form-label">Selecciona Vehículo (ECO):</label>
                            <select id="vehiculo_eco" name="vehiculo_eco" class="form-select">
                                <option value="">-- Cargando vehículos... --</option>
                            </select>
                            <small class="text-muted">Los datos del vehículo se llenarán automáticamente</small>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label>No. ECO</label>
                                <input type="text" name="eco" id="campo_eco" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Placas</label>
                                <input type="text" name="placas" id="campo_placas" class="form-control" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Marca</label>
                                <input type="text" name="marca" id="campo_marca" class="form-control" readonly>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label>Año</label>
                                <input type="text" name="anio" id="campo_anio" class="form-control" readonly>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label>KM</label>
                                <input type="text" name="km" id="campo_km" class="form-control" readonly>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label>Fecha</label>
                                <input type="date" name="fecha" id="campo_fecha" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Nombre del Conductor</label>
                            <input type="text" name="nombre_conductor" id="campo_conductor" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Descripción Servicio / Fallo</label>
                            <textarea name="descripcion" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Observaciones Técnicas del Trabajo Realizado</label>
                            <textarea name="observaciones" rows="3" class="form-control"></textarea>
                        </div>
                        <h6 class="mt-3">Materiales a utilizar</h6>
                        <div class="mb-3">
                            <label for="materialBuscar">Buscar Material</label>
                            <input type="text" id="materialBuscar" class="form-control" list="materialesList"
                                placeholder="Ingrese clave o descripción">
                            <datalist id="materialesList">
                                @foreach($materiales->where('id_lugar', Auth::user()->id_lugar) as $material)
                                    <option data-id="{{ $material->id_material }}"
                                        value="{{ $material->clave_material }} - {{ $material->descripcion }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="mb-3">
                            <table class="table table-bordered" id="materialesTable">
                                <thead>
                                    <tr>
                                        <th>Clave</th>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="materialesTableBody"></tbody>
                            </table>
                        </div>
                        <div class="mb-3">
                            <label for="correo_destino" class="form-label">Correo para envío del reporte:</label>
                            <input type="email" name="correo_destino" id="correo_destino" class="form-control"
                                placeholder="correo@ejemplo.com" required>
                            <small class="form-text text-muted">Se enviará el PDF del reporte a este correo</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmitFalla">Enviar Reporte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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

            // Mostrar/Ocultar dropdown de usuario
            $('#userProfileDropdown').click(function () {
                $('#userDropdownMenu').toggleClass('show');
            });

            $(document).click(function (e) {
                if (!$(e.target).closest('#userProfileDropdown, #userDropdownMenu').length) {
                    $('#userDropdownMenu').removeClass('show');
                }
            });

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
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p class="mt-2">Cargando notificaciones...</p>
                    </div>
                `);

                $.get('/notificaciones/pendientes')
                    .done(function (response) {
                        if (response.success) {
                            renderNotifications(response.notificaciones, response.total);
                        } else {
                            $('#notificationsContainer').html(`
                                <div class="no-notifications">
                                    <i class="fas fa-bell-slash"></i>
                                    <h5>Error al cargar notificaciones</h5>
                                    <p class="text-muted">${response.error || 'Intenta de nuevo más tarde.'}</p>
                                </div>
                            `);
                        }
                    })
                    .fail(function () {
                        $('#notificationsContainer').html(`
                            <div class="no-notifications">
                                <i class="fas fa-bell-slash"></i>
                                <h5>Error al cargar notificaciones</h5>
                                <p class="text-muted">No se pudo conectar con el servidor.</p>
                            </div>
                        `);
                    });
            }

            // Renderizar notificaciones
            function renderNotifications(notificaciones, total) {
                const userLugarId = {{ Auth::user()->id_lugar }};
                // Verificación adicional en el frontend (aunque el backend ya filtra por id_lugar)
                const filteredNotificaciones = notificaciones.filter(notif => notif.id_lugar === userLugarId);

                $('#modalNotificationCount').text(filteredNotificaciones.length);

                if (filteredNotificaciones.length === 0) {
                    $('#notificationsContainer').html(`
                        <div class="no-notifications">
                            <i class="fas fa-bell-slash"></i>
                            <h5>No hay notificaciones pendientes</h5>
                            <p class="text-muted">Todas las notificaciones han sido procesadas.</p>
                        </div>
                    `);
                    return;
                }

                let html = '';
                filteredNotificaciones.forEach(function (notif) {
                    html += `
                        <div class="notification-item" data-id="${notif.id}">
                            <div class="notification-header">
                                <span class="notification-title">${notif.resumen}</span>
                                <span class="notification-time">${notif.tiempo_transcurrido}</span>
                            </div>
                            <div class="notification-details">
                                <strong>Usuario:</strong> ${notif.usuario_reporta}<br>
                                <strong>Lugar:</strong> ${notif.lugar}<br>
                                <strong>Vehículo:</strong> ${notif.vehiculo || 'No especificado'}<br>
                                <strong>Materiales:</strong> ${notif.materiales_count} items<br>
                                <strong>Fecha:</strong> ${notif.fecha_creacion}
                            </div>
                            <div class="notification-actions">
                                <button class="btn btn-view btn-sm" onclick="verDetalleNotificacion(${notif.id})">
                                    <i class="fas fa-eye"></i> Ver Detalles
                                </button>
                                <button class="btn btn-approve btn-sm" onclick="aprobarNotificacion(${notif.id})">
                                    <i class="fas fa-check"></i> Aprobar
                                </button>
                                <button class="btn btn-reject btn-sm" onclick="rechazarNotificacion(${notif.id})">
                                    <i class="fas fa-times"></i> Rechazar
                                </button>
                            </div>
                        </div>
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
                    $('#notificationBell').addClass('has-notifications');
                } else {
                    $('#notificationBadge').addClass('hidden');
                    $('#notificationBell').removeClass('has-notifications');
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
                                materialesHtml = '<h6>Materiales:</h6><ul>';
                                notif.materiales.forEach(function (material) {
                                    materialesHtml += `<li>${material.descripcion} (Cantidad: ${material.cantidad})</li>`;
                                });
                                materialesHtml += '</ul>';
                            } else {
                                materialesHtml = '<p>No se especificaron materiales.</p>';
                            }

                            $('#detalleNotificacionBody').html(`
                                <p><strong>ID:</strong> ${notif.id}</p>
                                <p><strong>Lugar:</strong> ${notif.lugar_nombre}</p>
                                <p><strong>Vehículo:</strong> ${notif.eco || ''} ${notif.placas || ''} ${notif.marca || ''}</p>
                                <p><strong>Año:</strong> ${notif.anio || 'No especificado'}</p>
                                <p><strong>KM:</strong> ${notif.km || 'No especificado'}</p>
                                <p><strong>Fecha:</strong> ${notif.fecha || 'No especificada'}</p>
                                <p><strong>Conductor:</strong> ${notif.nombre_conductor || 'No especificado'}</p>
                                <p><strong>Descripción:</strong> ${notif.descripcion || 'No especificada'}</p>
                                <p><strong>Observaciones:</strong> ${notif.observaciones || 'No especificadas'}</p>
                                <p><strong>Usuario Reporta:</strong> ${notif.usuario_reporta}</p>
                                <p><strong>Correo Reporta:</strong> ${notif.correo_reporta}</p>
                                <p><strong>Correo Destino:</strong> ${notif.correo_destino || 'No especificado'}</p>
                                <p><strong>Fecha Creación:</strong> ${notif.fecha_creacion}</p>
                                <p><strong>Estado:</strong> ${notif.estado}</p>
                                ${materialesHtml}
                            `);
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
                $('#passwordAprobar').on('input', function () {
                    clearTimeout(timeout);
                    const password = $(this).val();
                    if (password.length > 0) {
                        $('#passwordValidating').show();
                        $('#passwordValidSuccess').hide();
                        timeout = setTimeout(function () {
                            $.post('/validar-password', { password })
                                .done(function (response) {
                                    if (response.valid) {
                                        $('#passwordValidating').hide();
                                        $('#passwordValidSuccess').show();
                                        $('#btnConfirmarAprobar').prop('disabled', false);
                                        $('#datosUsuarioAutoriza').show();
                                        $('#nombreUsuarioAutoriza').text('{{ Auth::user()->nombre }}');
                                        $('#correoUsuarioAutoriza').text('{{ Auth::user()->email }}');
                                    } else {
                                        $('#passwordValidating').hide();
                                        $('#passwordValidSuccess').hide();
                                        $('#errorAprobarMessage').text('Contraseña incorrecta');
                                        $('#errorAprobar').removeClass('d-none');
                                        $('#btnConfirmarAprobar').prop('disabled', true);
                                    }
                                })
                                .fail(function () {
                                    $('#passwordValidating').hide();
                                    $('#errorAprobarMessage').text('Error al validar contraseña');
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
                $('#btnTogglePasswordAprobar').click(function () {
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
                $.get('/vehiculos/list').done(function (response) {
                    if (response.success) {
                        let options = '<option value="">-- Selecciona un vehículo --</option>';
                        response.vehiculos.forEach(function (vehiculo) {
                            options += `<option value="${vehiculo.eco}" 
                                data-eco="${vehiculo.eco}" 
                                data-placas="${vehiculo.placas}" 
                                data-marca="${vehiculo.marca}" 
                                data-anio="${vehiculo.anio}" 
                                data-km="${vehiculo.km}">
                                ${vehiculo.eco} - ${vehiculo.marca}
                            </option>`;
                        });
                        $('#vehiculo_eco').html(options);
                    }
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
                    $.get(`/materiales/${id}`).done(function (response) {
                        if (response.success) {
                            const material = response.material;
                            const row = `
                                <tr data-id="${material.id_material}">
                                    <td>${material.clave_material}</td>
                                    <td>${material.descripcion}</td>
                                    <td><input type="number" class="form-control cantidad" min="1" max="${material.existencia}" value="1"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm btnEliminarMaterial"><i class="fas fa-trash"></i></button></td>
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
                    nombre_usuario_reporta: '{{ Auth::user()->nombre }}',
                    correo_usuario_reporta: '{{ Auth::user()->email }}',
                    correo_destino: $('#correo_destino').val(),
                    materials: JSON.stringify(materiales)
                };

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
                    error: function () {
                        Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                    }
                });
            });

            // Subir archivo Cardex
            $('#formCardex').submit(function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                $('#btnSubirCardex').prop('disabled', true).addClass('loading');
                $('#progreso').show();
                $.ajax({
                    url: '/materiales/import',
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
                        if (response.success) {
                            Swal.fire('Éxito', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.error || 'Error al procesar el archivo', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                    },
                    complete: function () {
                        $('#btnSubirCardex').prop('disabled', false).removeClass('loading');
                        $('#progreso').hide();
                        $('#progreso .progress-bar').css('width', '0%');
                    }
                });
            });

            // Registrar/Editar material
            $('#btnNuevoMaterial').click(function () {
                $('#modalMaterialTitle').text('Registrar Material');
                $('#modalMaterialBody').html(`
                    <div class="mb-3">
                        <label for="clave_material" class="form-label">Clave Material</label>
                        <input type="text" name="clave_material" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <input type="text" name="descripcion" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="generico" class="form-label">Genérico</label>
                        <input type="text" name="generico" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="clasificacion" class="form-label">Clasificación</label>
                        <input type="text" name="clasificacion" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="existencia" class="form-label">Existencia</label>
                        <input type="number" name="existencia" class="form-control" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="costo_promedio" class="form-label">Costo Promedio</label>
                        <input type="number" name="costo_promedio" class="form-control" step="0.01" min="0" required>
                    </div>
                `);
                $('#modalMaterialFooter').html(`
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                `);
            });

            // Ver material
            $(document).on('click', '.btnVer', function () {
                const id = $(this).data('id');
                $.get(`/materiales/${id}`).done(function (response) {
                    if (response.success) {
                        const material = response.material;
                        $('#modalMaterialTitle').text('Detalles del Material');
                        $('#modalMaterialBody').html(`
                            <p><strong>ID:</strong> ${material.id_material}</p>
                            <p><strong>Clave:</strong> ${material.clave_material}</p>
                            <p><strong>Descripción:</strong> ${material.descripcion}</p>
                            <p><strong>Genérico:</strong> ${material.generico || 'No especificado'}</p>
                            <p><strong>Clasificación:</strong> ${material.clasificacion || 'No especificada'}</p>
                            <p><strong>Existencia:</strong> ${material.existencia}</p>
                            <p><strong>Costo Promedio:</strong> $${material.costo_promedio}</p>
                        `);
                        $('#modalMaterialFooter').html(`
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        `);
                    }
                });
            });

            // Editar material
            $(document).on('click', '.btnEditar', function () {
                const id = $(this).data('id');
                $.get(`/materiales/${id}`).done(function (response) {
                    if (response.success) {
                        const material = response.material;
                        $('#materialId').val(material.id_material);
                        $('#modalMaterialTitle').text('Editar Material');
                        $('#modalMaterialBody').html(`
                            <div class="mb-3">
                                <label for="clave_material" class="form-label">Clave Material</label>
                                <input type="text" name="clave_material" class="form-control" value="${material.clave_material}" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <input type="text" name="descripcion" class="form-control" value="${material.descripcion}" required>
                            </div>
                            <div class="mb-3">
                                <label for="generico" class="form-label">Genérico</label>
                                <input type="text" name="generico" class="form-control" value="${material.generico || ''}">
                            </div>
                            <div class="mb-3">
                                <label for="clasificacion" class="form-label">Clasificación</label>
                                <input type="text" name="clasificacion" class="form-control" value="${material.clasificacion || ''}">
                            </div>
                            <div class="mb-3">
                                <label for="existencia" class="form-label">Existencia</label>
                                <input type="number" name="existencia" class="form-control" value="${material.existencia}" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label for="costo_promedio" class="form-label">Costo Promedio</label>
                                <input type="number" name="costo_promedio" class="form-control" value="${material.costo_promedio}" step="0.01" min="0" required>
                            </div>
                        `);
                        $('#modalMaterialFooter').html(`
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        `);
                    }
                });
            });

            // Guardar/Actualizar material
            $('#formMaterial').submit(function (e) {
                e.preventDefault();
                const id = $('#materialId').val();
                const url = id ? `/materiales/${id}` : '/materiales';
                const method = id ? 'PUT' : 'POST';
                const data = $(this).serialize();

                $.ajax({
                    url: url,
                    method: method,
                    data: data,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Éxito', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.error || 'Error al procesar el material', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                    }
                });
            });

            // Aumentar existencia
            $(document).on('click', '.btnAumentar', function () {
                const id = $(this).data('id');
                $.get(`/materiales/${id}`).done(function (response) {
                    if (response.success) {
                        const material = response.material;
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
                    url: `/materiales/${id}/aumentar`,
                    method: 'POST',
                    data: data,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Éxito', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.error || 'Error al aumentar existencia', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
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
                    url: `/materiales/${id}`,
                    method: 'DELETE',
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Éxito', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.error || 'Error al eliminar material', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                    }
                });
            });
        });
    </script>
</body>

</html>