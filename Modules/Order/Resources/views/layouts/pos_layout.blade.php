<!DOCTYPE html>
<html lang="fa" class="light-style layout-navbar-fixed layout-menu-fixed" dir="rtl" data-theme="theme-default" data-assets-path="/assets/panel/" data-template="vertical-menu-template">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <title>@yield('dashboardTitle')</title>

    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/panel/img/favicon/favicon.ico') }}">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/fonts/boxicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/fonts/flag-icons.css') }}">

    <!-- Core CSS -->
    <link rel="stylesheet"  href="{{ asset('assets/panel/vendor/css/rtl/core.css') }}" class="template-customizer-core-css">
    <link rel="stylesheet"   href="{{ asset('assets/panel/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/css/rtl/rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/css/demo.css') }}">
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" href="{{ mix('assets/panel/css/app.css') }}">
    <!-- Custom CSS -->
    @yield('styles')

    <!-- Helpers -->
    <script src="{{ mix('js/head_app.js') }}"></script>

    <script src="{{ asset('assets/panel/js/config.js') }}"></script>
    <script defer src="{{ asset('assets/cdn/alpinejs@3.10.5.min.js') }}"></script>
    @livewireStyles
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar layout-without-menu">
    <div class="layout-container">

        <!-- Layout container -->
        <div class="layout-page">
            <!-- Navbar -->

            @include('dashboard::partials.navbar')

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                @yield('content')
                <!-- / Content -->

                <!-- Footer -->
                @include('dashboard::partials.footer')
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>
    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
</div>
<!-- / Layout wrapper -->

<!-- Core JS -->
<!-- build:js assets/panel/vendor/js/core.js -->
<script src="{{ mix('js/app.js') }}"></script>
<!-- endbuild -->
<!-- Main JS -->
<script src="{{ asset('assets/panel/js/main.js') }}"></script>

<script type="text/javascript">
    function blockUi(time = 5000) {
        $(".content-wrapper").block({
            message:
                '<div class="d-flex justify-content-center">' +
                '<div class="spinner-border text-primary" role="status"></div>' +
                '<p class="mb-0 mx-2">لطفا صبر کنید ...</p>' +
                '</div>',
            timeout: time,
            css: {
                backgroundColor: 'transparent',
                color: '#fff',
                border: '0'
            },
            overlayCSS: {
                opacity: 0.5
            }
        });
    }
</script>

@livewireScripts
@yield('script')
</body>
</html>
