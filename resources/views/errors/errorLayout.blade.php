<!DOCTYPE html>
<html lang="fa" class="light-style" dir="rtl" data-theme="theme-default" data-assets-path="/assets/panel/"
      data-template="vertical-menu-template">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <title>@yield('title')</title>

    <meta name="description" content="">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/panel/img/favicon/favicon.ico') }}">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/fonts/boxicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/fonts/flag-icons.css') }}">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/css/rtl/core.css') }}" class="template-customizer-core-css">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/css/rtl/rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/css/demo.css') }}">

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/typeahead-js/typeahead.css') }}">

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/css/pages/page-misc.css') }}">
    <!-- Helpers -->
    <script src="{{ asset('assets/panel/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('assets/panel/vendor/js/template-customizer.js') }}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/panel/js/config.js') }}"></script>
</head>

<body>
<!-- Content -->

<!-- Not Authorized -->
<div class="container-xxl container-p-y">
    <div class="misc-wrapper">
        <h1 class="mb-2 mx-2 secondary-font">@yield('message')</h1>
        <p class="mb-4 mx-2">@yield('description')</p>
        <a href="#" class="btn btn-primary">@yield('btnText')</a>
        @if(\Auth::guard('admin')->check())
            <a href="{{ route('dashboard.index') }}" class="btn btn-lg btn-outline-secondary my-2">داشبورد مدیریت</a>
        @endif
        <div class="mt-5">
            <img src="@yield('imgSrc')"
                 alt="page-misc-error-light" width="450" class="img-fluid"
                 data-app-light-img=@yield('lightImg')"
                 data-app-dark-img="@yield('darkImg')">
        </div>
    </div>
    @yield('content')
</div>

<!-- /Not Authorized -->

<!-- / Content -->

<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="{{ asset('assets/panel/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/panel/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/panel/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/panel/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('assets/panel/vendor/libs/hammer/hammer.js') }}"></script>

<script src="{{ asset('assets/panel/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('assets/panel/vendor/libs/typeahead-js/typeahead.js') }}"></script>

<script src="{{ asset('assets/panel/vendor/js/menu.js') }}"></script>
<!-- endbuild -->

<!-- Vendors JS -->

<!-- Main JS -->
<script src="{{ asset('assets/panel/js/main.js') }}"></script>

<!-- Page JS -->
</body>
</html>
