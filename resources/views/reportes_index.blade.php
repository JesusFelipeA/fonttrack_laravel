<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reportes de Fallas - FontTrack</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        /* Animaciones modales mejoradas */
        @keyframes modalBubbleIn {
            0% {
                transform: scale(0.3) translateY(100px);
                opacity: 0;
            }

            50% {
                transform: scale(1.05) translateY(-10px);
                opacity: .8;
            }

            70% {
                transform: scale(0.95) translateY(5px);
                opacity: .9;
            }

            100% {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

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

        /* User dropdown mejorado */
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

        /* Body & Container mejorados */
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

        .container {
            background: rgba(255, 255, 255, 0.95);
            margin: 30px auto;
            padding: 40px;
            max-width: 100%;
            border-radius: 25px;
            box-shadow: 0 20px 60px var(--shadow-light);
            border: 1px solid rgba(246, 184, 143, 0.2);
            backdrop-filter: blur(10px);
            animation: slideInUp 0.6s ease-out;
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

        /* Buscador mejorado */
        .search-container {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--secondary-orange) 100%);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px var(--shadow-medium);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .search-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        .search-container .row {
            position: relative;
            z-index: 1;
        }

        .search-input {
            border: none;
            border-radius: 50px;
            padding: 18px 25px;
            font-size: 16px;
            font-weight: 500;
            box-shadow: inset 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
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

        /* Control de columnas */
        .column-controls {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .column-toggle {
            margin: 5px;
            padding: 8px 15px;
            border: 2px solid var(--primary-orange);
            background: rgba(246, 184, 143, 0.1);
            color: var(--dark-brown);
            border-radius: 20px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .column-toggle.active {
            background: var(--primary-orange);
            color: white;
        }

        .column-toggle:hover {
            background: var(--secondary-orange);
            color: white;
            transform: scale(1.05);
        }

        /* ===== TABLA RESPONSIVA MEJORADA ===== */
        .table-container {
            position: relative;
            margin-bottom: 30px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px var(--shadow-light);
            background: white;
        }

        .table-wrapper {
            /* Scroll horizontal y vertical */
            overflow: auto;
            max-height: 70vh;
            min-height: 400px;
            position: relative;

            /* Estilo de scrollbar */
            scrollbar-width: thin;
            scrollbar-color: var(--primary-orange) #f1f1f1;
        }

        .table-wrapper::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        .table-wrapper::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-wrapper::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            border-radius: 10px;
        }

        .table-wrapper::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--secondary-orange), var(--primary-orange));
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
            min-width: 1800px;
            /* Ancho mínimo para forzar scroll horizontal */
        }

        .table thead {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--secondary-orange) 100%);
            color: white;
            position: sticky;
            top: 0;
            z-index: 20;
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
            padding: 15px 12px;
            font-weight: 600;
            font-size: 0.95rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            border: none;
            position: relative;
            min-width: 120px;
            white-space: nowrap;
            text-align: center;
        }

        .table th.col-acciones {
            /* Columna de acciones fija a la derecha */
            position: sticky;
            right: 0;
            z-index: 21;
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--secondary-orange) 100%);
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            min-width: 140px;
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
            padding: 12px;
            border: none;
            border-bottom: 1px solid rgba(246, 184, 143, 0.1);
            font-weight: 500;
            vertical-align: middle;
            font-size: 0.9rem;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
        }

        .table td.col-acciones {
            /* Columna de acciones fija a la derecha */
            position: sticky;
            right: 0;
            z-index: 15;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
            min-width: 140px;
        }

        .table-hover tbody tr {
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            cursor: pointer;
        }

        .table-hover tbody tr:hover {
            background: linear-gradient(135deg, rgba(246, 184, 143, 0.15), rgba(227, 139, 91, 0.1));
            transform: scale(1.005);
            box-shadow: 0 4px 15px rgba(246, 184, 143, 0.2);
        }

        .table-hover tbody tr:hover .col-acciones {
            background: linear-gradient(135deg, rgba(246, 184, 143, 0.15), rgba(227, 139, 91, 0.1));
        }

        /* Columnas ocultas */
        .table th.hidden-col,
        .table td.hidden-col {
            display: none;
        }

        /* Celdas con contenido expandible */
        .expandable-cell {
            position: relative;
            cursor: pointer;
        }

        .expandable-cell:hover {
            background: rgba(246, 184, 143, 0.1);
            border-radius: 8px;
        }

        .expandable-cell .full-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: var(--dark-brown);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 0.85rem;
            min-width: 200px;
            max-width: 300px;
            white-space: normal;
            z-index: 1000;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .expandable-cell:hover .full-content {
            display: block;
        }

        /* Badges y elementos */
        .badge {
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 15px;
            font-weight: 600;
        }

        .badge-eco {
            background: linear-gradient(135deg, var(--accent-blue), #7ab8c8);
            color: white;
        }

        .badge-placas {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }

        .badge-cantidad {
            background: linear-gradient(135deg, var(--secondary-orange), var(--primary-orange));
            color: white;
        }

        /* ===== BOTONES MEJORADOS ===== */
        .btn {
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 12px;
            padding: 10px 15px;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border: none;
            position: relative;
            overflow: hidden;
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
            background: linear-gradient(135deg, var(--accent-yellow), #d4941f);
            color: white;
            box-shadow: 0 4px 15px rgba(229, 163, 77, 0.3);
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d4941f, var(--accent-yellow));
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

        .btn-sm {
            padding: 8px 12px;
            font-size: 0.8rem;
            border-radius: 10px;
        }

        /* Grupo de botones en acciones */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
            flex-wrap: nowrap;
        }

        .action-buttons .btn {
            flex-shrink: 0;
            min-width: 45px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Paginación mejorada */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 20px;
            list-style: none;
            margin: 0;
            gap: 5px;
        }

        .pagination li {
            margin: 0;
        }

        .pagination li a,
        .pagination li span {
            text-decoration: none;
            padding: 12px 18px;
            background: linear-gradient(135deg, var(--secondary-orange), var(--primary-orange));
            color: white;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border: 2px solid transparent;
            min-width: 48px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(227, 139, 91, 0.3);
        }

        .pagination li a:hover {
            background: linear-gradient(135deg, var(--primary-orange), var(--secondary-orange));
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 8px 25px rgba(227, 139, 91, 0.4);
        }

        .pagination .active span {
            background: linear-gradient(135deg, var(--dark-brown), #4a3a2a);
            border: 2px solid var(--secondary-orange);
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(99, 77, 59, 0.4);
        }

        .pagination .disabled span {
            background: linear-gradient(135deg, #ccc, #aaa);
            color: #666;
            cursor: not-allowed;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* ===== RESPONSIVE MEJORADO ===== */
        @media (max-width: 1200px) {
            .container {
                margin: 20px;
                padding: 30px;
            }

            .table {
                min-width: 1600px;
            }
        }

        @media (max-width: 768px) {
            .container {
                margin: 15px;
                padding: 20px;
                border-radius: 20px;
            }

            h2 {
                font-size: 2rem;
            }

            .search-container {
                padding: 20px;
            }

            .search-input {
                padding: 15px 20px;
                font-size: 14px;
            }

            .btn-outline-light {
                padding: 15px 20px;
            }

            .table {
                min-width: 1400px;
            }

            .table th,
            .table td {
                padding: 8px 6px;
                font-size: 0.8rem;
                min-width: 100px;
            }

            .table th.col-acciones,
            .table td.col-acciones {
                min-width: 120px;
            }

            .action-buttons {
                gap: 4px;
            }

            .action-buttons .btn {
                min-width: 35px;
                height: 32px;
                font-size: 0.7rem;
            }

            .column-controls {
                padding: 15px;
            }

            .column-toggle {
                margin: 3px;
                padding: 6px 10px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .table {
                min-width: 1200px;
            }

            .table th,
            .table td {
                padding: 6px 4px;
                font-size: 0.75rem;
                min-width: 80px;
            }

            .action-buttons .btn {
                min-width: 30px;
                height: 28px;
                padding: 4px;
            }
        }

        /* Loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid var(--primary-orange);
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Success/Error states */
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            animation: slideInUp 0.5s ease-out;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05));
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05));
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Indicador de scroll */
        .scroll-indicator {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            background: var(--primary-orange);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            opacity: 0.8;
            pointer-events: none;
            z-index: 100;
        }

        .scroll-indicator.hidden {
            display: none;
        }
    </style>


</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <img src="{{ asset('img/FontTrack.png') }}" alt="logo" width="100" height="70" class="logo me-2"
                onclick="location.href='{{ route('reportes.index') }}'">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('materials') }}">
                            <i class="bi bi-box-seam me-2"></i>Materiales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('lugares.index') }}">
                            <i class="bi bi-geo-alt me-2"></i>Lugares
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users') }}">
                            <i class="bi bi-people me-2"></i>Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('vehiculos.index') }}">
                            <i class="bi bi-truck me-2"></i>Vehículos
                        </a>
                    </li>
                </ul>
                <div class="user-profile" onclick="$('.user-dropdown').toggleClass('show')">
                    <img src="{{ Auth::user()->foto_usuario_url ?? asset('img/usuario_default.png') }}" alt="Perfil">
                    <span class="user-name">{{ Auth::user()->nombre }}</span>
                    <i class="bi bi-chevron-down ms-2"></i>
                    <div class="user-dropdown">
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    @if(Auth::user()->id_lugar || Auth::user()->tipo_usuario == 1)
    <div class="container mt-3">
        <h2><i class="bi bi-exclamation-triangle me-3"></i>Reportes de Fallas - {{ Auth::user()->lugar->nombre ?? 'Todos los lugares' }}</h2>

        <!-- Buscador -->
        <div class="search-container">
            <div class="row align-items-center">
                <div class="col-md-10">
                    <input type="text" id="searchFallas" class="search-input w-100"
                        placeholder="🔍 Buscar por lugar, conductor, descripción, ECO, placas, marca...">
                </div>
                <div class="col-md-2 text-center">
                    <button class="btn btn-outline-light w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Control de Columnas -->
        <div class="column-controls">
            <h6 class="mb-3"><i class="bi bi-toggles me-2"></i>Mostrar/Ocultar Columnas:</h6>
            <div class="d-flex flex-wrap">
                <button class="btn btn-sm column-toggle active" data-column="lugar">🏢 Lugar</button>
                <button class="btn btn-sm column-toggle active" data-column="fecha">📅 Fecha</button>
                <button class="btn btn-sm column-toggle active" data-column="conductor">👤 Conductor</button>
                <button class="btn btn-sm column-toggle active" data-column="eco">🚛 ECO</button>
                <button class="btn btn-sm column-toggle active" data-column="placas">🔢 Placas</button>
                <button class="btn btn-sm column-toggle active" data-column="marca">🏭 Marca</button>
                <button class="btn btn-sm column-toggle active" data-column="anio">📆 Año</button>
                <button class="btn btn-sm column-toggle active" data-column="km">🛣️ KM</button>
                <button class="btn btn-sm column-toggle active" data-column="descripcion">📝 Descripción</button>
                <button class="btn btn-sm column-toggle active" data-column="observaciones">📋 Observaciones</button>
                <button class="btn btn-sm column-toggle active" data-column="material">📦 Material</button>
                <button class="btn btn-sm column-toggle active" data-column="cantidad">🔢 Cant.</button>
                <button class="btn btn-sm column-toggle active" data-column="autorizado">✅ Autorizado por</button>
                <button class="btn btn-sm column-toggle active" data-column="reviso">👥 Revisó</button>
                <button class="btn btn-sm column-toggle active" data-column="reporta">📤 Reporta</button>
                <button class="btn btn-sm column-toggle active" data-column="correo">📧 Correo</button>
            </div>
        </div>

        <!-- Tabla de Fallas Completa con Scroll -->
        <div class="table-container">
            <div class="scroll-indicator" id="scrollIndicator">
                <i class="bi bi-arrows-move"></i> Desplázate para ver más columnas
            </div>
            <div class="table-wrapper" id="tableWrapper">
                <table class="table table-hover" id="tablaFallas">
                    <thead>
                        <tr>
                            <th class="col-lugar"><i class="bi bi-geo-alt me-1"></i>Lugar</th>
                            <th class="col-fecha"><i class="bi bi-calendar me-1"></i>Fecha</th>
                            <th class="col-conductor"><i class="bi bi-person me-1"></i>Conductor</th>
                            <th class="col-eco"><i class="bi bi-truck me-1"></i>ECO</th>
                            <th class="col-placas"><i class="bi bi-123 me-1"></i>Placas</th>
                            <th class="col-marca"><i class="bi bi-building me-1"></i>Marca</th>
                            <th class="col-anio"><i class="bi bi-calendar-check me-1"></i>Año</th>
                            <th class="col-km"><i class="bi bi-speedometer me-1"></i>KM</th>
                            <th class="col-descripcion"><i class="bi bi-chat-text me-1"></i>Descripción</th>
                            <th class="col-observaciones"><i class="bi bi-clipboard me-1"></i>Observaciones</th>
                            <th class="col-material"><i class="bi bi-box me-1"></i>Material</th>
                            <th class="col-cantidad"><i class="bi bi-hash me-1"></i>Cant.</th>
                            <th class="col-autorizado"><i class="bi bi-check-circle me-1"></i>Autorizado por</th>
                            <th class="col-reviso"><i class="bi bi-eye me-1"></i>Revisó</th>
                            <th class="col-reporta"><i class="bi bi-person-up me-1"></i>Reporta</th>
                            <th class="col-correo"><i class="bi bi-envelope me-1"></i>Correo</th>
                            <th class="col-acciones"><i class="bi bi-gear me-1"></i>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="bodyFallas">
                        @foreach($fallas->where('id_lugar', Auth::user()->id_lugar) as $falla)
                            <tr data-id="{{ $falla->id }}">
                                <td class="col-lugar">
                                    <div class="expandable-cell">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        <span>{{ Str::limit(optional($falla->lugar)->nombre ?: 'N/A', 15) }}</span>
                                        @if(strlen(optional($falla->lugar)->nombre ?: '') > 15)
                                            <div class="full-content">{{ optional($falla->lugar)->nombre }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-fecha">
                                    <i class="bi bi-calendar-event me-1 text-primary"></i>
                                    {{ $falla->fecha ?: 'N/A' }}
                                </td>
                                <td class="col-conductor">
                                    <div class="expandable-cell">
                                        <i class="bi bi-person-circle me-1 text-success"></i>
                                        <span>{{ Str::limit($falla->nombre_conductor ?: 'N/A', 15) }}</span>
                                        @if(strlen($falla->nombre_conductor ?: '') > 15)
                                            <div class="full-content">{{ $falla->nombre_conductor }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-eco">
                                    <span class="badge badge-eco">
                                        {{ $falla->eco ?: 'N/A' }}
                                    </span>
                                </td>
                                <td class="col-placas">
                                    <span class="badge badge-placas">
                                        {{ $falla->placas ?: 'N/A' }}
                                    </span>
                                </td>
                                <td class="col-marca">{{ $falla->marca ?: 'N/A' }}</td>
                                <td class="col-anio">{{ $falla->anio ?: 'N/A' }}</td>
                                <td class="col-km">{{ $falla->km ?: 'N/A' }}</td>
                                <td class="col-descripcion">
                                    <div class="expandable-cell">
                                        <span>{{ Str::limit($falla->descripcion ?: 'N/A', 20) }}</span>
                                        @if(strlen($falla->descripcion ?: '') > 20)
                                            <div class="full-content">{{ $falla->descripcion }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-observaciones">
                                    <div class="expandable-cell">
                                        <span>{{ Str::limit($falla->observaciones ?: 'N/A', 20) }}</span>
                                        @if(strlen($falla->observaciones ?: '') > 20)
                                            <div class="full-content">{{ $falla->observaciones }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-material">
                                    <div class="expandable-cell">
                                        <i class="bi bi-box-seam me-1 text-warning"></i>
                                        <span>{{ Str::limit($falla->material ?: 'N/A', 20) }}</span>
                                        @if(strlen($falla->material ?: '') > 20)
                                            <div class="full-content">{{ $falla->material }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-cantidad">
                                    <span class="badge badge-cantidad">{{ $falla->cantidad ?: '0' }}</span>
                                </td>
                                <td class="col-autorizado">
                                    <div class="expandable-cell">
                                        <span>{{ Str::limit($falla->autorizado_por ?: 'N/A', 15) }}</span>
                                        @if(strlen($falla->autorizado_por ?: '') > 15)
                                            <div class="full-content">{{ $falla->autorizado_por }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-reviso">
                                    <div class="expandable-cell">
                                        <span>{{ Str::limit($falla->reviso_por ?: 'N/A', 15) }}</span>
                                        @if(strlen($falla->reviso_por ?: '') > 15)
                                            <div class="full-content">{{ $falla->reviso_por }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-reporta">
                                    <div class="expandable-cell">
                                        <span>{{ Str::limit($falla->nombre_usuario_reporta ?: 'N/A', 15) }}</span>
                                        @if(strlen($falla->nombre_usuario_reporta ?: '') > 15)
                                            <div class="full-content">{{ $falla->nombre_usuario_reporta }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-correo">
                                    <div class="expandable-cell">
                                        <span>{{ Str::limit($falla->correo_usuario_reporta ?: 'N/A', 20) }}</span>
                                        @if(strlen($falla->correo_usuario_reporta ?: '') > 20)
                                            <div class="full-content">{{ $falla->correo_usuario_reporta }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-acciones">
                                    <div class="action-buttons">
                                        <a href="{{ route('fallas.pdf', $falla->id) }}" class="btn btn-info btn-sm"
                                            title="Generar PDF">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </a>
                                        <button class="btn btn-warning btn-sm btnEnviar" data-id="{{ $falla->id }}"
                                            title="Enviar por correo">
                                            <i class="bi bi-envelope-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if($fallas->where('id_lugar', Auth::user()->id_lugar)->count() == 0)
                            <tr>
                                <td colspan="17" class="text-center">No hay reportes de fallas registrados para tu lugar.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <nav>
            <ul class="pagination" id="fallaPagination"></ul>
        </nav>
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

    <!-- Modal: Enviar Correo -->
    <div class="modal fade" id="modalEnviar" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formEnviar" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-envelope-plus me-2"></i>Enviar Reporte por Correo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <input type="hidden" id="fallaId">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-envelope me-2"></i>Correo de Destino
                        </label>
                        <input type="email" id="correoDestino" name="correo_destino"
                            class="form-control form-control-lg" placeholder="usuario@dominio.com" required>
                    </div>
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        El reporte será enviado como archivo PDF adjunto al correo especificado.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-2"></i>Enviar Reporte
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(function () {
            // Inicializar filas
            let all = $('#bodyFallas tr').toArray().map(r => ({ html: r.outerHTML, id: $(r).data('id') })),
                filtered = [...all], current = 1, perPage = 10;

            // Control del indicador de scroll
            const $tableWrapper = $('#tableWrapper');
            const $scrollIndicator = $('#scrollIndicator');

            function checkScrollIndicator() {
                const wrapper = $tableWrapper[0];
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

                // Efecto de transición
                $('#bodyFallas').addClass('loading');

                setTimeout(() => {
                    $('#bodyFallas').html(filtered.slice(start, end).map(u => u.html).join(''));
                    $('#bodyFallas').removeClass('loading');
                    renderPagination();
                    attachListeners();
                    applyColumnVisibility();
                    checkScrollIndicator();
                }, 150);
            }

            function renderPagination() {
                let total = Math.ceil(filtered.length / perPage), nav = '';

                // Botón anterior
                nav += `<li class="page-item ${current === 1 ? 'disabled' : ''}">
                    <a class="page-link" data-page="${current - 1}" title="Página anterior">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>`;

                // Números de página
                for (let i = 1; i <= total; i++) {
                    if (i === 1 || i === total || (i >= current - 2 && i <= current + 2)) {
                        nav += `<li class="page-item ${i === current ? 'active' : ''}">
                            <a class="page-link" data-page="${i}" title="Página ${i}">${i}</a>
                        </li>`;
                    } else if (i === current - 3 || i === current + 3) {
                        nav += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                }

                // Botón siguiente
                nav += `<li class="page-item ${current === total || total === 0 ? 'disabled' : ''}">
                    <a class="page-link" data-page="${current + 1}" title="Página siguiente">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>`;

                $('#fallaPagination').html(nav);
            }

            // Control de columnas
            function applyColumnVisibility() {
                $('.column-toggle').each(function () {
                    const column = $(this).data('column');
                    const isActive = $(this).hasClass('active');

                    if (isActive) {
                        $(`.col-${column}`).removeClass('hidden-col');
                    } else {
                        $(`.col-${column}`).addClass('hidden-col');
                    }
                });
                checkScrollIndicator();
            }

            // Toggle de columnas
            $('.column-toggle').on('click', function () {
                $(this).toggleClass('active');
                applyColumnVisibility();
            });

            // Búsqueda mejorada con más campos
            let searchTimeout;
            $('#searchFallas').on('input', function () {
                clearTimeout(searchTimeout);
                const searchInput = $(this);

                searchTimeout = setTimeout(() => {
                    let q = searchInput.val().toLowerCase().trim();

                    if (q) {
                        filtered = all.filter(u => {
                            const html = u.html.toLowerCase();
                            return html.includes(q);
                        });
                        searchInput.removeClass('is-invalid').addClass('is-valid');
                    } else {
                        filtered = [...all];
                        searchInput.removeClass('is-valid is-invalid');
                    }

                    showPage(1);
                }, 300);
            });

            // Paginación
            $(document).on('click', '.page-link', function (e) {
                e.preventDefault();
                let p = +$(this).data('page');
                if (p > 0 && p <= Math.ceil(filtered.length / perPage)) {
                    showPage(p);
                }
            });

            // Modal enviar correo mejorado
            let modal = new bootstrap.Modal($('#modalEnviar'));

            function attachListeners() {
                $('.btnEnviar').on('click', function () {
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

            // Envío de formulario mejorado
            $('#formEnviar').on('submit', function (e) {
                e.preventDefault();

                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();

                // Estado de carga
                submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-2"></i>Enviando...');

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
                        // Mostrar mensaje de éxito
                        $('body').prepend(`
                        <div class="alert alert-success alert-dismissible fade show position-fixed" 
                             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong>¡Éxito!</strong> ${json.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);

                        modal.hide();

                        // Auto-remover alerta después de 5 segundos
                        setTimeout(() => {
                            $('.alert-success').fadeOut();
                        }, 5000);
                    })
                    .fail(function () {
                        // Mostrar mensaje de error
                        $('body').prepend(`
                        <div class="alert alert-error alert-dismissible fade show position-fixed" 
                             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Error:</strong> No se pudo enviar el correo. Inténtalo de nuevo.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);

                        setTimeout(() => {
                            $('.alert-error').fadeOut();
                        }, 5000);
                    })
                    .always(function () {
                        // Restaurar botón
                        submitBtn.prop('disabled', false).html(originalText);
                    });
            });

            // Cerrar dropdown al hacer clic fuera
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.user-profile').length) {
                    $('.user-dropdown').removeClass('show');
                }
            });

            // Redimensionar ventana
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
</body>

</html>