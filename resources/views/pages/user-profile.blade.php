@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Profil ' . $user->nama])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{ route('storage.private',  $user->foto_profil) }}" alt="profile_image"
                            class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ $user->nama }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ $user->speciality }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                                    <i class="ni ni-app"></i>
                                    <span class="ms-2">Aktivitas</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <i class="ni ni-settings-gear-65"></i>
                                    <span class="ms-2">Pengaturan</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="alert">
        @include('components.alert')
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <form role="form" method="POST" action={{ route('profile.update') }} enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Edit Profile</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Informasi Pengguna</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Nama</label>
                                        <input class="form-control" type="text" name="nama"
                                            value="{{ old('username', $user->username) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Email address</label>
                                        <input class="form-control" type="email" name="email"
                                            value="{{ old('email', $user->email) }}">
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Contact Information</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Address</label>
                                        <input class="form-control" type="text" name="address"
                                            value="{{ old('address', $user->address) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">City</label>
                                        <input class="form-control" type="text" name="city"
                                            value="{{ old('city', $user->city) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Country</label>
                                        <input class="form-control" type="text" name="country"
                                            value="{{ old('country', $user->country) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Postal code</label>
                                        <input class="form-control" type="text" name="postal"
                                            value="{{ old('postal', $user->postal) }}">
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">About me</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">About me</label>
                                        <input class="form-control" type="text" name="about"
                                            value="{{ old('about', $user->about) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Perolehan Poin</p>
                                    <h5 class="font-weight-bolder">
                                        {{ $user->poin }}
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder">+55%</span>
                                        since yesterday
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="ni ni-bulb-61 text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header m-0 pb-0">
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">TIM TEKNISI</p>
                    </div>
                    <hr class="horizontal dark">
                    <div class="card-body p-3 pt-0">
                        <div class="accordion accordion-flush" id="timAccordion">
                            @foreach ($tims as $i => $tim)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-heading{{ $i }}">
                                        <button class="accordion-button collapsed text-xs fw-bold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $i }}"
                                            aria-expanded="false" aria-controls="flush-collapse{{ $i }}">
                                            TIM {{ $tim->id }}
                                            <span class="badge bg-gradient-success ms-3">{{ $tim->status }}</span>
                                        </button>
                                    </h2>
                                    <div id="flush-collapse{{ $i }}" class="accordion-collapse collapse"
                                        aria-labelledby="flush-heading{{ $i }}"
                                        data-bs-parent="#timAccordion">
                                        <div class="accordion-body">
                                            @if ($tim->anggota->count() > 1)
                                                <p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2">KETUA</p>
                                            @endif
                                            <div class="card card-sm mb-2">
                                                <div class="card-body p-2">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="/storage/private/{{ $tim->foto_profil }}"
                                                            class="avatar">
                                                        <p class="m-0">{{ $tim->ketua }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($tim->anggota->count() > 1)
                                                <p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2 mt-1">ANGGOTA</p>
                                            @endif
                                            @foreach ($tim->anggota as $anggota)
                                                @if ($anggota->user_id != $tim->user_id)
                                                    <div class="card card-sm mb-2">
                                                        <div class="card-body p-2">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <img src="/storage/private/{{ $anggota->foto_profil }}"
                                                                    class="avatar">
                                                                <p class="m-0">{{ $anggota->nama }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
