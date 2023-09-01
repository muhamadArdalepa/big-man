@extends('layouts.app')

@section('content')
    @include('layouts.navbars.guest.navbar')
    <main class="main-content">
        <div class="page-header align-items-start min-vh-50 py-5 m-3 border-radius-lg "
            style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signup-cover.jpg'); background-position: top;">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container pt-5">
                <div class="d-flex">
                    <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                        <div class="card z-index-0">
                            <div class="card-body">
                                <form method="POST" action="{{ route('verify') }}">
                                    @csrf
                                    <div class="flex flex-col">
                                        <input type="hidden" name="id"  value="{{ session('registered_id') }}" >
                                        <label for="email">Varifikasi emailmu untuk melanjutkan</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror " placeholder="Email" aria-label="Email" value="{{ session('registered_email') }}" >
                                        @error('email') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                    @error('terms') <p class='text-danger text-xs'> {{ $message }} </p> @enderror
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Kirim email verifikasi</button>
                                    </div>
                                </form>
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