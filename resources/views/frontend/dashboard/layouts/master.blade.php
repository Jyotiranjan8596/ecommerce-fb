<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('devstarit.app_name') }} - {{ config('devstarit.app_desc') }}</title>
    <!-- Primary Meta Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="{{ config('devstarit.app_name') }} - {{ config('devstarit.app_desc') }}">
    <meta name="author" content="FreeBazar">
    <meta name="description" content="{{ config('devstarit.app_desc') }}">
    <meta name="keywords" content="FreeBazar, Ecommerce, Store" />
    <link rel="canonical" href="">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:title" content="{{ config('devstarit.app_name') }}">
    <meta property="og:description" content="{{ config('devstarit.app_desc') }}">
    <meta property="og:image" content="">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="">
    <meta property="twitter:title" content="Volt - Free Bootstrap 5 Dashboard">
    <meta property="twitter:description"
        content="Volt Pro is a Premium Bootstrap 5 Admin Dashboard featuring over 800 components, 10+ plugins and 20 example pages using Vanilla JS.">
    <meta property="twitter:image"
        content="https://themesberg.s3.us-east-2.amazonaws.com/public/products/volt-pro-bootstrap-5-dashboard/volt-pro-preview.jpg">

    <!-- Favicon -->
    <link rel="apple-touch-icon" type="image/png" sizes="120x120"
        href="{{ asset('assets/images/freebazarfavicon.png') }}?v=3" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/freebazarfavicon.png') }}?v=3" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/freebazarfavicon.png') }}?v=3" />
    <link rel="icon" href="{{ asset('assets/images/freebazarfavicon.png') }}?v=3" />
    <link rel="mask-icon" href="{{ asset('assets/images/freebazarfavicon.png') }}?v=3" color="#ffffff" />
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="theme-color" content="#ffffff" />

    <!-- Sweet Alert -->
    <link type="text/css" href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">

    <!-- Notyf -->
    <link type="text/css" href="{{ asset('vendor/notyf/notyf.min.css') }}" rel="stylesheet">

    <!-- Volt CSS -->
    <link type="text/css" href="{{ asset('css/volt.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('css/enterprise-theme.css') }}" rel="stylesheet">
    <script src="{{ asset('js/scroll-list.js') }}"></script>

    {{-- Bottom bar Css  --}}
    {{-- <link type="text/css" href="{{ asset('css/bottombar.css') }}" rel="stylesheet"> --}}


</head>

<body class="has-fixed-sidebar">

    <!-- mobile menu  -->
    <nav class="navbar navbar-light  px-4 col-12 d-lg-none" style="border-bottom: 5px solid #fff;">
        <a class="navbar-brand me-lg-5" href="{{ route('frontend.index') }}">
            <img class="navbar-brand-dark" src="{{ asset('assets/images/freebazar-header-logo.png') }}" alt="FreeBazar — Your Spending Today. Your Savings Tomorrow. Your Legacy for Generations." style="width:160px;max-width:100%;height:auto;" />
            <img class="navbar-brand-light" src="{{ asset('assets/images/freebazar-header-logo.png') }}" alt="FreeBazar — Your Spending Today. Your Savings Tomorrow. Your Legacy for Generations." style="width:160px;max-width:100%;height:auto;" />
        </a>
        <div class="d-flex align-items-center">
            <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>


    <!-- sidebar  -->
    @include('frontend.dashboard.includes.sidebar')


    {{-- Main Content  --}}
    <main class="content">

        @include('frontend.dashboard.includes.navbar')
        <br>
        @yield('content')
    </main>
    {{-- Main Content end  --}}

    @include('frontend.dashboard.includes.scanmodal')
    @include('ui.layout.footer')

    <!-- Scripts -->
    @include('frontend.dashboard.includes.js')

    <!-- Core -->
    <script src="{{ asset('vendor/@popperjs/core/dist/umd/popper.min.js') }}"></script>

    <!-- Vendor JS -->
    <script src="{{ asset('vendor/onscreen/dist/on-screen.umd.min.js') }}"></script>

    <!-- Slider -->
    <script src="{{ asset('vendor/nouislider/distribute/nouislider.min.js') }}"></script>

    <!-- Smooth scroll -->
    <script src="{{ asset('vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js') }}"></script>

    <!-- Charts -->
    <script src="{{ asset('vendor/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('vendor/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>

    <!-- Datepicker -->
    <script src="{{ asset('vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>

    <!-- Sweet Alerts 2 -->
    <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

    <!-- Moment JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>

    <!-- Vanilla JS Datepicker -->
    <script src="{{ asset('vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>

    <!-- Notyf -->
    <script src="{{ asset('vendor/notyf/notyf.min.js') }}"></script>

    <!-- Simplebar -->
    <script src="{{ asset('vendor/simplebar/dist/simplebar.min.js') }}"></script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Volt JS -->
    <script src="{{ asset('assets/js/volt.js') }}"></script>
    <script src="{{ asset('assets/js/notifications.js') }}"></script>

</body>

</html>
