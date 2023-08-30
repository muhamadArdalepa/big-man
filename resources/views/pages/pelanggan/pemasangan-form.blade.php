@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pendaftaran Jaringan Bignet'])
    @push('css')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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
                            <div class="d-flex align-items-end">
                                <label for="alamat" class="form-control-label">Alamat</label>
                                <button class="btn ms-auto btn-link text-secondary font-weight-normal"
                                    data-bs-toggle="modal" data-bs-target="#mapModal">
                                    <i class="fas fa-location-dot"></i>
                                    Pilih Lewat Peta
                                </button>
                            </div>
                            <textarea type="text" id="alamat" class="form-control" placeholder="Alamat"></textarea>

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
@endsection
@push('modal')
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-body h-100">
                    <div id="map-container" class="h-100">
                        <div id="map"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#mapModal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('js')
    <script>
        // init
        const appUrl = "{{ env('APP_URL') }}";
        const baseUrl = appUrl + '/api/pelanggan-pemasangan'
        const pakets = @json($pakets);
        let map = null;

        let koordinat_rumah = null
        let alamat = null
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


        function getAddressFromCoordinatesMap(position) {
            var lat = position.coords.latitude;
            var long = position.coords.longitude;
            setupMap(lat, long)
        }

        function setupMap(lat, long, alm = null) {
            if (map != null) {
                map.remove()
            }
            map = L.map('map').setView([lat, long], 16);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 20,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            var popup = L.popup();
            var address;

            let getDisplayAddress = async (lat, lng) => {
                try {
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
                    );
                    const data = await response.json();
                    return data.display_name;
                } catch (error) {
                    console.error('Error:', error);
                    $('#mapModal').find('.leaflet-popup-content .text-center').html(
                        'Gagal mendapatkan alamat'
                    );
                }
            };

            if (!alm) {
                getDisplayAddress(lat, long).then(address => {
                    const marker = L.marker([lat, long]).addTo(map);
                    let button = '<button class="btn btn-primary btn-pilih-lokasi w-100">Pilih Lokasi</button>';
                    marker.bindPopup(`<div class="mb-3 w-auto">${address}</div>${button}`).openPopup();
                    $('.btn-pilih-lokasi').on('click', () => {
                        $('#alamat').val(address);
                        $('#alamat').text(address);
                        $('#koordinat_rumah').val(lat + ',' + long);
                        $('#mapModal').modal('hide');
                        alamat = address
                        koordinat_rumah = lat + ',' + long
                    });
                });
            } else {
                let button = '<button class="btn btn-primary btn-pilih-lokasi w-100">Pilih Lokasi</button>';
                const marker = L.marker([lat, long]).addTo(map);
                marker.bindPopup(`<div class="mb-3 w-auto">${alamat}</div>${button}`).openPopup();
                $('.btn-pilih-lokasi').on('click', () => {
                    $('#alamat').val(alamat);
                    $('#alamat').text(alamat);
                    $('#koordinat_rumah').val(lat + ',' + long);
                    $('#mapModal').modal('hide');
                    alamat = address
                    koordinat_rumah = lat + ',' + long
                });
            }
            let button = '<button class="btn btn-primary btn-pilih-lokasi w-100">Pilih Lokasi</button>';
            map.on('click', async e => {
                popup
                    .setLatLng(e.latlng)
                    .setContent(`<div class="text-center"><i class="fa-solid fa-spinner fa-spin me-1"></i></div>`).openOn(map);
                const address = await getDisplayAddress(e.latlng.lat, e.latlng.lng);
                $('#mapModal').find('.leaflet-popup-content').html(`
                    <div class="mb-3 w-auto">${address}</div>
                    ${button}
                `);
                $('.btn-pilih-lokasi').on('click', () => {
                    $('#alamat').val(address);
                    $('#alamat').text(address);
                    $('#koordinat_rumah').val(e.latlng.lat + ',' + e.latlng.lng);
                    $('#mapModal').modal('hide');
                    alamat = address
                    koordinat_rumah = e.latlng.lat + ',' + e.latlng.lng
                });
            });



        }

        $('#mapModal').on('shown.bs.modal', e => {
            mapHeight = document.getElementById('map-container').offsetHeight
            $('#map').css('height', mapHeight)
            if (koordinat_rumah != null && alamat != null) {
                var parts = koordinat_rumah.split(',');
                var lat = parseFloat(parts[0]);
                var long = parseFloat(parts[1]);
                setupMap(lat, long, alamat)
            } else if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(getAddressFromCoordinatesMap, errGeolokasi);
            } else {
                setupMap(-0.0277, 109.3425)
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
