<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('img/logos/big-warna.png') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>BIG Man</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" rel="stylesheet" />

    <!-- CSS Files -->

    <link href="{{ asset('build/assets/argon-dashboard-bef89ddb.css') }}" rel="stylesheet" />

    {{-- @vite(['resources/scss/argon-dashboard.scss', 'resources/js/app.js']) --}}
    @stack('css')
    <style>
        #btn-to-top {
           transition-duration: 300ms;
           transition-timing-function: ease-in-out;
        }
    </style>
</head>

<body class="{{ $class ?? '' }}">
    <button id="btn-to-top" class="btn btn-dark position-fixed opacity-5 d-flex align-items-center justify-content-center" style="z-index: 100; bottom: -6rem;right: 4rem;width: 3rem;height: 3rem;"><i class="fa-solid fa-chevron-up"></i></button>
    @guest @yield('content') @endguest @auth @if (in_array(request()->route()->getName(),
            ['auth.profile', 'teknisi.show']))
        <div class="position-absolute w-100 min-height-300 top-0"
            style="
                background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg');
                background-position-y: 50%;
            ">
            <span class="mask bg-danger opacity-7"></span>
        </div>
    @else
        <div class="min-height-300 bg-danger position-absolute w-100"></div>
    @endif @include('layouts.navbars.auth.sidenav')

    <main class="main-content border-radius-lg">@yield('content')</main>
@endauth
@stack('modal')

<script src="{{ asset('build/assets/app-54d9a510.js') }}"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);


    }
    window.addEventListener("scroll", function() {
        if (window.pageYOffset > 300) {
            document.getElementById('btn-to-top').style.bottom = "4rem";
        } else {
            document.getElementById('btn-to-top').style.bottom = "-6rem" ;
        }
    });
    document.getElementById('btn-to-top').addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    })
</script>


<script src="{{ asset('assets/plugins/Buttons/buttons.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery/jquery-3.7.0.min.js') }}"></script>

@stack('js')
</body>

</html>
