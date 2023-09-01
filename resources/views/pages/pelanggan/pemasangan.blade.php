@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detail Pemasangan Jaringan'])
    @push('css')
        <style>
            #mainPanel .card-body {
                transition: margin-left 300ms ease-out;
            }

            .img-container {
                background-size: cover;
                min-height: 200px;
            }
        </style>
    @endpush
    <div class="container-fluid py-4">
        <div class="card shadow-lg mb-4">
            <div class="card-body py-3 px-5">
                <div class="row gx-4 align-items-end" id="mainHeader">
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <p class="mb-0 font-weight-bold text-sm">
                            </p>
                            @if ($pemasangan->sn === null)
                                <span class="badge bg-gradient-primary">
                                @else
                                    <h5 class="mb-0 text">
                            @endif
                            {{ $pemasangan->sn != null ? $pemasangan->sn : $pemasangan->status }}
                            @if ($pemasangan->sn === null)
                                </span>
                            @else
                                </h5>
                            @endif
                        </div>
                    </div>
                    <div class="col-auto d-sm-none ms-auto align-self-start">
                        <button class="btn btn-link text-secondary mb-0">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                    </div>
                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1" role="tablist" id="tabList">
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center "
                                        data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                        <i class="ni ni-settings-gear-65"></i>
                                        <span class="ms-2">Informasi</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center "
                                        data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                                        <i class="ni ni-app"></i>
                                        <span class="ms-2">Aktivitas</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-auto align-self-center d-none d-sm-inline-block">
                        <div class="dropdown">
                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown" id="dropdownButton"
                                aria-expanded="false">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu  dropdown-menu-end  p-2 me-sm-n3" aria-labelledby="dropdownButton">
                                <li>
                                    <a id="btnSelesai" class="dropdown-item border-radius-md py-1 " href="javascript:;">
                                        <i class="fa-solid fa-check me-2 text-success"></i>
                                        Pekerjaan Selesai

                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md py-1 " href="javascript:;">
                                        <i class="fa-solid fa-spinner me-2 text-warning"></i>
                                        Pekerjaan Pending

                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item border-radius-md py-1 " href="javascript:;">
                                        <i class="fa-solid fa-xmark me-2 text-danger"></i>
                                        Hapus Pekerjaan
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 d-flex">
                <div class="card w-100 d-flex flex-row flex-nowrap overflow-hidden" id="mainPanel">
                    <div class="card-body w-100 flex-shrink-0">
                        <p class="text-uppercase text-sm">Informasi Pemasangan</p>
                        <div class="form-group">
                            <label for="paket_id" class="form-control-label">Pilih Paket</label>
                            <select class="form-select" id="paket_id">
                                <option disabled>--Pilih Paket--</option>
                                @foreach ($pakets as $paket)
                                    <option value="{{ $paket->id }}" {{ $paket->id === $pemasangan->paket_id }}>
                                        {{ $paket->nama_paket }} -- Rp.
                                        {{ $paket->harga }}/{{ $paket->kecepatan }}Mbps
                                    </option>
                                @endforeach
                            </select>
                            <div id="paket_idFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                        <div class="form-group">
                            <label for="nik" class="form-control-label">NIK</label>
                            <input type="number" id="nik" class="form-control" value="{{ $pemasangan->nik }}">
                            <div id="nikFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                        <div class="form-group">
                            <label for="alamat" class="form-control-label">Alamat</label>
                            <div class="input-group">
                                <input type="text" id="alamat" class="form-control" value="{{ $pemasangan->alamat }}">
                                <button class="btn btn-link text-secondary font-weight-normal border">Pilih Lewat
                                    Peta</button>
                            </div>
                            <div id="alamatFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                        <div class="form-group">
                            <label for="koordinat_rumah" class="form-control-label">Koordinat Rumah</label>
                            <input type="text" id="koordinat_rumah" class="form-control"
                                value="{{ $pemasangan->koordinat_rumah }}">
                            <div id="koordinat_rumahFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                        <hr class="horizontal ">
                        <p class="text-uppercase text-sm">Foto Pendukung</p>
                        <div class="form-group">
                            <label for="foto_ktp" class="form-control-label">Foto KTP</label>
                            <input type="file" id="foto_ktp" class="d-none">
                            <div class="rounded-3 border overflow-hidden position-relative img-container" style="cursor: pointer;"
                                onclick="document.getElementById('foto_ktp').click()" >
                                <img src="{{route('storage.private','pemasangan/'.$pemasangan->foto_ktp)}}" class="img-preview w-100">
                                <div class="text-center d-none top-50 start-50 position-absolute img-placeholder"
                                    style="transform: translate(-50%, -50%);">
                                    <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                    <div class="text-sm opacity-7 mt-3">Upload Foto</div>
                                </div>
                            </div>
                            <div id="koordinatFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                        <div class="form-group">
                            <label for="foto_rumah" class="form-control-label">Foto KTP</label>
                            <input type="file" id="foto_rumah" class="d-none">
                            <div class="rounded-3 border overflow-hidden position-relative img-container" style="cursor: pointer;"
                                onclick="document.getElementById('foto_rumah').click()" >
                                <img src="{{route('storage.private','pemasangan/'.$pemasangan->foto_ktp)}}" class="img-preview w-100">
                                <div class="text-center d-none top-50 start-50 position-absolute img-placeholder"
                                    style="transform: translate(-50%, -50%);">
                                    <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                    <div class="text-sm opacity-7 mt-3">Upload Foto</div>
                                </div>
                            </div>
                            <div id="koordinatFeedback" class="invalid-feedback text-xs"></div>
                        </div>



                    </div>
                    <div class="card-body w-100 flex-shrink-0">
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-uppercase text-sm">Aktivitas Teknisi</div>
                        </div>
                        <button id="btnTambahAktivitas"
                            class="form-control form-control-lg px-3 d-flex align-items-center gap-3"
                            data-bs-toggle="modal" data-bs-target="#Modal">
                            Tambah Aktivitas. . .
                            <i class="fa-solid fa-camera ms-auto"></i>
                            <i class="fa-solid fa-location-dot"></i>
                        </button>
                        <div class="aktivitas-body mt-3">
                            <span id="aktivitasLoading">Loading data...</span>
                        </div>
                    </div>

            </div>
            </div>
            <div class="col-md-4">

            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('js')
    <script>
        // init
        const appUrl = "{{ env('APP_URL') }}";
        const baseUrl = appUrl + '/api/pelanggan-pemasangan'
        const pakets = @json($pakets);
        const navItems = document.querySelectorAll('#tabList .nav-item');
        const main = document.getElementById('mainPanel');
        let mainPanel = document.querySelectorAll('#mainPanel .card-body');
        // endinit


        // functions
        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    input.nextElementSibling.querySelector('.img-preview').src = e.target.result;
                    input.nextElementSibling.querySelector('.img-preview').style.display = 'inline-block'
                    input.nextElementSibling.querySelector('.img-placeholder').style.display = 'none'


                };

                reader.readAsDataURL(input.files[0]);

            }
        }

        function setMainPanel(i) {
            mainPanel[0].style.marginLeft = i === 0 ? 0 : (0 - mainPanel[0].offsetWidth) + "px"
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
            koordinat = latitude + ', ' + longitude

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
            $('.img-preview').css('display', 'none')
            $('.img-placeholder').css('display', 'inline-block')
            $('#Modal').find('.invalid-feedback').text('')
            $('#Modal').find('.form-control').removeClass('is-invalid')
            $('#Modal').find('.form-control').val('')
        }
        // end functions

        // event listener
        $(document).ready(() => {
        //     img - preview.style.display = 'none';
        //     axios(`${baseUrl}/${pekerjaanId}`)
        //         .then(response => {
        //             document.getElementById('aktivitasLoading').style.display = 'none';
        //             let aktivitasItems = '';
        //             response.data.forEach((e, i) => {
        //                 let img = e.foto === null ? '' : `
    //         <img class="rounded-3 img-fluid mb-2" src="${appUrl}/storage/private/aktivitas/${e.foto}" alt="foto aktivitas" >
    //         `
        //                 aktivitasItems += `
    //         <div class="aktivitas-item mb-3 border-bottom " loading="lazy">
    //             <div class="d-flex gap-2 mb-2 align-items-start">
    //                 <img class="rounded-3" src="${appUrl}/storage/private/${e.foto_profil}"
    //                     alt="foto profil" height="35">
    //                 <div>
    //                     <p class="mb-0">${e.nama} - <small class="text-xs">${formatDate(e.created_at)}</small></p>
    //                     <div class="text-xs lh-sm">${e.alamat}</div>
    //                 </div>

    //                 <button class="btn btn-link text-secondary mb-0 ms-auto">
    //                     <i class="fa fa-ellipsis-v text-xs"></i>
    //                 </button>
    //             </div>
    //             ${img}
    //             <p>${e.aktivitas}</p>
    //         </div>
    //         `
        //             });
        //             document.getElementsByClassName('aktivitas-body')[0]
        //                 .insertAdjacentHTML('beforeend', aktivitasItems);
        //         })
        //         .catch(error => {
        //             document.getElementById('aktivitasLoading').style.display = 'none';
        //             console.log(error);
        //         })

        })

        $('#paket_id').on('change', e => {
            for (let i = 0; i < pakets.length; i++) {
                if (pakets[i].id == $('#paket_id').val()) {
                    console.log(pakets[i].ket);
                    $('#paketKet').text(pakets[i].ket)
                    break;
                }
            }
        });
        $("input.d-none").on('change', function(e) {
            e.preventDefault();
            readURL(this);
        });


        navItems.forEach((navItem, i) => {
            navItem.addEventListener('click', () => {
                setMainPanel(i)
            });
        });

        // end event listener
    </script>
@endpush
