@extends('layouts.app')

@section('content')
    @include('layouts.navbars.guest.navbar')
    <main class="main-content">
        <div class="page-header align-items-start min-vh-50 py-5 m-3 border-radius-lg "
            style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signup-cover.jpg'); background-position: top;">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container pt-5">
                <div class="d-flex">
                    <div class="col-auto mx-auto">
                        <div class="card z-index-0">
                            <div class="card-body">
                                <p>Email verifikasi telah dikirimkan ke <strong>{{session('registered_email')}} </strong> </p>
                                <div class="small">Belum menerima kode? <a class="text-primary" href="{{route('verify') }}">Kirim ulang kode</a></div>
                            </div>
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
    document.getElementById('wilayah_id').addEventListener('click',function (e) {
        e.target.style.color = '#495057'
    })
</script>
@endpush