<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Vehículos - FontTrack</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @guest
        <script>
            window.location.href = '/login';
        </script>
    @endguest

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
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes modalBubbleIn {
            0% { transform: scale(0.3) translateY(100px); opacity: 0; }
            50% { transform: scale(1.05) translateY(-10px); opacity: .8; }
            70% { transform: scale(0.95) translateY(5px); opacity: .9; }
            100% { transform: scale(1) translateY(0); opacity: 1; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0) scale(1.2); }
            25% { transform: translateX(-2px) scale(1.2); }
            75% { transform: translateX(2px) scale(1.2); }
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

        .navbar .navbar-brand {
            color: white;
            font-weight: 700;
            font-size: 1.8em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .navbar .navbar-brand:hover {
            color: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
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

        .navbar-toggler-icon {
            filter: invert(1);
        }

        /* User profile mejorado */
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

        /* Search and filters mejorado */
        .search-filters {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--secondary-orange) 100%);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px var(--shadow-medium);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .search-filters::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        .search-input {
            border: none;
            border-radius: 15px;
            padding: 15px 20px;
            font-size: 16px;
            font-weight: 500;
            box-shadow: inset 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 0 30px rgba(227, 139, 91, 0.4), inset 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: scale(1.02);
            background: white;
        }

        .search-input::placeholder {
            color: rgba(99, 77, 59, 0.6);
            font-weight: 400;
        }

        /* Botones mejorados */
        .btn {
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            padding: 14px 20px;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border: none;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-orange), var(--primary-orange));
            color: white;
            box-shadow: 0 4px 15px rgba(227, 139, 91, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(227, 139, 91, 0.4);
            color: white;
        }

        .btn-info {
            background: linear-gradient(135deg, var(--accent-blue), #7ab8c8);
            color: white;
            box-shadow: 0 4px 15px rgba(136, 192, 208, 0.3);
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #7ab8c8, var(--accent-blue));
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(136, 192, 208, 0.4);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--accent-yellow), #d4901a);
            color: white;
            box-shadow: 0 4px 15px rgba(229, 163, 77, 0.3);
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d4901a, var(--accent-yellow));
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(229, 163, 77, 0.4);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--accent-red), #c9302c);
            color: white;
            box-shadow: 0 4px 15px rgba(217, 83, 79, 0.3);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c9302c, var(--accent-red));
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(217, 83, 79, 0.4);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #5a6268, #6c757d);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #20c997, #28a745);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 50px;
            padding: 18px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .btn-outline-light:hover {
            background: white;
            color: var(--secondary-orange);
            border-color: white;
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.3);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9rem;
            border-radius: 10px;
        }

        /* Botones de acción mejorados */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
        }

        .action-buttons .btn {
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
        }

        .action-buttons .btn i {
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        /* Efectos hover específicos para cada botón */
        .btn-info:hover i {
            transform: scale(1.2);
        }

        .btn-warning:hover i {
            transform: rotate(15deg) scale(1.2);
        }

        .btn-danger:hover i {
            transform: scale(1.2);
            animation: shake 0.5s ease-in-out;
        }

        /* Modales mejorados */
        .modal.show .modal-dialog {
            animation: modalBubbleIn .6s cubic-bezier(.68, -.55, .265, 1.55);
        }

        .modal-content {
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 25px 80px var(--shadow-heavy);
            border: none;
            backdrop-filter: blur(10px);
        }

        .modal-backdrop {
            background: linear-gradient(45deg, rgba(0, 0, 0, .5), rgba(0, 0, 0, .7));
            backdrop-filter: blur(5px);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            color: white;
            border: none;
            padding: 20px 30px;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.3rem;
        }

        .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .btn-close:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .modal-body {
            padding: 30px;
            background: linear-gradient(135deg, #fff 0%, #FCE8D5 100%);
        }

        .modal-footer {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            border: none;
            padding: 20px 30px;
        }

        /* Tabla mejorada */
        .table-responsive {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px var(--shadow-light);
            background: white;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }

        .table thead {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--secondary-orange) 100%);
            color: white;
            position: relative;
        }

        .table thead::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
        }

        .table th {
            padding: 20px 15px;
            font-weight: 600;
            font-size: 1.1rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            border: none;
            position: relative;
        }

        .table th::after {
            content: '';
            position: absolute;
            right: 0;
            top: 25%;
            bottom: 25%;
            width: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        .table th:last-child::after {
            display: none;
        }

        .table td {
            padding: 18px 15px;
            border: none;
            border-bottom: 1px solid rgba(246, 184, 143, 0.1);
            font-weight: 500;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            cursor: pointer;
            position: relative;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, rgba(246, 184, 143, 0.1), rgba(227, 139, 91, 0.05));
            transform: scale(1.01) translateY(-2px);
            box-shadow: 0 8px 25px rgba(246, 184, 143, 0.2);
            border-radius: 10px;
        }

        /* Status badges mejorados */
        .badge {
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 0.8em;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-activo {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            box-shadow: 0 2px 10px rgba(40, 167, 69, 0.3);
        }

        .badge-inactivo {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
            box-shadow: 0 2px 10px rgba(108, 117, 125, 0.3);
        }

        .badge-mantenimiento {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: white;
            box-shadow: 0 2px 10px rgba(255, 193, 7, 0.3);
        }

        /* Form controls mejorados */
        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 15px 20px;
            border: 2px solid rgba(246, 184, 143, 0.3);
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--secondary-orange);
            box-shadow: 0 0 20px rgba(227, 139, 91, 0.3);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-brown);
            margin-bottom: 8px;
        }

        /* Notification mejorada */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            z-index: 9999;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .notification.show {
            opacity: 1;
            transform: translateX(0);
        }

        .notification.success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .notification.error {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }

        /* Progress bar para carga de archivos */
        .progress {
            height: 10px;
            border-radius: 5px;
            overflow: hidden;
            background: rgba(246, 184, 143, 0.2);
        }

        .progress-bar {
            background: linear-gradient(135deg, var(--secondary-orange), var(--primary-orange));
            transition: width 0.3s ease;
        }

        /* Loading state para tabla */
        .table-loading {
            position: relative;
        }

        .table-loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid var(--primary-orange);
            border-top: 4px solid var(--secondary-orange);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Empty state mejorado */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: rgba(99, 77, 59, 0.6);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h4 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--dark-brown);
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        /* Responsive mejorado */
        @media (max-width: 768px) {
            .container {
                margin: 15px;
                padding: 25px;
                border-radius: 20px;
            }

            h2 {
                font-size: 2rem;
            }

            .search-filters {
                padding: 20px;
            }

            .search-input {
                padding: 12px 15px;
                font-size: 14px;
            }

            .table th,
            .table td {
                padding: 12px 8px;
                font-size: 0.9rem;
            }

            .btn-sm {
                padding: 6px 12px;
                font-size: 0.8rem;
            }

            .user-profile img {
                width: 35px;
                height: 35px;
            }

            .navbar .nav-link {
                padding: 10px 15px !important;
                font-size: 1rem;
            }

            .user-profile {
                margin-top: 10px;
            }

            .user-dropdown {
                position: static;
                margin-top: 5px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }

            .action-buttons .btn {
                width: 35px;
                height: 35px;
            }

            .action-buttons .btn i {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <!-- Notification -->
    <div class="notification" id="notification">
        <span id="notificationMessage"></span>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <img src="{{ asset('img/FontTrack.png') }}" alt="logo" height="70px" width="100px" class="logo"
                onclick="window.location.href='{{ route('materiales.index') }}'">
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
                        <a class="nav-link" href="{{ route('materials') }}">
                            <i class="bi bi-box-seam me-2"></i>Materiales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reportes.index') }}">
                            <i class="bi bi-file-text me-2"></i>Reportes
                        </a>
                    </li>
                    @if(Auth::user()->tipo_usuario == 1)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('lugares.index') }}">
                                <i class="bi bi-geo-alt me-2"></i>Lugares
                            </a>
                        </li>
                    @endif
                </ul>

                <!-- Perfil de usuario -->
                <div class="user-profile ms-auto">
                    <img src="{{ Auth::user()->foto_usuario_url ?? asset('img/usuario_default.png') }}"
                        alt="Foto de perfil">
                    <span class="user-name">{{ Auth::user()->nombre }}</span>
                    <div class="user-dropdown">
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <h2 class="mb-4"><i class="bi bi-truck me-3"></i>Gestión de Vehículos</h2>

        <!-- Search and Filters -->
        <div class="search-filters">
            <div class="row">
                <div class="col-md-5">
                    <div class="d-flex">
                        <input type="text" id="searchInput" class="form-control search-input me-2"
                            placeholder="Buscar por ECO, placas, marca..." autocomplete="off">
                        <button class="btn btn-outline-light" onclick="performSearch()">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                </div>
                @if(Auth::user()->tipo_usuario == 1)
                    <div class="col-md-2">
                        <select id="lugarFilter" class="form-select search-input" onchange="filterByLugar()">
                            <option value="">Todos los lugares</option>
                            @foreach($lugares as $lugar)
                                <option value="{{ $lugar->id_lugar }}">{{ $lugar->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-2">
                    <button class="btn btn-outline-light w-100" data-bs-toggle="modal" data-bs-target="#modalVehiculo">
                        <i class="fas fa-plus"></i> Nuevo Vehículo
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-light w-100" data-bs-toggle="modal"
                        data-bs-target="#modalImportExcel">
                        <i class="fas fa-file-excel"></i> Importar Excel
                    </button>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Vehicles Table -->
        <div class="table-responsive" id="vehiclesTableContainer">
            <table class="table">
                <thead>
                    <tr>
                        <th><i class="bi bi-tag me-2"></i>ECO</th>
                        <th><i class="bi bi-credit-card me-2"></i>Placas</th>
                        <th><i class="bi bi-truck me-2"></i>Marca/Modelo</th>
                        <th><i class="bi bi-calendar me-2"></i>Año</th>
                        <th><i class="bi bi-speedometer me-2"></i>Kilometraje</th>
                        <th><i class="bi bi-person me-2"></i>Conductor</th>
                        <th><i class="bi bi-check-circle me-2"></i>Estado</th>
                        <th><i class="bi bi-geo-alt me-2"></i>Lugar</th>
                        <th><i class="bi bi-gear me-2"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody id="vehiculosTableBody">
                    @if(isset($vehiculos) && $vehiculos->count() > 0)
                        @foreach($vehiculos as $vehiculo)
                            <tr data-id="{{ $vehiculo->id }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-tag-fill text-primary me-2"></i>
                                        <strong>{{ $vehiculo->eco }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-credit-card-fill text-info me-2"></i>
                                        {{ $vehiculo->placas ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $vehiculo->marca ?? 'N/A' }}</strong>
                                        @if($vehiculo->modelo)
                                            <br><small class="text-muted">{{ $vehiculo->modelo }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $vehiculo->anio ?? 'N/A' }}</td>
                                <td>{{ number_format($vehiculo->kilometraje) }} km</td>
                                <td>{{ $vehiculo->conductor_habitual ?? 'No asignado' }}</td>
                                <td>
                                    <span class="badge badge-{{ $vehiculo->estatus }}">
                                        {{ ucfirst($vehiculo->estatus) }}
                                    </span>
                                </td>
                                <td>{{ $vehiculo->lugar->nombre ?? 'N/A' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-info btn-sm" onclick="showVehiculo({{ $vehiculo->id }})"
                                            title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editVehiculo({{ $vehiculo->id }})"
                                            title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteVehiculo({{ $vehiculo->id }})"
                                            title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="empty-state">
                                <i class="bi bi-truck"></i>
                                <h4>No hay vehículos registrados</h4>
                                <p>Aún no tienes vehículos en tu lugar de trabajo</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($vehiculos) && method_exists($vehiculos, 'links'))
            <div class="d-flex justify-content-center">
                {{ $vehiculos->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- Modal Vehículo (Create/Edit) -->
    <div class="modal fade" id="modalVehiculo" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formVehiculo">
                    @csrf
                    <input type="hidden" id="vehiculoId" name="vehiculo_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalVehiculoTitle">
                            <i class="fas fa-car"></i> Nuevo Vehículo
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_lugar" class="form-label">Lugar *</label>
                                    <select id="id_lugar" name="id_lugar" class="form-select" required>
                                        <option value="">Selecciona un lugar</option>
                                        @foreach($lugares as $lugar)
                                            <option value="{{ $lugar->id_lugar }}">{{ $lugar->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="eco" class="form-label">No. ECO *</label>
                                    <input type="text" id="eco" name="eco" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="placas" class="form-label">Placas</label>
                                    <input type="text" id="placas" name="placas" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="marca" class="form-label">Marca</label>
                                    <input type="text" id="marca" name="marca" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="modelo" class="form-label">Modelo</label>
                                    <input type="text" id="modelo" name="modelo" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="anio" class="form-label">Año</label>
                                    <input type="text" id="anio" name="anio" class="form-control" maxlength="4">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kilometraje" class="form-label">Kilometraje</label>
                                    <input type="number" id="kilometraje" name="kilometraje" class="form-control"
                                        min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color" class="form-label">Color</label>
                                    <input type="text" id="color" name="color" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="conductor_habitual" class="form-label">Conductor Habitual</label>
                                    <input type="text" id="conductor_habitual" name="conductor_habitual"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estatus" class="form-label">Estado *</label>
                                    <select id="estatus" name="estatus" class="form-select" required>
                                        <option value="activo">Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                        <option value="mantenimiento">En Mantenimiento</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" id="btnSubmitVehiculo" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Ver Vehículo -->
    <div class="modal fade" id="modalVerVehiculo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-eye"></i> Detalles del Vehículo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="vehiculoDetails">
                    <!-- Los detalles se cargarán aquí -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Importar Excel -->
    <div class="modal fade" id="modalImportExcel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formImportExcel" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-file-excel"></i> Importar Vehículos desde Excel
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Seleccionar archivo Excel</label>
                            <input type="file" id="excel_file" name="excel_file" class="form-control"
                                accept=".xlsx,.xls" required>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Formato soportado: .xlsx, .xls
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="lugar_import" class="form-label">Lugar para los vehículos *</label>
                            <select id="lugar_import" name="lugar_import" class="form-select" required>
                                <option value="">Selecciona un lugar</option>
                                @foreach($lugares as $lugar)
                                    <option value="{{ $lugar->id_lugar }}">{{ $lugar->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <strong>Formato esperado del Excel:</strong>
                            <ul class="mb-0 mt-2">
                                <li>ECO (obligatorio)</li>
                                <li>Placas</li>
                                <li>Marca</li>
                                <li>Modelo</li>
                                <li>Año</li>
                                <li>Kilometraje</li>
                                <li>Color</li>
                                <li>Conductor Habitual</li>
                                <li>Estado (activo/inactivo/mantenimiento)</li>
                            </ul>
                        </div>

                        <div id="import-progress" class="mt-3" style="display: none;">
                            <label class="form-label">Progreso de importación:</label>
                            <div class="progress">
                                <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small id="progress-text" class="form-text">Preparando importación...</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" id="btnImportExcel" class="btn btn-success">
                            <i class="fas fa-upload"></i> Importar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            console.log('🚗 Sistema de vehículos inicializado - Búsqueda AJAX habilitada');

            // Manejar el menú desplegable del perfil de usuario
            $('.user-profile').click(function (e) {
                e.stopPropagation();
                $('.user-dropdown').toggleClass('show');
            });

            // Cerrar el menú desplegable al hacer clic fuera
            $(document).click(function () {
                $('.user-dropdown').removeClass('show');
            });

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

            // 🔍 BÚSQUEDA AJAX MEJORADA
            let searchTimeout;
            
            $('#searchInput').on('input', function() {
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

            // Función de búsqueda AJAX
            function performAjaxSearch(query) {
                console.log('🔍 Iniciando búsqueda AJAX:', query);
                
                // Mostrar estado de carga
                $('#vehiclesTableContainer').addClass('table-loading');
                $('#vehiculosTableBody').html(`
                    <tr>
                        <td colspan="9" class="text-center">
                            <div class="loading-spinner"></div>
                            <p class="mt-2">Buscando vehículos...</p>
                        </td>
                    </tr>
                `);

                $.ajax({
                    url: '/vehiculos/search',
                    method: 'GET',
                    data: { q: query },
                    success: function(vehiculos) {
                        console.log('✅ Búsqueda exitosa:', vehiculos);
                        updateVehiclesTable(vehiculos);
                    },
                    error: function(xhr) {
                        console.error('❌ Error en búsqueda:', xhr);
                        $('#vehiculosTableBody').html(`
                            <tr>
                                <td colspan="9" class="text-center text-danger">
                                    <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                                    <br>Error al buscar vehículos
                                </td>
                            </tr>
                        `);
                        handleAjaxError(xhr);
                    },
                    complete: function() {
                        $('#vehiclesTableContainer').removeClass('table-loading');
                    }
                });
            }

            // Actualizar tabla con resultados
            function updateVehiclesTable(vehiculos) {
                let html = '';
                
                if (vehiculos && vehiculos.length > 0) {
                    vehiculos.forEach(vehiculo => {
                        html += `
                            <tr data-id="${vehiculo.id}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-tag-fill text-primary me-2"></i>
                                        <strong>${vehiculo.eco}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-credit-card-fill text-info me-2"></i>
                                        ${vehiculo.placas || 'N/A'}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>${vehiculo.marca || 'N/A'}</strong>
                                        ${vehiculo.modelo ? '<br><small class="text-muted">' + vehiculo.modelo + '</small>' : ''}
                                    </div>
                                </td>
                                <td>${vehiculo.anio || 'N/A'}</td>
                                <td>${vehiculo.kilometraje ? Number(vehiculo.kilometraje).toLocaleString() + ' km' : '0 km'}</td>
                                <td>${vehiculo.conductor_habitual || 'No asignado'}</td>
                                <td>
                                    <span class="badge badge-${vehiculo.estatus}">
                                        ${vehiculo.estatus.charAt(0).toUpperCase() + vehiculo.estatus.slice(1)}
                                    </span>
                                </td>
                                <td>${vehiculo.lugar ? vehiculo.lugar.nombre : 'N/A'}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-info btn-sm" onclick="showVehiculo(${vehiculo.id})" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editVehiculo(${vehiculo.id})" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteVehiculo(${vehiculo.id})" title="Eliminar">
                                            <i class="bi bi-trash"></i>
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
                                <i class="bi bi-search"></i>
                                <h4>No se encontraron resultados</h4>
                                <p>No hay vehículos que coincidan con tu búsqueda</p>
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

                $('#btnSubmitVehiculo').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

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
                        $('#btnSubmitVehiculo').prop('disabled', false).html('<i class="fas fa-save"></i> Guardar');
                    }
                });
            });

            // Form submission para importar Excel
            $('#formImportExcel').submit(function (e) {
                e.preventDefault();

                const formData = new FormData(this);

                $('#btnImportExcel').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importando...');
                $('#import-progress').show();

                // Simular progreso
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += 10;
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
                        $('#progress-text').text('Importación completada!');

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
                        $('#btnImportExcel').prop('disabled', false).html('<i class="fas fa-upload"></i> Importar');
                        setTimeout(() => {
                            $('#import-progress').hide();
                            $('#progress-bar').css('width', '0%');
                        }, 2000);
                    }
                });
            });

            // Clean form when closing modals
            $('#modalVehiculo').on('hidden.bs.modal', function () {
                $('#formVehiculo')[0].reset();
                $('#vehiculoId').val('');
                $('#modalVehiculoTitle').html('<i class="fas fa-car"></i> Nuevo Vehículo');
                $('#btnSubmitVehiculo').html('<i class="fas fa-save"></i> Guardar');
            });

            $('#modalImportExcel').on('hidden.bs.modal', function () {
                $('#formImportExcel')[0].reset();
                $('#import-progress').hide();
                $('#progress-bar').css('width', '0%');
            });

            // Search on Enter key
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

        // Global functions for buttons
        function performSearch() {
            const query = $('#searchInput').val().trim();
            if (query.length >= 2) {
                // Trigger the input event to use the same AJAX search
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
                    const vehiculo = response.data;
                    const details = `
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <i class="bi bi-tag text-primary me-2"></i>
                                                <strong>ECO:</strong>
                                            </div>
                                            <div class="col-6">${vehiculo.eco}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <i class="bi bi-credit-card text-info me-2"></i>
                                                <strong>Placas:</strong>
                                            </div>
                                            <div class="col-6">${vehiculo.placas || 'N/A'}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <i class="bi bi-truck text-success me-2"></i>
                                                <strong>Marca:</strong>
                                            </div>
                                            <div class="col-6">${vehiculo.marca || 'N/A'}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <i class="bi bi-gear text-warning me-2"></i>
                                                <strong>Modelo:</strong>
                                            </div>
                                            <div class="col-6">${vehiculo.modelo || 'N/A'}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <i class="bi bi-calendar text-danger me-2"></i>
                                                <strong>Año:</strong>
                                            </div>
                                            <div class="col-6">${vehiculo.anio || 'N/A'}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <i class="bi bi-speedometer text-primary me-2"></i>
                                                <strong>Kilometraje:</strong>
                                            </div>
                                            <div class="col-6">${vehiculo.kilometraje ? Number(vehiculo.kilometraje).toLocaleString() + ' km' : 'N/A'}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <i class="bi bi-palette text-info me-2"></i>
                                                <strong>Color:</strong>
                                            </div>
                                            <div class="col-6">${vehiculo.color || 'N/A'}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <i class="bi bi-person text-success me-2"></i>
                                                <strong>Conductor:</strong>
                                            </div>
                                            <div class="col-6">${vehiculo.conductor_habitual || 'No asignado'}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <i class="bi bi-check-circle text-warning me-2"></i>
                                                <strong>Estado:</strong>
                                            </div>
                                            <div class="col-6"><span class="badge badge-${vehiculo.estatus}">${vehiculo.estatus.charAt(0).toUpperCase() + vehiculo.estatus.slice(1)}</span></div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <i class="bi bi-geo-alt text-danger me-2"></i>
                                                <strong>Lugar:</strong>
                                            </div>
                                            <div class="col-6">${vehiculo.lugar ? vehiculo.lugar.nombre : 'N/A'}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#vehiculoDetails').html(details);
                    $('#modalVerVehiculo').modal('show');
                }
            }).fail(function (xhr) {
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

                    $('#modalVehiculoTitle').html('<i class="fas fa-edit"></i> Editar Vehículo');
                    $('#modalVehiculo').modal('show');
                }
            }).fail(function (xhr) {
                showNotification('Error al cargar los datos del vehículo', 'error');
            });
        }

        function deleteVehiculo(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este vehículo?')) {
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
                    error: function (xhr) {
                        showNotification('Error al eliminar el vehículo', 'error');
                    }
                });
            }
        }

        function filterByLugar() {
            const lugar = $('#lugarFilter').val();
            const currentUrl = new URL(window.location);
            if (lugar) {
                currentUrl.searchParams.set('lugar', lugar);
            } else {
                currentUrl.searchParams.delete('lugar');
            }
            window.location.href = currentUrl.toString();
        }
    </script>
</body>

</html>