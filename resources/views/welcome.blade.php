@extends('layouts.guest')

@section('title', 'Bienvenido')

@section('content')
    <!-- Círculo de transición hacia el inicio de sesión -->
    <div class="transition-circle" id="transitionCircle" aria-hidden="true"></div>

    <div class="ft-welcome">
        <header class="ft-welcome__bar">
            <span class="ft-auth-card__brand d-flex mb-0">
                <img src="{{ asset('img/brand/fonttrack-mark-192.webp') }}" alt="" width="40" height="40">
                FontTrack
            </span>
            <a href="{{ url('login') }}" class="btn btn-outline-secondary">Iniciar sesión</a>
        </header>

        <main class="ft-welcome__hero">
            <div>
                <p class="ft-page-header__eyebrow mb-3"><i class="bi bi-droplet-half" aria-hidden="true"></i> Gestión de inventario inspirada en la frescura de Bonafont</p>
                <h1>Optimiza tu inventario <span>con facilidad.</span></h1>
                <p class="ft-welcome__lead">FontTrack te ayuda a administrar tu stock de manera inteligente, eficiente y sencilla.</p>

                <a href="{{ url('login') }}" id="loginBtn" class="btn btn-primary btn-lg px-4">
                    Ingresar <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                </a>

                <ul class="ft-welcome__features">
                    <li><i class="bi bi-box-seam" aria-hidden="true"></i><strong>Materiales</strong>Existencias por lugar, Kardex y exportación a Excel.</li>
                    <li><i class="bi bi-truck" aria-hidden="true"></i><strong>Vehículos</strong>Flota con kilometraje, conductor y estado.</li>
                    <li><i class="bi bi-clipboard2-pulse" aria-hidden="true"></i><strong>Reportes</strong>Fallas en PDF con envío por correo.</li>
                </ul>
            </div>

            <div class="ft-welcome__visual">
                <img src="{{ asset('img/brand/bonafont-x-fonttrack.webp') }}" alt="Bonafont by FontTrack" width="555" height="174">
            </div>
        </main>

        <footer class="ft-welcome__footer">
            &copy; {{ date('Y') }} FontTrack · Todos los derechos reservados
        </footer>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginBtn = document.getElementById('loginBtn');
            const circle = document.getElementById('transitionCircle');
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            loginBtn.addEventListener('click', function (e) {
                if (reduceMotion || e.metaKey || e.ctrlKey || e.shiftKey) return;
                e.preventDefault();

                // Posicionar el círculo en el centro del botón y expandirlo
                const btnRect = this.getBoundingClientRect();
                circle.style.left = (btnRect.left + btnRect.width / 2 - 50) + 'px';
                circle.style.top = (btnRect.top + btnRect.height / 2 - 50) + 'px';
                circle.classList.add('expanding');

                setTimeout(function () {
                    window.location.href = loginBtn.href;
                }, 400);
            });
        });
    </script>
@endpush
