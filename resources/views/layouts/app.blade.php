<!DOCTYPE html>
<html lang="es">
<head>
    @include('layouts.partials.head')
</head>
<body class="@yield('body-class')">
    <a href="#main" class="ft-skip-link">Saltar al contenido</a>

    @stack('before-shell')

    <div class="ft-shell ft-shell--with-sidebar">
        @include('layouts.partials.sidebar')

        <div class="ft-main">
            @include('layouts.partials.topbar')

            <main id="main" class="ft-content" tabindex="-1">
                <div class="ft-container">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('modals')

    @include('layouts.partials.vendor-scripts')
    @stack('scripts')
</body>
</html>
