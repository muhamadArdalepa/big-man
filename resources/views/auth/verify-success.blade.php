@extends('layouts.app')

@section('content')
    @include('layouts.navbars.guest.navbar')
    <main class="main-content  mt-0 pb-5">
        <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg "
            style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signup-cover.jpg'); background-position: top;">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
                        <h1 class="text-white mb-2 mt-5">Selamat datang</h1>
                        <p class="text-lead text-white">Ayo bergabung dan akses layanan <strong>BigNet</strong> dengan mudah
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                    <div class="card z-index-0">
                        <div class="card-body">
                            <h1>Akun berhasil diaktifkan</h1>
                            <p id="redirecting"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('layouts.footers.guest.footer')
@endsection
@push('js')
    <script>
        var timer = 2;
        setInterval(() => {
            $('#redirecting').text('Mengalihkan ke halaman Login ' + (
                timer--) + '...');
        }, 1000);
        setTimeout(() => {
            // Change 'location.redirect' to 'window.location.href'
            window.location.href = "{{ route('login') }}";
        }, 3000);
    </script>
@endpush

