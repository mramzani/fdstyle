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
    <!-- FAVICON AND TOUCH ICONS -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('images/apple-touch-icon.png') }}" type="image/x-icon">
    @yield('style')
    @if(resolve(\Modules\Dashboard\Helper\Setting\ThirdPartySettings::class)->mediaad != "")
        @php
        $mediaad = resolve(\Modules\Dashboard\Helper\Setting\ThirdPartySettings::class)->mediaad;
        @endphp
        <script>
            !function (t, e, n) {
                t.yektanetAnalyticsObject = n, t[n] = t[n] || function () {
                    t[n].q.push(arguments)
                }, t[n].q = t[n].q || [];
                var a = new Date, r = a.getFullYear().toString() + "0" + a.getMonth() + "0" + a.getDate() + "0" + a.getHours(),
                    c = e.getElementsByTagName("script")[0], s = e.createElement("script");
                s.id = "ua-script-{{ $mediaad }}"; s.dataset.analyticsobject = n;
                s.async = 1; s.type = "text/javascript";
                s.src = "https://cdn.yektanet.com/rg_woebegone/scripts_v3/{{ $mediaad }}/rg.complete.js?v=" + r, c.parentNode.insertBefore(s, c)
            }(window, document, "yektanet");
        </script>
    @endif
    <!-- Hotjar Tracking Code for my site -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:3433612,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
</head>

<body>

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


<!-- JS Global Compulsory -->
<script src="{{ asset('assets/front/js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('assets/front/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/front/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/front/js/plugins/jquery.knob.js') }}"></script>
<script src="{{ asset('assets/front/js/plugins/jquery.throttle.js') }}"></script>
<script src="{{ asset('assets/front/js/plugins/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('assets/front/js/theme.js') }}"></script>
<script type="text/javascript">
    RINO.StickyHeader();
    RINO.CategoryList();
    RINO.ResponsiveHeader();
    RINO.SearchResult();
    RINO.NiceScroll();
    RINO.BackToTop();
</script>

@yield('script')
@if(resolve(\Modules\Dashboard\Helper\Setting\ThirdPartySettings::class)->goftino != "")
    <!-- start GOFTINO code--->
    <script type="text/javascript">
        !function(){var i="{{ resolve(\Modules\Dashboard\Helper\Setting\ThirdPartySettings::class)->goftino }}",a=window,d=document;function g(){var g=d.createElement("script"),s="https://www.goftino.com/widget/"+i,l=localStorage.getItem("goftino_"+i);g.async=!0,g.src=l?s+"?o="+l:s;d.getElementsByTagName("head")[0].appendChild(g);}"complete"===d.readyState?g():a.attachEvent?a.attachEvent("onload",g):a.addEventListener("load",g,!1);}();
    </script>
    <!---end GOFTINO code--->
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
@if(resolve(\Modules\Dashboard\Helper\Setting\ThirdPartySettings::class)->google_tracking_id != "")
    @php
        $google_tracking_id = resolve(\Modules\Dashboard\Helper\Setting\ThirdPartySettings::class)->google_tracking_id
    @endphp
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $google_tracking_id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ $google_tracking_id }}');
    </script>
@endif
</body>

</html>
