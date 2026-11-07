<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#FBF8F5">
<title>@hasSection('title')@yield('title') · FontTrack @else FontTrack @endif</title>
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/brand/favicon-32.png') }}">
<link rel="apple-touch-icon" href="{{ asset('img/brand/apple-touch-icon.png') }}">
@vite(['resources/scss/app.scss', 'resources/js/app.js'])
@stack('styles')
