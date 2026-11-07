@php
    $usuario = Auth::user();
    $esAdmin = optional($usuario)->tipo_usuario == 1;

    // Navegación única. Cada enlace apunta a una ruta que ya existe y se
    // marca como activo con request()->routeIs().
    $secciones = $esAdmin
        ? [
            'General' => [
                ['ruta' => 'dashboard', 'activo' => ['dashboard'], 'icono' => 'bi-grid-1x2', 'texto' => 'Panel'],
            ],
            'Inventario' => [
                ['ruta' => 'materials', 'activo' => ['materials', 'materials.*'], 'icono' => 'bi-box-seam', 'texto' => 'Materiales'],
                ['ruta' => 'vehiculos.index', 'activo' => ['vehiculos.*'], 'icono' => 'bi-truck', 'texto' => 'Vehículos'],
            ],
            'Operación' => [
                ['ruta' => 'reportes.index', 'activo' => ['reportes.*', 'fallas.*'], 'icono' => 'bi-clipboard2-pulse', 'texto' => 'Reportes de fallas'],
            ],
            'Administración' => [
                ['ruta' => 'users', 'activo' => ['users', 'user_detail', 'edit_user'], 'icono' => 'bi-people', 'texto' => 'Usuarios'],
                ['ruta' => 'lugares.index', 'activo' => ['lugares.*'], 'icono' => 'bi-geo-alt', 'texto' => 'Lugares'],
            ],
        ]
        : [
            'Inventario' => [
                ['ruta' => 'materiales.index', 'activo' => ['materiales.*'], 'icono' => 'bi-box-seam', 'texto' => 'Materiales'],
                ['ruta' => 'vehiculos.index', 'activo' => ['vehiculos.*'], 'icono' => 'bi-truck', 'texto' => 'Vehículos'],
            ],
            'Operación' => [
                ['ruta' => 'reportes.index', 'activo' => ['reportes.*', 'fallas.*'], 'icono' => 'bi-clipboard2-pulse', 'texto' => 'Reportes de fallas'],
            ],
        ];

    $inicio = $esAdmin ? route('dashboard') : route('materiales.index');
@endphp

<aside class="ft-sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="ftSidebar" aria-labelledby="ftSidebarLabel">
    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ $inicio }}" class="ft-brand flex-grow-1" id="ftSidebarLabel">
            <img src="{{ asset('img/brand/fonttrack-mark-96.webp') }}" alt="" width="36" height="36">
            <span>FontTrack <small>Inventario y fallas</small></span>
        </a>
        <button type="button" class="btn-close d-lg-none me-3" data-bs-dismiss="offcanvas" data-bs-target="#ftSidebar" aria-label="Cerrar menú"></button>
    </div>

    <div class="offcanvas-body">
        <nav class="ft-nav" aria-label="Navegación principal">
            @foreach($secciones as $titulo => $enlaces)
                <div class="ft-nav__section">{{ $titulo }}</div>
                @foreach($enlaces as $enlace)
                    @php $activo = request()->routeIs(...$enlace['activo']); @endphp
                    <a href="{{ route($enlace['ruta']) }}"
                       class="ft-nav__link {{ $activo ? 'is-active' : '' }}"
                       @if($activo) aria-current="page" @endif>
                        <i class="bi {{ $enlace['icono'] }}" aria-hidden="true"></i>
                        <span>{{ $enlace['texto'] }}</span>
                    </a>
                @endforeach
            @endforeach
        </nav>

        @if($usuario)
            <div class="ft-sidebar__footer">
                <i class="bi bi-geo-alt me-1" aria-hidden="true"></i>
                {{ optional($usuario->lugar)->nombre ?? 'Sin lugar asignado' }}
            </div>
        @endif
    </div>
</aside>
