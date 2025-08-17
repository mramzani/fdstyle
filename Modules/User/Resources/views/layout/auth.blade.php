<!DOCTYPE html>
<html lang="fa" class="light-style layout-navbar-fixed layout-menu-fixed" dir="rtl" data-theme="theme-default"
      data-assets-path="/assets/panel/" data-template="vertical-menu-template">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <title>@yield('authTitle')</title>

    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/panel/img/favicon/favicon.ico') }}">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/fonts/boxicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/fonts/flag-icons.css') }}">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/css/rtl/core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/css/rtl/theme-default.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/css/demo.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/css/rtl/rtl.css') }}">

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/typeahead-js/typeahead.css') }}">
    <!-- Vendor -->
    <link rel="stylesheet"
          href="{{ asset('assets/panel/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}">

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/css/pages/page-auth.css') }}">
    <!-- Helpers -->
    <script src="{{ asset('assets/panel/vendor/js/helpers.js') }}"></script>


    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/panel/js/config.js') }}"></script>
</head>

<body>
<!-- Content -->

<div class="authentication-wrapper authentication-cover">
    <div class="authentication-inner row m-0">
        <!-- /Left Text -->
        <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center">
            <div class="flex-row text-center mx-auto">
                <a href="https://rinofy.ir/?ref=dashboard_fdstyle" target="_blank">
                    <img src="https://rinofy.ir/wp-content/uploads/2024/02/rinofy_logo_website.png" alt="Auth Cover Bg color" width="520"
                     class="img-fluid authentication-cover-img" data-app-light-img="pages/login-light.png"
                     data-app-dark-img="pages/login-dark.png">
                </a>
                <div class="mx-auto">
                    <h4>طراحی و توسعه: <strong>نوآوران فرداد</strong></h4>
                    <p>
                        تمامی حقوق ماده و معنوی این وبسایت متعلق به <strong>{{ company()->site_title }}</strong> می‌باشد.
                    </p>
                </div>
            </div>
        </div>
        <!-- /Left Text -->

        <!-- Login -->
        @yield('content')
        <!-- /Login -->
    </div>
</div>

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
<script src="{{ asset('assets/panel/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
<script src="{{ asset('assets/panel/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/panel/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('assets/panel/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('assets/panel/js/pages-auth.js') }}"></script>
</body>
</html>
