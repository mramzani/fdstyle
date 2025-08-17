<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="_q5QKFrlYbHWa5B1hfxUKP81gkFf3l8T62TYUqANijA" />
    {{--<link rel="icon" type="image/x-icon" href="">--}}

    <!-- font-awesome -->
    <link rel="stylesheet" href="{{ asset('assets/front/css/font-awesome.min.css') }}">
    <!-- Bootstrap 4.5.3 -->
    <link rel="stylesheet" href="{{ asset('assets/front/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/front.css') }}">
    {!! SEO::generate() !!}

    <title>@yield('title')</title>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WVHD4C8');</script>
    <!-- End Google Tag Manager -->
    <!-- FAVICON AND TOUCH ICONS -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('images/apple-touch-icon.png') }}" type="image/x-icon">

    @yield('style')
    @livewireStyles

    
</head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WVHD4C8"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="page-wrapper">
    <!-- Page Header -->
    @include('front::partials.svg')
    @include('front::partials.header')
    <!-- header responsive -->
    @include('front::partials.header-responsive')
    <!-- end header responsive -->
    <!-- end Page Header -->

    <!-- Page Content -->
    @yield('mainContent')
    <!-- end Page Content -->

    <!-- Page Footer -->
    @include('front::partials.footer')
    <!-- end Page Footer -->
</div>

@include('front::partials.plugin-js')
<script type="text/javascript">
    RINO.StickyHeader();
    RINO.CategoryList();
    RINO.ResponsiveHeader();
    RINO.SearchResult();
    RINO.NiceScroll();
    RINO.BackToTop();
</script>
@livewireScripts
@yield('script')
@if(resolve(\Modules\Dashboard\Helper\Setting\ThirdPartySettings::class)->goftino != "")
    
    @if(Auth::guard('customer')->check())
        <script type="text/javascript" >
            window.addEventListener('goftino_ready', function () {
                Goftino.setUser({
                    name: '{{ Auth::guard('customer')->user()->full_name ?? '' }}',
                    phone: '{{ Auth::guard('customer')->user()->mobile ?? '' }}',
                });
            });
        </script>
    @endif
@endif
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-6N5Q7F1NZN"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-6N5Q7F1NZN');
</script>
</body>

</html>
