<!DOCTYPE html>
<html lang="es">
<head>
    @include('layouts.partials.head')
</head>
<body class="@yield('body-class')">
    @yield('content')

    @stack('modals')

    @include('layouts.partials.vendor-scripts')
    @stack('scripts')
</body>
</html>
