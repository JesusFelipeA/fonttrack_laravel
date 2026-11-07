@php $usuario = Auth::user(); @endphp

<header class="ft-topbar">
    <button type="button" class="btn btn-ghost btn-icon d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#ftSidebar" aria-controls="ftSidebar" aria-label="Abrir menú">
        <i class="bi bi-list fs-4" aria-hidden="true"></i>
    </button>

    <a href="{{ optional($usuario)->tipo_usuario == 1 ? route('dashboard') : route('materiales.index') }}" class="ft-topbar__brand d-lg-none">
        <img src="{{ asset('img/brand/fonttrack-mark-96.webp') }}" alt="" width="32" height="32">
        <span class="d-none d-sm-inline">FontTrack</span>
    </a>

    <div class="ft-topbar__context d-none d-md-block">
        @hasSection('breadcrumb')
            @yield('breadcrumb')
        @else
            <span>{{ optional(optional($usuario)->lugar)->nombre ?? 'Todos los lugares' }}</span>
            @hasSection('title') <span aria-hidden="true">/</span> <strong>@yield('title')</strong> @endif
        @endif
    </div>

    <div class="ft-topbar__actions">
        @stack('topbar-actions')

        @if($usuario)
            <div class="dropdown">
                <button class="ft-user" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menú de {{ $usuario->nombre }}">
                    <img src="{{ $usuario->foto_usuario_url ?? asset('img/usuario_default.png') }}" alt="" width="32" height="32" class="user-avatar-new">
                    <span class="d-none d-sm-inline text-truncate" style="max-width: 10rem">{{ $usuario->nombre }}</span>
                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end ft-user-menu">
                    <li class="dropdown-header">
                        <strong>{{ $usuario->nombre }}</strong>
                        <small>{{ $usuario->correo }}</small>
                        <small>{{ $usuario->tipo_usuario == 1 ? 'Administrador' : 'Usuario' }}</small>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    @stack('user-menu')
                    <li>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item dropdown-item--danger">
                                <i class="bi bi-box-arrow-right" aria-hidden="true"></i> Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @endif
    </div>
</header>
