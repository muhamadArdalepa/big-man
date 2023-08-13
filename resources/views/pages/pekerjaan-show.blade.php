@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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

        #map>div.leaflet-pane.leaflet-map-pane>div.leaflet-pane.leaflet-popup-pane>div>div.leaflet-popup-content-wrapper>div {
            width: 300px !important;
        }
    </style>
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detail Pekerjaan'])

    <div class="container-fluid py-4">
        <div class="card shadow-lg mb-4">
            <div class="card-body p-3">
                <div class="row gx-4 align-items-end">
                    @if ($pekerjaan->jenis_pekerjaan_id != 3)
                        <div class="col-auto">
                            <div class="avatar avatar-xl position-relative">
                                <img src="{{ route('storage.private', 'profile/' . $detail->foto_profil) }}"
                                    alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                            </div>
                        </div>
                    @endif

                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <p class="mb-0 font-weight-bold text-sm">
                                {{ $pekerjaan->jenis_pekerjaan->nama_pekerjaan }} <span
                                    class="badge bg-gradient-primary">{{ $pekerjaan->status }}</span>
                            </p>
                            <h5 class="mb-0">
                                {{ $detail->nama }}
                            </h5>
                        </div>
                    </div>
                    @if (in_array($pekerjaan->status, ['sedang diproses', 'ditunda']))
                        <div class="col-auto d-md-none ms-auto align-self-start">
                            <div class="dropdown">
                                <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown"
                                    id="dropdownButton" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu  dropdown-menu-end  p-2 me-sm-n3" aria-labelledby="dropdownButton">
                                    @if ($pekerjaan->status == 'sedang diproses')
                                        <li>
                                            <a class="dropdown-item btn-selesai border-radius-md py-1 " href="javascript:;">
                                                <i class="fa-solid fa-check me-2 text-success"></i>
                                                Pekerjaan Selesai

                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item btn-tunda border-radius-md py-1 " href="javascript:;">
                                                <i class="fa-solid fa-pause me-2 text-warning"></i>
                                                Tunda Pekerjaan

                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="dropdown-item btn-lanjut border-radius-md py-1 " href="javascript:;">
                                                <i class="fa-solid fa-play me-2 text-primary"></i>
                                                Lanjutkan Pekerjaan

                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item btn-batal border-radius-md py-1 " href="javascript:;">
                                            <i class="fa-solid fa-xmark me-2 text-danger"></i>
                                            Batalkan Pekerjaan
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    @endif

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
                                        <span class="ms-2">Informasi</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @if (in_array($pekerjaan->status, ['sedang diproses', 'ditunda']))
                        <div class="col-auto align-self-center d-none d-md-inline-block">
                            <div class="dropdown">
                                <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown"
                                    id="dropdownButton" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu  dropdown-menu-end  p-2 me-sm-n3" aria-labelledby="dropdownButton">
                                    @if ($pekerjaan->status == 'sedang diproses')
                                        <li>
                                            <a class="dropdown-item btn-selesai border-radius-md py-1 " href="javascript:;">
                                                <i class="fa-solid fa-check me-2 text-success"></i>
                                                Pekerjaan Selesai

                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item btn-tunda border-radius-md py-1 " href="javascript:;">
                                                <i class="fa-solid fa-pause me-2 text-warning"></i>
                                                Tunda Pekerjaan

                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="dropdown-item btn-lanjut border-radius-md py-1 " href="javascript:;">
                                                <i class="fa-solid fa-play me-2 text-primary"></i>
                                                Lanjutkan Pekerjaan

                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item btn-batal border-radius-md py-1 " href="javascript:;">
                                            <i class="fa-solid fa-xmark me-2 text-danger"></i>
                                            Batalkan Pekerjaan
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        <div class="row flex-column-reverse flex-md-row">
            <div class="col-md-7 d-flex">
                <div class="card w-100 d-flex flex-row flex-nowrap overflow-hidden">
                    <div class="card-body w-100 flex-shrink-0">
                        <div class="p-0 m-0">
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-uppercase text-sm">Aktivitas Teknisi</div>
                            </div>
                            @if ($pekerjaan->status == 'sedang diproses')
                                <button id="btnTambahAktivitas"
                                    class="form-control form-control-lg px-3 d-flex align-items-center gap-3"
                                    data-bs-toggle="modal" data-bs-target="#Modal">
                                    Tambah Aktivitas. . .
                                    <i class="fa-solid fa-camera ms-auto"></i>
                                    <i class="fa-solid fa-location-dot"></i>
                                </button>
                            @endif

                            <div class="aktivitas-body mt-3">
                                <span id="aktivitasLoading">Loading data...</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body w-100 flex-shrink-0">
                        <div class="p-0 m-0" id="infoPemasangan">
                            <div class="d-flex align-items-center">
                                <p class="text-uppercase text-sm">Informasi Pemasangan</p>
                                <button class="btn ms-auto btn-info-simpan bg-gradient-primary d-none">
                                    <i class="fa-solid fa-spinner fa-spin d-none me-1" id="info-simpan-loading"></i>
                                    Simpan
                                </button>
                            </div>
                            <div class="form-group">
                                <label for="paket_id" class="form-control-label">Paket Pilihan</label>
                                <select class="form-select" id="paket_id">
                                    <option disabled>--Pilih Paket--</option>
                                    @foreach (\App\Models\Paket::all() as $paket)
                                        <option value="{{ $paket->id }}" {{ $paket->id === $detail->paket_id }}>
                                            {{ $paket->nama_paket }} -- Rp.
                                            {{ $paket->harga }}/{{ $paket->kecepatan }}Mbps
                                        </option>
                                    @endforeach
                                </select>
                                <div id="paket_idFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                            <div class="form-group">
                                <label for="nik" class="form-control-label">NIK</label>
                                <input type="number" id="nik" class="form-control" value="{{ $detail->nik }}">
                                <div id="nikFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                            <div class="form-group">
                                <div class="d-flex align-items-end">
                                    <label for="alamat" class="form-control-label">Alamat</label>
                                    <button class="btn ms-auto btn-link text-secondary font-weight-normal"
                                        data-bs-toggle="modal" data-bs-target="#mapModal">
                                        <i class="fas fa-location-dot"></i>
                                        Pilih Lewat Peta
                                    </button>
                                </div>
                                <textarea type="text" id="alamat" class="form-control" value="{{ $detail->alamat }}">{{ $detail->alamat }}</textarea>

                                <div id="alamatFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                            <div class="form-group">
                                <label for="koordinat_rumah" class="form-control-label">Koordinat Rumah</label>
                                <input type="text" id="koordinat_rumah" class="form-control"
                                    value="{{ $detail->koordinat_rumah }}">
                                <div id="koordinat_rumahFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                            <div class="form-group">
                                <div class="d-flex align-items-end">

                                    <label for="koordinat_odp" class="form-control-label">Koordinat ODP</label>
                                    <button
                                        class="btn ms-auto btn-link text-secondary font-weight-normal btn-gunakan-lokasi">
                                        <i class="fas fa-location-crosshairs"></i>
                                        Gunakan lokasi saya
                                    </button>
                                </div>
                                <input type="text" id="koordinat_odp" class="form-control"
                                    value="{{ $detail->koordinat_odp }}">
                                <div id="koordinat_odpFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">

                                    <div class="form-group">
                                        <label for="serial_number" class="form-control-label">Serial Number</label>
                                        <input type="text" id="serial_number" class="form-control"
                                            value="{{ $detail->serial_number }}">
                                        <div id="serial_numberFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="port_odp" class="form-control-label">Port ODP</label>
                                        <input type="text" id="port_odp" class="form-control"
                                            value="{{ $detail->port_odp }}">
                                        <div id="port_odpFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="ssid" class="form-control-label">SSID</label>
                                        <input type="text" id="ssid" class="form-control"
                                            value="{{ $detail->ssid }}">
                                        <div id="ssidFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="password" class="form-control-label">Password</label>
                                        <input type="text" id="password" class="form-control"
                                            value="{{ $detail->password }}">
                                        <div id="passwordFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="hasil_opm_user" class="form-control-label">Hasil OPM User</label>
                                        <input type="text" id="hasil_opm_user" class="form-control"
                                            value="{{ $detail->hasil_opm_user }}">
                                        <div id="hasil_opm_userFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">

                                    <div class="form-group">
                                        <label for="hasil_opm_odp" class="form-control-label">Hasil OPM ODP</label>
                                        <input type="text" id="hasil_opm_odp" class="form-control"
                                            value="{{ $detail->hasil_opm_odp }}">
                                        <div id="hasil_opm_odpFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kabel_terpakai" class="form-control-label">Kabel Terpakai</label>
                                        <input type="text" id="kabel_terpakai" class="form-control"
                                            value="{{ $detail->kabel_terpakai }}">
                                        <div id="kabel_terpakaiFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>
                                <div class="form-group w-100">
                                    <button class="btn btn-block btn-info-simpan bg-gradient-primary d-none">
                                        <i class="fa-solid fa-spinner fa-spin d-none me-1 info-simpan-loading"></i>
                                        Simpan
                                    </button>
                                </div>


                            </div>
                            <hr class="horizontal ">
                            <div class="d-flex align-items-center">
                                <p class="text-uppercase text-sm">Foto Pendukung</p>
                                <button class="btn ms-auto btn-foto-simpan bg-gradient-primary d-none">
                                    <i class="fa-solid fa-spinner fa-spin d-none me-1 info-simpan-loading"></i>
                                    Simpan
                                </button>
                            </div>
                            <div class=""  id="infoFoto">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto_ktp" class="form-control-label">Foto KTP</label>
                                        <input type="file" id="foto_ktp" class="d-none">
                                        <div class="rounded-3 border overflow-hidden position-relative img-container"
                                            style="cursor: pointer;"
                                            onclick="document.getElementById('foto_ktp').click()">
                                            <img src="{{ route('storage.private', '/' . $detail->foto_ktp) }}"
                                                class="img-preview w-100">
                                            <div class="text-center d-none top-50 start-50 position-absolute img-placeholder"
                                                style="transform: translate(-50%, -50%);">
                                                <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                                <div class="text-sm opacity-7 mt-3">Upload Foto</div>
                                            </div>
                                        </div>
                                        <div id="koordinatFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto_rumah" class="form-control-label">Foto Rumah</label>
                                        <input type="file" id="foto_rumah" class="d-none">
                                        <div class="rounded-3 border overflow-hidden position-relative img-container"
                                            style="cursor: pointer;"
                                            onclick="document.getElementById('foto_rumah').click()">
                                            <img src="{{ route('storage.private', '/' . $detail->foto_ktp) }}"
                                                class="img-preview w-100">
                                            <div class="text-center d-none top-50 start-50 position-absolute img-placeholder"
                                                style="transform: translate(-50%, -50%);">
                                                <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                                <div class="text-sm opacity-7 mt-3">Upload Foto</div>
                                            </div>
                                        </div>
                                        <div id="koordinatFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto_modem" class="form-control-label">Foto KTP</label>
                                        <input type="file" id="foto_modem" class="d-none">
                                        <div class="rounded-3 border overflow-hidden position-relative img-container"
                                            style="cursor: pointer;"
                                            onclick="document.getElementById('foto_modem').click()">
                                            <img src="{{ route('storage.private', '/' . $detail->foto_modem) }}"
                                                class="img-preview w-100 {{ !$detail->foto_modem ? 'd-none' : '' }}">
                                            <div class="text-center {{ $detail->foto_modem ? 'd-none' : '' }} top-50 start-50 position-absolute img-placeholder"
                                                style="transform: translate(-50%, -50%);">
                                                <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                                <div class="text-sm opacity-7 mt-3">Upload Foto</div>
                                            </div>
                                        </div>
                                        <div id="koordinatFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto_letak_modem" class="form-control-label">Foto KTP</label>
                                        <input type="file" id="foto_letak_modem" class="d-none">
                                        <div class="rounded-3 border overflow-hidden position-relative img-container"
                                            style="cursor: pointer;"
                                            onclick="document.getElementById('foto_letak_modem').click()">
                                            <img src="{{ route('storage.private', '/' . $detail->foto_letak_modem) }}"
                                                class="img-preview w-100 {{ !$detail->foto_letak_modem ? 'd-none' : '' }}">
                                            <div class="text-center {{ $detail->foto_letak_modem ? 'd-none' : '' }} top-50 start-50 position-absolute img-placeholder"
                                                style="transform: translate(-50%, -50%);">
                                                <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                                <div class="text-sm opacity-7 mt-3">Upload Foto</div>
                                            </div>
                                        </div>
                                        <div id="koordinatFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto_opm_user" class="form-control-label">Foto KTP</label>
                                        <input type="file" id="foto_opm_user" class="d-none">
                                        <div class="rounded-3 border overflow-hidden position-relative img-container"
                                            style="cursor: pointer;"
                                            onclick="document.getElementById('foto_opm_user').click()">
                                            <img src="{{ route('storage.private', '/' . $detail->foto_opm_user) }}"
                                                class="img-preview w-100 {{ !$detail->foto_opm_user ? 'd-none' : '' }}">
                                            <div class="text-center {{ $detail->foto_opm_user ? 'd-none' : '' }} top-50 start-50 position-absolute img-placeholder"
                                                style="transform: translate(-50%, -50%);">
                                                <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                                <div class="text-sm opacity-7 mt-3">Upload Foto</div>
                                            </div>
                                        </div>
                                        <div id="koordinatFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto_opm_odp" class="form-control-label">Foto KTP</label>
                                        <input type="file" id="foto_opm_odp" class="d-none">
                                        <div class="rounded-3 border overflow-hidden position-relative img-container"
                                            style="cursor: pointer;"
                                            onclick="document.getElementById('foto_opm_odp').click()">
                                            <img src="{{ route('storage.private', '/' . $detail->foto_opm_odp) }}"
                                                class="img-preview w-100 {{ !$detail->foto_opm_odp ? 'd-none' : '' }}">
                                            <div class="text-center {{ $detail->foto_opm_odp ? 'd-none' : '' }} top-50 start-50 position-absolute img-placeholder"
                                                style="transform: translate(-50%, -50%);">
                                                <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                                <div class="text-sm opacity-7 mt-3">Upload Foto</div>
                                            </div>
                                        </div>
                                        <div id="koordinatFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>
                            </div>
                        </div>




                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card mb-4">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Perolehan Poin</p>
                                    <h5 class="font-weight-bolder text-success">
                                        +{{ $pekerjaan->poin }}
                                    </h5>
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

                <div class="card mb-4" id="timCard">
                    <div class="card-header m-0 pb-0">
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">TIM {{ $pekerjaan->tim_id }}</p>
                    </div>

                </div>

                <div class="card overflow-hidden mb-4">
                    <div class="card-header m-0 pb-0">
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Informasi Pelanggan</p>
                    </div>
                    <div class="card-body p-0 pb-3 overflow-hidden">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-4">
                                <small class="opacity-7">Nama</small>
                                <div>{{ $detail->nama }}</div>
                            </li>
                            <li class="list-group-item px-4">
                                <small class="opacity-7">Email</small>
                                <div class="">
                                    <a href="mailto:{{ $detail->email }}">{{ $detail->email }}</a>
                                </div>
                            </li>
                            <li class="list-group-item px-4">
                                <div class="d-flex align-items-center">
                                    <div class="">
                                        <small class="opacity-7">No Telepon</small>
                                        <div class="">
                                            <a href="javascript:;" data-bs-toggle="tooltip"
                                                data-bs-title="Salin nomor">{{ $detail->no_telp }}</a>
                                        </div>
                                    </div>
                                    <a href="https://wa.me/{{ $detail->no_telp }}"
                                        class="btn-circle btn btn-success ms-auto me-3" data-bs-toggle="tooltip"
                                        data-bs-title="Hubungi Whatsapp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('modal')
    @if ($pekerjaan->status == 'sedang diproses')
        <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" id="Modal-Content">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">Tambah Aktivitas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="text-dark">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-sm opacity-7" id="koordinat">Finding location...</div>
                        <div class="text-sm opacity-7" id="location"></div>
                        <div id="koordinatFeedback" class="invalid-feedback text-xs"></div>
                        <input class="d-none" type="file" id="foto" accept="image/*" capture="user">
                        <div class="mt-3 rounded-3 border overflow-hidden position-relative img-container"
                            style="cursor: pointer;" onclick="document.getElementById('foto').click()">
                            <img src="" class="img-preview w-100">
                            <div class="text-center top-50 start-50 position-absolute img-placeholder"
                                style="transform: translate(-50%, -50%);">
                                <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                <div class="text-sm opacity-7 mt-3">Upload Foto</div>
                            </div>
                        </div>
                        <div id="fotoFeedback" class="invalid-feedback text-xs"></div>
                        <div class="form-group">
                            <textarea id="aktivitas" class="form-control mt-3" rows="3" placeholder="Aktivitas"></textarea>
                            <div id="aktivitasFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary me-2 "
                            data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn bg-gradient-primary" id="simpan"><i
                                class="fa-solid fa-spinner fa-spin d-none me-1" id="simpanLoading"></i>Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-body h-100">
                    <div id="map-container" class="h-100">
                        <div id="map"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        // init
        const appUrl = "{{ env('APP_URL') }}";
        const baseUrl = appUrl + '/api/teknisi-pekerjaan'
        const pekerjaan = @json($pekerjaan);
        const navItems = document.querySelectorAll('#tabList .nav-item');
        const mainPanel = document.querySelectorAll('.card-body.flex-shrink-0');
        @if ($pekerjaan->status == 'sedang diproses')
            const Modal = document.getElementById('Modal');
        @endif

        const foto = document.getElementById('foto');
        const aktivitas = document.getElementById('aktivitas');
        const btnSimpan = document.getElementById('simpan');

        let koordinat;
        let alamat;
        let map = null;
        // endinit


        // functions

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    input.nextElementSibling.querySelector('.img-preview').src = e.target.result;
                    input.nextElementSibling.querySelector('.img-preview').classList.remove('d-none');
                    input.nextElementSibling.querySelector('.img-placeholder').classList.add('d-none');
                };

                reader.readAsDataURL(input.files[0]);

            }
        }

        function formatDate(dateString) {
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            };

            if (!dateString) {
                return new Date().toLocaleDateString('id-ID', options);
            }

            return new Date(dateString).toLocaleDateString('id-ID', options);
        }
        @if ($pekerjaan->status == 'sedang diproses')
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(getAddressFromCoordinates, showError);
                } else {
                    document.getElementById('location').textContent = 'Geolokasi tidak didukung oleh browser ini.';
                }
            }

            function getAddressFromCoordinates(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;
                koordinat = latitude + ',' + longitude

                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                    .then(response => response.json())
                    .then(data => {
                        var address = data.display_name;
                        alamat = address
                        document.getElementById('koordinat').textContent = koordinat;
                        document.getElementById('location').textContent = address;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('location').textContent = 'Gagal mendapatkan alamat';
                    });
            }

            function showError(error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        document.getElementById('location').textContent = 'Pengguna menolak permintaan Geolokasi.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        document.getElementById('location').textContent = 'Informasi lokasi tidak tersedia.';
                        break;
                    case error.TIMEOUT:
                        document.getElementById('location').textContent =
                            'Waktu permintaan untuk mendapatkan lokasi pengguna habis.';
                        break;
                    case error.UNKNOWN_ERROR:
                        document.getElementById('location').textContent = 'Terjadi kesalahan yang tidak diketahui.';
                        break;
                }
            }

            function resetModal() {
                $('#Modal').find('.img-preview').addClass('d-none')
                $('#Modal').find('.img-placeholder').removeClass('d-none')
                $('#Modal').find('.invalid-feedback').text('')
                $('#Modal').find('.form-control').removeClass('is-invalid')
                $('#Modal').find('.form-control').val('')
            }
        @endif


        // end functions

        // event listener
        $(document).ready(() => {

            $('#infoPemasangan').find('.form-control').on('keydown', e => {
                $('.btn-info-simpan').removeClass('d-none');
            })

            $('#infoPemasangan').find('.form-select').on('change', e => {
                $('.btn-info-simpan').removeClass('d-none');
            })

            $('.btn-info-simpan').on('click', e => {
                $('.info-simpan-loading').removeClass('d-none');
                let infoData = {};
                document.querySelectorAll('#infoPemasangan .form-control').forEach(el => {
                    if (el.value != '') {
                        let key = $(el).attr('id')
                        infoData[key] = $(el).val()
                    }
                });
                document.querySelectorAll('#infoPemasangan .form-select').forEach(el => {
                    if (el.value != '') {
                        let key = $(el).attr('id')
                        infoData[key] = $(el).val()
                    }
                });
                axios.put(`${appUrl}/api/pemasangan/${pekerjaan.pemasangan_id}`, infoData)
                    .then(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.data.message,
                            timer: 1500,
                            showConfirmButton: false,
                        })
                    }).catch(error => {
                        errList = error.response.data
                        console.log(errList);
                    })
                $('.info-simpan-loading').addClass('d-none');
                $(e.target).addClass('d-none');
            })

            $('#infoFoto').find('input.d-none').on('change', e => {
                $('.btn-foto-simpan').removeClass('d-none');
            })

            $('.btn-foto-simpan').on('click', e => {
                $('.info-foto-loading').removeClass('d-none');
                let data = {};
                document.querySelectorAll('#infoFoto input.d-none').forEach(el => {
                    if (el.value != '') {
                        let key = $(el).attr('id')
                        data[key] = el.files[0]
                    }
                });
                let url = appUrl + '/api/pemasangan/' + pekerjaan.pemasangan_id
                axios.post(url, data, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response => {
                        // document.getElementById('simpanLoading').classList.add('d-none');
                        console.log(response);
                    })
                    .catch(error => {

                    });
                $('.info-simpan-loading').addClass('d-none');
                $(e.target).addClass('d-none');
            })

            axios(`${baseUrl}/${pekerjaan.id}`)
                .then(response => {
                    document.getElementById('aktivitasLoading').style.display = 'none';
                    let aktivitasItems = '';
                    response.data.forEach((e, i) => {
                        var img = e.foto === null ? '' : `
                        <img class="rounded-3 img-fluid mb-2" src="${appUrl}/storage/private/${e.foto}" alt="foto aktivitas" >
                        `
                        var aktAlamat = e.alamat === null ? '' : `
                        <div class="text-xs lh-base"><i class="fas fa-location-dot me-1"></i> ${e.alamat}</div>
                        `
                        var align = e.alamat !== null ? 'start' : 'center';
                        aktivitasItems += `
                        <div class="aktivitas-item mb-3 border-bottom " loading="lazy">
                            <div class="d-flex gap-2 mb-2 align-items-${align}">
                                <img class="rounded-3" src="${appUrl}/storage/private/profile/${e.foto_profil}"
                                    alt="foto profil" height="35">
                                <div>
                                    <div class="mb-0">${e.nama} - <small class="text-xs">${formatDate(e.created_at)}</small></div>
                                    ${aktAlamat}
                                </div>
                            </div>
                            ${img}
                            <p>${e.aktivitas}</p>
                        </div>
                        `
                    });
                    document.getElementsByClassName('aktivitas-body')[0]
                        .insertAdjacentHTML('beforeend', aktivitasItems);
                })
                .catch(error => {
                    document.getElementById('aktivitasLoading').style.display = 'none';
                    console.log(error);
                })


            axios(`${appUrl}/api/tim/${pekerjaan.tim_id}`)
                .then(response => {
                    let timCard = '';
                    let tim = response.data;
                    let timAnggota = '';
                    tim.anggota.forEach((anggota, i) => {
                        timAnggota += `
                        <div class="card card-sm mb-2">
                            <div class="card-body p-2">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="/storage/private/profile/${anggota.foto_profil}" class="avatar">
                                    <div class="">
                                        <p class="m-0">${anggota.nama}</p>
                                        <small class="m-0 opacity-7">${anggota.speciality}</small>

                                    </div>
                                    <a href="https://wa.me/${anggota.no_telp}" class="btn-circle btn btn-success ms-auto me-3"
                                        data-bs-toggle="tooltip" data-bs-title="Hubungi Whatsapp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div>`
                    });

                    console.log(timAnggota);
                    let ketuaLabel = '';
                    let anggotaLabel = '';
                    if (tim.anggota.length > 0) {
                        ketuaLabel = `<p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2">KETUA</p>`
                        anggotaLabel =
                            '<p class="ps-2 text-xxs font-weight-bolder opacity-7 mb-2 mt-1">ANGGOTA</p>';
                    }
                    timCard = `<div class="card-body p-3" >
                        ${ketuaLabel}
                        <div class="card card-sm mb-2">
                            <div class="card-body p-2">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="/storage/private/profile/${tim.foto_profil}" class="avatar">
                                    <div class="">
                                        <p class="m-0">${tim.nama}</p>
                                        <small class="m-0 opacity-7">${tim.speciality}</small>
                                    </div>
                                    <a href="https://wa.me/${tim.no_telp}" class="btn-circle btn btn-success ms-auto me-3"
                                        data-bs-toggle="tooltip" data-bs-title="Hubungi Whatsapp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        ${anggotaLabel}
                        ${timAnggota}
                    </div>`
                    // console.log(timCard);
                    $('#timCard').append((timCard));

                })
                .catch(error => {

                })
        })

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

        $("input.d-none").on('change', function(e) {
            e.preventDefault();
            readURL(this);
        });
        @if ($pekerjaan->status == 'sedang diproses')
            Modal.addEventListener('shown.bs.modal', e => {
                getLocation()
            })

            Modal.addEventListener('hide.bs.modal', e => {
                resetModal()
            })

            btnSimpan.addEventListener('click', e => {
                document.getElementById('simpanLoading').classList.remove('d-none')
                let data = {
                    pekerjaan_id: pekerjaan.id,
                    foto: foto.files[0],
                    aktivitas: aktivitas.value,
                    koordinat: koordinat,
                    alamat: alamat,
                }
                axios.post(baseUrl, data, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response => {
                        document.getElementById('simpanLoading').classList.add('d-none');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.data.message,
                            timer: 1500,
                            showConfirmButton: false,
                        }).then(() => {
                            $('#Modal').modal('hide');
                            let newImg =
                                `<img class="rounded-3 img-fluid mb-2" src="${appUrl}/storage/private/${response.data.foto}" alt="foto aktivitas">`
                            let newAkt = `
                        <div class="aktivitas-item mb-3 border-bottom " loading="lazy">
                            <div class="d-flex gap-2 mb-2 align-items-start">
                                <img class="rounded-3" src="${appUrl}/storage/private/profile/{{ auth()->user()->foto_profil }}"
                                    alt="foto profil" height="35">
                                <div>
                                    <div class="mb-0">{{ auth()->user()->nama }} - <small class="text-xs">${formatDate()}</small></div>
                                    <div class="text-xs lh-base"><i class="fas fa-location-dot me-1"></i> ${alamat}</div>
                                </div>

                                <button class="btn btn-link text-secondary mb-0 ms-auto">
                                    <i class="fa fa-ellipsis-v text-xs"></i>
                                </button>
                            </div>
                            ${newImg}
                            <p>${data.aktivitas}</p>
                        </div>`;
                            console.log(newAkt);
                            document.getElementsByClassName('aktivitas-body')[0].insertAdjacentHTML(
                                'afterbegin',
                                newAkt)
                        })
                    })
                    .catch(error => {
                        document.getElementById('simpanLoading').classList.add('d-none')
                        $('#Modal').find('.invalid-feedback').text('')
                        $('#Modal').find('.form-control').removeClass('is-invalid')
                        if (error.response && error.response.data && error.response.data.errors) {
                            var errors = error.response.data.errors;
                            for (const key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    $('#' + key).addClass('is-invalid');
                                    $('#' + key).addClass('is-invalid-image');
                                    $('#' + key + 'Feedback').show();
                                    $('#' + key + 'Feedback').text(errors[key]);
                                }
                            }
                        }
                    });
            })
            $('.btn-tunda').on('click', () => {
                Swal.fire({
                    title: 'Konfirmasi Tunda',
                    text: 'Apakah Anda yakin ingin menunda pekerjaan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#5e72e4',
                    cancelButtonColor: '#f5365c',
                    confirmButtonText: 'Ya, Selesai!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let data = {
                            status: 'ditunda',
                            jenis_pekerjaan_id: pekerjaan.jenis_pekerjaan_id,
                            poin: pekerjaan.poin,
                            tim_id: pekerjaan.tim_id
                        }
                        axios.put(`${appUrl}/api/pekerjaan/${pekerjaan.id}`, data)
                            .then(() => {
                                location.reload()
                            })
                    }
                });

            })
            $('.btn-selesai').on('click', () => {
                Swal.fire({
                    title: 'Konfirmasi Selesai',
                    text: 'Apakah Anda yakin ingin menyelesaikan pekerjaan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#5e72e4',
                    cancelButtonColor: '#f5365c',
                    confirmButtonText: 'Ya, Selesai!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let data = {
                            status: 'selesai',
                            jenis_pekerjaan_id: pekerjaan.jenis_pekerjaan_id,
                            poin: pekerjaan.poin,
                            tim_id: pekerjaan.tim_id
                        }
                        axios.put(`${appUrl}/api/pekerjaan/${pekerjaan.id}`, data)
                            .then(() => {
                                location.reload()
                            })
                    }
                });

            })
        @endif

        function getAddressFromCoordinatesMap(position) {
            var lat = position.coords.latitude;
            var long = position.coords.longitude;
            setupMap(lat, long)
        }



        function setupMap(lat, long) {
            if (map === null) {
                map = L.map('map').setView([lat, long], 16);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 20,
                    attribution: '© OpenStreetMap'
                }).addTo(map);
            }
            var popup = L.popup();
            map.on('click', e => {
                fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`
                    )
                    .then(response => response.json())
                    .then(data => {
                        var address = data.display_name;
                        $('#mapModal').find('.leaflet-popup-content .text-center').html(`
                        <div class="mb-3 w-auto">${address}</div>
                        <button class="btn btn-primary btn-pilih-lokasi">Pilih Lokasi</button>
                        `)
                        $('.btn-pilih-lokasi').on('click', () => {
                            $('#alamat').val(address)
                            $('#alamat').text(address)
                            $('#koordinat_rumah').val(e.latlng.lat + ',' + e.latlng.lng)
                            $('#mapModal').modal('hide');
                            $('.btn-info-simpan').removeClass('d-none')
                        })
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        $('#mapModal').find('.leaflet-popup-content .text-center').html(
                            'Gagal mendapatkan alamat');
                    });

                popup
                    .setLatLng(e.latlng)
                    .setContent(`
                    <div class="text-center">
                        <i class="fa-solid fa-spinner fa-spin me-1"></i>
                    </div>
                    `)
                    .openOn(map);
            })

        }

        $('#mapModal').on('shown.bs.modal', e => {
            // setup map 
            mapHeight = document.getElementById('map-container').offsetHeight
            $('#map').css('height', mapHeight)
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(getAddressFromCoordinatesMap, errGeolokasi);
            } else {
                setupMap(-0.0277, 109.3425)
            }
        })

        $('.btn-gunakan-lokasi').on('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    $('#koordinat_odp').val(position.coords.latitude + ',' + position.coords.longitude)
                    $('.btn-info-simpan').removeClass('d-none')
                }, errGeolokasi);
            } else {
                alert('perangkat tidak didukung geolokasi');
            }
        })

        function errGeolokasi(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert('Pengguna menolak permintaan Geolokasi.');
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert('Informasi lokasi tidak tersedia.');
                    break;
                case error.TIMEOUT:
                    alert('Waktu permintaan untuk mendapatkan lokasi pengguna habis.');
                    break;
                case error.UNKNOWN_ERROR:
                    alert('Terjadi kesalahan yang tidak diketahui.');
                    break;
            }
        }

        @if ($pekerjaan->status == 'ditunda')
            $('.btn-lanjut').on('click', () => {
                Swal.fire({
                    title: 'Lanjutkan Pekerjaan',
                    text: 'Apakah Anda yakin ingin melanjutkan pekerjaan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#5e72e4',
                    cancelButtonColor: '#f5365c',
                    confirmButtonText: 'Ya, Selesai!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let data = {
                            status: 'sedang diproses',
                            jenis_pekerjaan_id: pekerjaan.jenis_pekerjaan_id,
                            poin: pekerjaan.poin,
                            tim_id: pekerjaan.tim_id
                        }
                        axios.put(`${appUrl}/api/pekerjaan/${pekerjaan.id}`, data)
                            .then(() => {
                                location.reload()
                            })
                    }
                });

            })
        @endif
        // end event listener
    </script>
@endpush
