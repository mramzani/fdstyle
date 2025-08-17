<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود</title>
    <!-- font-awesome -->
    <link rel="stylesheet" href="{{ asset('assets/front/css/font-awesome.min.css') }}">
    <!-- Bootstrap 4.5.3 -->
    <link rel="stylesheet" href="{{ asset('assets/front/bootstrap/css/bootstrap.min.css') }}">
    <!-- CSS Implementing Plugins -->
    {{--<link rel="stylesheet" href="{{ asset('assets/front/css/plugins/apexcharts.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('assets/front/css/plugins/jquery.classycountdown.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/plugins/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/plugins/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/plugins/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/plugins/swiper.min.css') }}">
    <!-- CSS Template -->
    <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'"
          href="{{ asset('assets/front/css/theme.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/front/css/custom.css') }}">
    <!-- colors: amber,blue,brown,cyan,default,green,indigo,orange,pink,purple,red,teal,yellow -->
    <link rel="stylesheet" href="{{ asset('assets/front/css/colors/red.css') }}" id="colorswitch">
</head>

<body>

<div class="page-wrapper auth-page-wrapper">

    <!-- Page Content -->
    @yield('content')
    <!-- end Page Content -->

    <!-- Page mini-Footer -->
    <footer class="mini-footer">
        <div class="container main-container">
            <div class="row">

                @if(resolve(\Modules\Dashboard\Helper\Setting\ThirdPartySettings::class)->e_symbol_code != "")
                    <div class="col-12 text-center">
                        <div class="el-trust-symbol border border-1 rounded-md my-2 mx-auto position-relative shadow">
                            <div class="position-absolute rounded-md w-100 h-100 d-none" style="opacity: 0.3;background-color: #5d5d5d"> </div>
                            {!! resolve(\Modules\Dashboard\Helper\Setting\ThirdPartySettings::class)->e_symbol_code !!}
                        </div>
                    </div>
                @endif

                <div class="col-12 mt-2 mb-3">
                    <div class="footer-light-text">
                        {{ resolve(\Modules\Dashboard\Helper\Setting\GeneralSettings::class)->copy_right_text }}
                       {{-- کلیه حقوق این سایت متعلق به  <strong>{{ company()->site_title }}</strong> می‌باشد.--}}
                    </div>
                </div>

            </div>
        </div>
    </footer>
    <!-- end Page mini-Footer -->

</div>



@include('front::partials.plugin-js')
</body>

</html>
