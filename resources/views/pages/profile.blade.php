@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
    <style>
        .card-body.flex-shrink-0 {
            transition: margin-left 300ms ease-in-out;
        }

        .card-body.flex-shrink-0 div {
            transition: opacity 300ms ease-in-out;
        }

        .img-container {
            background-size: cover;
            min-height: 150px;
            max-height: 300px;
        }
    </style>
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Your Profile'])
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{ route('storage.private', auth()->user()->foto_profil) }}" alt="profile_image"
                            class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ auth()->user()->nama }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ auth()->user()->speciality }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist" id="tabList">
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

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col">
                <div class="card w-100 d-flex flex-row flex-nowrap overflow-hidden">
                    <div class="card-body w-100 flex-shrink-0">
                        <div class="p-0 m-0">
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-uppercase text-sm">Aktivitas</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body w-100 flex-shrink-0">
                        <div class="p-0 m-0">
                            <form method="post" action="{{ route('profile.update',auth()->user()->id) }}">
                                @method('PUT')
                                @csrf
                                <div class="d-flex align-items-center">
                                    <p class="text-uppercase text-sm">Pengaturan Akun</p>
                                    <button class="btn ms-auto btn-info-simpan bg-gradient-primary">
                                        <i class="fa-solid fa-spinner d-none fa-spin me-1" id="info-simpan-loading"></i>
                                        Simpan
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="nama" class="form-control-label">Nama</label>
                                            <input type="text" name="nama" id="nama" class="@error('nama') is-invalid @enderror form-control"
                                                value="{{ auth()->user()->nama }}">
                                            @error('nama')<div id="namaFeedback" class="invalid-feedback text-xs">{{$message}}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="wilayah_id" class="form-control-label">Wilayah</label>
                                            <select name="wilayah_id" id="wilayah_id" class="form-select">
                                                @foreach (\App\Models\Wilayah::all() as $wilayah)
                                                    <option
                                                        {{ auth()->user()->wilayah_id == $wilayah->id ? 'selected' : '' }}
                                                        value="{{ $wilayah->id }}">{{ $wilayah->nama_wilayah }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="speciality" class="form-control-label">Speciality</label>
                                            <input type="text" name="speciality" id="speciality" class="@error('speciality') is-invalid @enderror form-control"
                                                value="{{ auth()->user()->speciality }}">
                                            @error('speciality')<div id="specialityFeedback" class="invalid-feedback text-xs">{{$message}}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-control-label">Email</label>
                                            <input type="text" name="email" id="email" class="@error('email') is-invalid @enderror form-control"
                                                value="{{ auth()->user()->email }}">
                                            @error('email')<div id="emailFeedback" class="invalid-feedback text-xs">{{$message}}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="no_telp" class="form-control-label">No Telepon</label>
                                            <input type="text" name="no_telp" id="no_telp" class="@error('no_telp') is-invalid @enderror form-control"
                                                value="{{ auth()->user()->no_telp }}">
                                            @error('no_telp')<div id="no_telpFeedback" class="invalid-feedback text-xs">{{$message}}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                                    <p class="text-uppercase text-sm">Ubah Password</p>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="old_password" class="form-control-label">Password Lama</label>
                                                <input type="password" id="old_password" name="old_password" class="@error('old_password') is-invalid @enderror form-control">
                                                @error('old_password')<div id="old_passwordFeedback" class="invalid-feedback text-xs">{{$message}}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="new_password" class="form-control-label">Password Baru</label>
                                                <input type="password" id="new_password" name="new_password" class="@error('new_password') is-invalid @enderror form-control">
                                                @error('new_password')<div id="new_passwordFeedback" class="invalid-feedback text-xs">{{$message}}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="password_confirmation" class="form-control-label">Konfirmasi Password</label>
                                                <input type="password" id="password_confirmation" name="password_confirmation" class="@error('password_confirmation') is-invalid @enderror form-control">
                                                @error('password_confirmation')<div id="password_confirmationFeedback" class="invalid-feedback text-xs">{{$message}}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                            </form>

                        </div>

                    </div>
                </div>
            </div>
            @if (auth()->user()->role == 2)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Perolehan Poin</p>
                                        <h5 class="font-weight-bolder">
                                            {{ auth()->user()->poin }}
                                        </h5>
                                        <p class="mb-0">
                                            <span class="text-success text-sm font-weight-bolder">+55%</span>
                                            since yesterday
                                        </p>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
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
                                                data-bs-toggle="collapse"
                                                data-bs-target="#flush-collapse{{ $i }}" aria-expanded="false"
                                                aria-controls="flush-collapse{{ $i }}">
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
                                                    <p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2 mt-1">ANGGOTA
                                                    </p>
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
    @endif

    </div>
    @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('js')
    <script>
        const navItems = document.querySelectorAll('#tabList .nav-item');
        const mainPanel = document.querySelectorAll('.card-body.flex-shrink-0');
        navItems[0].addEventListener('click', () => {
            mainPanel[0].querySelector('div').style.display = 'block'
            mainPanel[0].querySelector('div').style.opacity = 1

            mainPanel[1].querySelector('div').style.opacity = 0
            setTimeout(() => {
                mainPanel[1].querySelector('div').style.display = 'none'
            }, 300);
            mainPanel[0].style.marginLeft = 0;
        });

        navItems[1].addEventListener('click', () => {
            mainPanel[1].querySelector('div').style.display = 'block'
            mainPanel[1].querySelector('div').style.opacity = 1

            mainPanel[0].querySelector('div').style.opacity = 0
            setTimeout(() => {
                mainPanel[0].querySelector('div').style.display = 'none'
            }, 300);
            mainPanel[0].style.marginLeft = -mainPanel[0].offsetWidth + "px";
        });
    </script>
@endpush
