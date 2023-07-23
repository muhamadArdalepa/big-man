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
                        <p class="text-lead text-white">Ayo bergabung dan akses layanan <strong>BigNet</strong> dengan mudah</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                    <div class="card z-index-0">
                        <div class="card-body">
                            <form method="POST" action="{{ route('register.perform') }}">
                                @csrf
                                <div class="flex flex-col mb-3">
                                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror " placeholder="Nama Lengkap" aria-label="Name" value="{{ old('nama') }}" >
                                    @error('nama') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                                <div class="flex flex-col mb-3">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror " placeholder="Email" aria-label="Email" value="{{ old('email') }}" >
                                    @error('email') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                                <div class="flex flex-col mb-3">
                                    <input type="number" name="no_telp" class="form-control @error('no_telp') is-invalid @enderror " placeholder="Nomor Telepon" aria-label="Nomor Telepon" value="{{ old('no_telp') }}" >
                                    @error('no_telp') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                                <div class="flex flex-col mb-3">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror " placeholder="Password" aria-label="Password">
                                </div>
                                <div class="flex flex-col mb-3">
                                    <input type="password" name="password_confirmation" class="form-control @error('password') is-invalid @enderror " placeholder="Konfirmasi Password" aria-label="Konfirmasi Password">
                                    @error('password') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                                <div class="flex flex-col mb-3">
                                    <select id="kota_id" name="kota_id" class="form-control @error('kota_id') is-invalid @enderror " style="color: #adb5bd;">
                                        <option selected disabled>Kota</option>
                                        @foreach($kotas as $kota)
                                        <option value="{{$kota->id}}" {{old('kota_id') == $kota->id ? 'selected' : ''}}>{{$kota->kota}}</option>
                                        @endforeach
                                    </select>
                                    @error('kota_id') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                </div>
                                <div class="form-check form-check-info text-start">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="flexCheckDefault" >
                                    <label class="form-check-label" for="flexCheckDefault">
                                        I agree the <a href="javascript:;" class="font-weight-bolder">Terms and
                                            Conditions</a>
                                    </label>
                                </div>
                                @error('terms') <p class='text-danger text-xs'> {{ $message }} </p> @enderror
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Daftar</button>
                                </div>
                                <p class="text-sm mt-3 mb-0">Sudah punya akun? <a href="{{ route('login') }}"
                                        class="text-dark font-weight-bolder">Masuk</a></p>
                            </form>
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
    document.getElementById('kota_id').addEventListener('click',function (e) {
        e.target.style.color = '#495057'
    })
</script>
@endpush