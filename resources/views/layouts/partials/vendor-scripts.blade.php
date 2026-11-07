{{--
    Librerías clásicas auto-hospedadas (copiadas a public/vendor por `npm run build`).
    Van al final del <body> y antes de los scripts de cada vista, que las usan
    de forma síncrona ($, bootstrap, Swal). Una sola versión de cada una.
--}}
<script src="{{ asset('vendor/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/sweetalert2.all.min.js') }}"></script>
