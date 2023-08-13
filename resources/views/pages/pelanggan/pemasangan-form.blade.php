@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pendaftaran Jaringan Bignet'])
    @push('css')
        <style>
            .img-container {
                min-height: 100px;
                max-height: 300px;
            }

            .is-invalid-image {
                border-color: #fd5c70 !important;
            }
        </style>
    @endpush
    <div class="container-fluid py-4">
        <div class="card d-block" id="mainPanel">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 px-md-4">
                        <p class="text-uppercase text-sm">Informasi Pemasangan</p>
                        <div class="form-group">
                            <label for="paket_id" class="form-control-label">Pilih Paket</label>
                            <select class="form-select" id="paket_id">
                                <option selected disabled>--Pilih Paket--</option>
                                @foreach ($pakets as $paket)
                                    <option value="{{ $paket->id }}">
                                        {{ $paket->nama_paket }} -- Rp.
                                        {{ $paket->harga }}/{{ $paket->kecepatan }}Mbps
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-sm text-muted" id="paketKet"></div>
                            <div id="paket_idFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                        <div class="form-group">
                            <label for="nik" class="form-control-label">NIK</label>
                            <input type="number" id="nik" class="form-control" />
                            <div id="nikFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                        <div class="form-group">
                            <label for="alamat" class="form-control-label">Alamat</label>

                            <button class="btn btn-link text-secondary font-weight-normal ">
                                <i class="fas fa-location-dot"></i>
                                Pilih Lewat Peta
                            </button>
                            <textarea id="alamat" rows="3" class="form-control"></textarea>
                            <div id="alamatFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                        <div class="form-group">
                            <label for="koordinat_rumah" class="form-control-label">Koordinat Rumah</label>
                            <input type="text" id="koordinat_rumah" class="form-control" />
                            <div id="koordinat_rumahFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                    </div>
                    <div class="col-md-6 border-start px-md-4">
                        <p class="text-uppercase text-sm">Foto Pendukung</p>
                        <div class="form-group">
                            <label for="foto_ktp" class="form-control-label">Foto KTP</label>
                            <input type="file" id="foto_ktp" class="d-none" />
                            <div class="rounded-3 border overflow-hidden position-relative img-container"
                                style="cursor: pointer" onclick="document.getElementById('foto_ktp').click()">
                                <img src="" class="img-preview w-100">
                                <div class="text-center top-50 start-50 position-absolute img-placeholder"
                                    style="transform: translate(-50%, -50%)">
                                    <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                    <div class="text-sm opacity-7 mt-3">
                                        Upload Foto
                                    </div>
                                </div>
                            </div>
                            <div id="foto_ktpFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                        <div class="form-group">
                            <label for="foto_rumah" class="form-control-label">Foto Rumah</label>
                            <input type="file" id="foto_rumah" class="d-none" />
                            <div class="rounded-3 border overflow-hidden position-relative img-container"
                                style="cursor: pointer" onclick="document.getElementById('foto_rumah').click()"> <img
                                    src="" class="img-preview w-100" />
                                <div class="text-center top-50 start-50 position-absolute img-placeholder"
                                    style="transform: translate(-50%, -50%)">
                                    <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                    <div class="text-sm opacity-7 mt-3">
                                        Upload Foto
                                    </div>
                                </div>
                            </div>
                            <div id="foto_rumahFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                        <div class="form-group d-flex mb-0 pt-3">
                            <button class="ms-auto btn bg-gradient-primary" id="btnDaftar">
                                <i class="fa-solid fa-spinner fa-spin d-none me-1" id="daftarLoading"></i>Daftar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @include('layouts.footers.auth.footer')
    </div>
    @endsection @push('js')
    <script>
        // init
        const appUrl = "{{ env('APP_URL') }}";
        const baseUrl = appUrl + '/api/pelanggan-pemasangan'
        const pakets = @json($pakets);
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

        // end functions

        // event listener

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



        $('#btnDaftar').on('click', e => {
            document.getElementById('daftarLoading').classList.remove('d-none')
            let data = {
                paket_id: $('#paket_id').val(),
                nik: $('#nik').val(),
                alamat: $('#alamat').val(),
                koordinat_rumah: $('#koordinat_rumah').val(),
                foto_ktp: document.getElementById('foto_ktp').files[0],
                foto_rumah: document.getElementById('foto_rumah').files[0]
            }

            axios.post(baseUrl, data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    document.getElementById('daftarLoading').classList.add('d-none')
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.data.message,
                        timer: 1500,
                        showConfirmButton: false,
                    }).then(e => {
                        $('.card-body .row').remove()
                        $(".card-body").append(
                            `<div class="d-flex align-items-center">
                                <h5 class="card-title">Pendaftaran Berhasil</h5>
                                <span class="badge bg-secondary">Sedang Menunggu Konfirmasi</span>
                                <small class="opacity-7 ms-auto" id="redirecting">Mengalihkan ke detail pemasangan 3...</small>
                            </div>`
                        );
                        var timer = 2
                        setInterval(() => {
                            $('#redirecting').text('Mengalihkan ke detail pemasangan ' + (
                                timer--) + '...')
                        }, 1000);
                        setTimeout(() => {
                            location.reload();
                        }, 3000);
                    });
                })
                .catch(error => {
                    // if (error.data != undefined) {
                    $('.is-invalid').removeClass('is-invalid')
                    document.getElementById('daftarLoading').classList.add('d-none')
                    let errList = error.response.data.errors
                    for (const key in errList) {
                        if (errList.hasOwnProperty(key)) {
                            $('#' + key).addClass('is-invalid');
                            $('#' + key + ' + .img-container').addClass('is-invalid-image');
                            // $('#' + key + 'Feedback').show();
                            $('#' + key + 'Feedback').text(errList[key]);
                        }
                    }
                    // }
                });

        })


        // end event listener
    </script>
@endpush
