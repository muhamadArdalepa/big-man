@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@include('components.dataTables')
@include('components.select2css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@include('components.select2css')

<style>
    .img-container {
        background-size: cover;
        min-height: 150px;
        max-height: 300px;
    }

    #map>div.leaflet-pane.leaflet-map-pane>div.leaflet-pane.leaflet-popup-pane>div>div.leaflet-popup-content-wrapper>div {
        width: 300px !important;
    }
</style>
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pemasangan'])
    <div class="container-fluid px-0 px-sm-4 py-sm-4">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex gap-3">
                    <div class="d-flex align-items-center">
                        <label for="wilayah" class="m-0 d-none d-sm-inline-block">Wilayah</label>
                        <select class="form-control m-0 ms-sm-2" id="wilayah">
                            <option value="">Semua Wilayah</option>
                            @foreach ($wilayahs as $wilayah)
                                <option value="{{ $wilayah->id }}"
                                    {{ auth()->user()->wilayah_id == $wilayah->id ? 'selected' : '' }}>
                                    {{ $wilayah->nama_wilayah }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <label for="tanggal" class="m-0 d-none d-sm-inline-block">Tanggal</label>
                        <select name="tanggal" id="tanggal">
                            <option value="{{date('Y-m-d')}}">{{date('Y-m-d')}}</option>
                        </select>
                    </div>

                    <button id="btn-add" class="btn btn-icon bg-gradient-danger m-0 ms-auto" type="button"
                        data-bs-toggle="modal" data-bs-target="#Modal" data-bs-title="Tambah Laporan">
                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                        <span class="btn-inner--text d-none d-sm-inline-block">Pemasangan Baru</span>
                    </button>

                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">
                                    #</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Pelanggan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Marketer</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Alamat</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Waktu<br>Daftar</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                </th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('modal')
    <!-- modal -->
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-md-down modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="">
                        <h5 class="modal-title" id="ModalLabel"></h5>
                        <span id="ModalStatus" class="badge detail-item"></span>
                    </div>
                    <div class="dropdown detail-item">
                        <button class="btn btn-link text-secondary mb-0" data-bs-toggle="dropdown" id="dropdownButton"
                            aria-expanded="false">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu  dropdown-menu-end  p-2 me-sm-n3" aria-labelledby="dropdownButton">

                            {{-- <li>
                                <a class="dropdown-item btn-selesai border-radius-md py-1 " href="javascript:;">
                                    <i class="fa-solid fa-check me-2 text-success"></i>
                                    Terima dan tugaskan perbaikan

                                </a>
                            </li> --}}
                            <li>
                                <a id="edit" class="dropdown-item btn-tunda border-radius-md py-1 "
                                    href="javascript:;">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Edit
                                </a>
                            </li>
                            <li>
                                <a id="hapus" class="dropdown-item border-radius-md py-1 " href="javascript:;">
                                    <i class="fa-solid fa-xmark"></i>
                                    Hapus
                                </a>
                            </li>


                        </ul>
                    </div>

                </div>
                <div class="modal-body">
                    <div class="text-xs mb-1 detail-item">
                        <i class="fa-solid me-2 fa-calendar-plus"></i>
                        <span id="created_at"></span>
                        <span id="marketer"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="wilayah_id">Wilayah</label>
                                <select class="form-select" id="wilayah_id" tabindex="1">
                                    @foreach ($wilayahs as $wilayah)
                                        <option value="{{ $wilayah->id }}">{{ $wilayah->nama_wilayah }}</option>
                                    @endforeach
                                </select>
                                <div id="wilayah_idFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="nama">Nama Pelanggan</label>
                                <input type="text" id="nama" class="form-control" placeholder="Nama Pelanggan">
                                <div id="namaFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nik" class="form-control-label">NIK</label>
                                <input type="number" class="form-control" id="nik" placeholder="NIK"
                                    tabindex="4">
                                <div id="nikFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_telp" class="form-control-label">No Telepon</label>
                                <input type="number" class="form-control" id="no_telp" placeholder="No Telepon"
                                    tabindex="4">
                                <div id="no_telpFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control" placeholder="Email Pelanggan">
                        <div id="emailFeedback" class="invalid-feedback text-xs"></div>
                    </div>
                    <div class="form-group">
                        <label for="paket_id" class="form-control-label">Pilih Paket</label>
                        <select class="form-select" id="paket_id">
                            <option selected disabled value="">--Pilih Paket--</option>
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
                        <div class="d-flex align-items-end">
                            <label for="alamat" class="form-control-label">Alamat</label>
                            <button id="btn-open-map" class="btn ms-auto p-2 btn-link text-secondary font-weight-normal"
                                data-bs-toggle="modal" data-bs-target="#mapModal">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>Pilih Lewat Peta</span>
                            </button>
                        </div>
                        <textarea class="form-control" id="alamat" rows="3" placeholder="Alamat Pelanggan"></textarea>
                        <div id="alamatFeedback" class="invalid-feedback text-xs"></div>

                    </div>

                    <div class="form-group">
                        <label for="koordinat_rumah" class="form-control-label">Koordinat Rumah</label>
                        <input type="text" class="form-control" id="koordinat_rumah" placeholder="Koordinat Rumah"
                            tabindex="4">
                        <div id="koordinat_rumahFeedback" class="invalid-feedback text-xs"></div>
                    </div>




                    <div class="form-group">
                        <label for="example-text-input" class="form-control-label">Password Akun Pelanggan</label>
                        <div class="row">
                            <div class="input-group">
                                <input type="text" class="form-control" id="password" tabindex="3"
                                    placeholder="Password Akun Pelanggan">
                                <button type="button" id="copy-btn" class="btn m-0 btn-primary"
                                    data-bs-toggle="tooltip">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button type="button" id="generate-btn" class="btn m-0 btn-warning">Generate</button>
                            </div>
                            <div id="passwordFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                    </div>

                    <div class="row ">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto_ktp" class="form-control-label">Foto KTP</label>
                                <input type="file" id="foto_ktp" class="d-none">
                                <div class="rounded-3 border overflow-hidden position-relative img-container"
                                    style="cursor: pointer;" onclick="document.getElementById('foto_ktp').click()">
                                    <img src="" class="img-preview w-100 d-none">
                                    <div class="text-center top-50 start-50 position-absolute img-placeholder"
                                        style="transform: translate(-50%, -50%);">
                                        <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                        <div class="text-sm opacity-7 mt-3">Upload Foto</div>
                                    </div>
                                </div>
                                <div id="kfoto_ktpFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto_rumah" class="form-control-label">Foto KTP</label>
                                <input type="file" id="foto_rumah" class="d-none">
                                <div class="rounded-3 border overflow-hidden position-relative img-container"
                                    style="cursor: pointer;" onclick="document.getElementById('foto_rumah').click()">
                                    <img src="" class="img-preview w-100 d-none">
                                    <div class="text-center top-50 start-50 position-absolute img-placeholder"
                                        style="transform: translate(-50%, -50%);">
                                        <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                        <div class="text-sm opacity-7 mt-3">Upload Foto</div>
                                    </div>
                                </div>
                                <div id="foto_rumahFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                    </div>




                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary me-2" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="simpan">Tambah</button>
                    <button type="button" class="btn bg-gradient-primary" id="edit-perform">Simpan</button>
                </div>
            </div>
        </div>
    </div>

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
                            data-bs-target="#Modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // init
        const appUrl = "{{ env('APP_URL') }}";
        const baseUrl = 'api/pemasangan'
        const pakets = @json($pakets);

        let koordinat_rumah = null
        let alamat = null
        // functions

        let wilayah = $('#wilayah').val();
        let tanggal = $('#tanggal').val();
        let table = $('#table');

        let url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`
        let pemasanganId = null
        isDetail = false;
        $(document).ready(function() {
            $('#pelanggan').val(null).trigger('change');
            table = $('#table').DataTable({
                ajax: {
                    url: url,
                    type: 'GET',
                    dataSrc: '',
                },
                dom: "<'d-flex flex-column flex-md-row gap-3 align-items-center '<'d-flex align-items-center w-100 w-sm-auto'<'whitespace-nowrap'B><'ms-sm-3 ms-auto'l>><'ms-0 ms-md-auto'f>>" +
                    "<'row'<'col-sm-12't>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [
                    'excel', 'pdf'
                ],
                order: [
                    [0, "desc"]
                ],
                columns: [

                    {
                        data: 'id',
                        className: 'text-center',
                    },
                    {
                        data: 'pelanggan',
                    },
                    {
                        data: 'marketer',
                    },
                    {
                        data: 'alamat',
                        className: 'text-sm',
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function(data, type) {
                            if (type === 'display') {
                                let badge = null
                                switch (data) {
                                    case 'menunggu konfirmasi':
                                        badge = 'bg-secondary'
                                        break;
                                    case 'ditolak':
                                        badge = 'bg-gradient-danger'
                                        break;
                                    case 'sedang diproses':
                                        badge = 'bg-gradient-primary'
                                        break;
                                    case 'ditunda':
                                        badge = 'bg-gradient-warning'
                                        break;
                                    case 'aktif':
                                        badge = 'bg-gradient-success'
                                        break;
                                    case 'isolir':
                                        badge = 'bg-gradient-dark'
                                        break;
                                    case 'tidak aktif':
                                        badge = 'bg-gradient-dark'
                                        break;
                                    default:
                                        break;
                                }
                                return `
                                <span class="badge badge-sm text-xxs ${badge}">${data}</span>
                                `
                            }
                            return data
                        }
                    },
                    {
                        data: 'created_at',
                        className: 'text-center text-xs',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return row.created_atFormat;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type) {
                            if (type === 'display') {
                                return `<a href="${appUrl}/pemasangan/${data}" class="btn-detail-pemasangan btn btn-link text-secondary font-weight-normal">
                                        Detail
                                    </a>`
                            }
                            return data;
                        }
                    },
                ],
                language: {
                    oPaginate: {
                        sNext: '<i class="fa fa-forward"></i>',
                        sPrevious: '<i class="fa fa-backward"></i>',
                        sFirst: '<i class="fa fa-step-backward"></i>',
                        sLast: '<i class="fa fa-step-forward"></i>'
                    }
                },
                createdRow: function(row) {
                    let cell = $('td:eq(3)', row);
                    cell.addClass('force-wrap-space');
                    cell.addClass('extend-min-width');
                },
            });

            $('#wilayah').on('change', function() {
                wilayah = $('#wilayah').val()
                tanggal = $('#tanggal').val()
                url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`
                table.ajax.url(url).load()
                wilayahModal = wilayah
            });

            $('#tanggal').on('change', function() {
                wilayah = $('#wilayah').val()
                tanggal = $('#tanggal').val()
                url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`
                table.ajax.url(url).load()
                wilayahModal = wilayah
            });


            table.on('draw', () => {
                $('.btn-detail-pemasangan').on('click', (e) => {
                    isDetail = true;
                    pemasanganId = e.currentTarget.dataset.pemasangan
                    $('.detail-item').removeClass('d-none')
                    $('#simpan').addClass('d-none')
                    $('#edit-perform').removeClass('d-none')
                    $('#Modal .form-control').attr('disabled', 'true')
                    $('#Modal .form-select').attr('disabled', 'true')
                    $('#btn-open-map span').text('Lihat peta')
                    $('#btn-open-map i').removeClass('fa-location-dot')
                    $('#btn-open-map i').addClass('fa-map')
                    fetch(`${baseUrl}/${pemasanganId}`)
                        .then(response => response.json())
                        .then(data => {
                            $('#ModalLabel').text('Detail Pemasangan ' + data.id);
                            $('#ModalStatus').text(data.status);
                            var badge = null
                            switch (data.status) {
                                case 'menunggu konfirmasi':
                                    badge = 'bg-secondary'
                                    break;
                                case 'ditolak':
                                    badge = 'bg-gradient-danger'
                                    break;
                                case 'sedang diproses':
                                    badge = 'bg-gradient-primary'
                                    break;
                                case 'ditunda':
                                    badge = 'bg-gradient-warning'
                                    break;
                                case 'aktif':
                                    badge = 'bg-gradient-success'
                                    break;
                                case 'isolir':
                                    badge = 'bg-secondary'
                                    break;
                                case 'tidak aktif':
                                    badge = 'bg-dark text-light'
                                    break;
                            }
                            if (data.marketer) {
                                $('#marketer').text(' - Marketer: ' + data.marketer.nama)
                            } else {
                                $('#marketer').text('')
                            }
                            $('#ModalStatus').addClass(badge);
                            $('#created_at').text(data.created_atFormat);
                            $('#wilayah_id').val(data.pelanggan.wilayah_id);
                            $('#nama').val(data.pelanggan.nama);
                            $('#nik').val(data.nik);
                            $('#paket_id').val(data.paket_id);
                            $('#no_telp').val(data.pelanggan.no_telp);
                            $('#email').val(data.pelanggan.email);
                            $('#alamat').val(data.alamat);
                            $('#koordinat_rumah').val(data.koordinat_rumah);
                            $('.form-group:has(#password)').addClass('d-none')
                            $('#foto_ktp + div img').removeClass('d-none');
                            $('#foto_ktp + div img').attr('src', appUrl + '/storage/private/' +
                                data.foto_ktp);
                            $('#foto_ktp + div div.img-placeholder').addClass('d-none');
                            $('#foto_rumah + div img').removeClass('d-none');
                            $('#foto_rumah + div img').attr('src', appUrl +
                                '/storage/private/' + data.foto_rumah);
                            $('#foto_rumah + div div.img-placeholder').addClass('d-none');
                            koordinat_rumah = data.koordinat_rumah
                            alamat = data.alamat
                            $('#Modal').modal('show');
                        })
                });
            });

        });

        $('#simpan').on('click', function(e) {
            axios.post(baseUrl, {
                    wilayah_id: $('#wilayah_id').val(),
                    nama: $('#nama').val(),
                    nik: $('#nik').val(),
                    paket_id: $('#paket_id').val(),
                    no_telp: $('#no_telp').val(),
                    email: $('#email').val(),
                    alamat: $('#alamat').val(),
                    koordinat_rumah: $('#koordinat_rumah').val(),
                    password: $('#password').val(),
                    foto_ktp: $('#foto_ktp')[0].files[0],
                    foto_rumah: $('#foto_rumah')[0].files[0],
                }, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    console.log(response);
                    $('#jenis_gangguan_id').val(null)
                    $('#pelapor').val(null)
                    $('#ket').val('')
                    table.ajax.reload();
                    $('#Modal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false,
                    });
                })
                .catch(error => {
                    $('#Modal').find('.invalid-feedback').text('')
                    $('#Modal').find('.form-control').removeClass('is-invalid')
                    $('#Modal').find('.form-select').removeClass('is-invalid')
                    var errors = error.response.data.errors;
                    for (const key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            $('#' + key).addClass('is-invalid');
                            $('#' + key + 'Feedback').show();
                            $('#' + key + 'Feedback').text(errors[key]);
                        }
                    }
                })
        })
        $('#hapus').on('click', function(e) {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus data pemasangan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(baseUrl + '/' + pemasanganId)
                        .then(function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.data.message,
                                timer: 1500,
                                showConfirmButton: false,
                            });
                            $('#Modal').modal('hide');
                            table.ajax.reload();
                        })
                        .catch(function(error) {
                            Swal.fire('Error', error.response.data.message, 'error');
                        });
                }
            });
        });
        $('#btn-add').on('click', () => {
            isDetail = false;
            $('#Modal .form-control').val(null);
            $('#Modal .form-control:not(.form-control.disabled)').removeAttr('disabled');
            $('#Modal .form-select:not(#wilayah_id)').val(null);
            $('#Modal .form-select:not(.form-select.disabled)').removeAttr('disabled');
            $('#Modal').find('.invalid-feedback').text('')
            $('#Modal').find('.form-control').removeClass('is-invalid')
            $('#Modal').find('.form-select').removeClass('is-invalid')
            $('#Modal').find('img').addClass('d-none')
            $('#Modal').find('.img-placeholder').removeClass('d-none')
            $('#btn-open-map span').text('Pilih lewat peta')
            $('#btn-open-map i').removeClass('fa-map')
            $('#btn-open-map i').addClass('fa-location-dot')
            $('.detail-item').addClass('d-none')
            $('#edit-perform').addClass('d-none')
            $('#ModalLabel').text('Tambah Pemasangan');
            $('#wilayah_id').val(wilayah);
            $('#paket_id').val('');
            $('#paketKet').text('');
            $('.form-group:has(#password)').removeClass('d-none')
            const copyBtn = document.getElementById("copy-btn");
            const tooltip = new bootstrap.Tooltip(copyBtn);
            copyBtn.setAttribute("data-bs-original-title", "Copy Password")
            copyBtn.addEventListener("click", function() {
                var passwordInput = document.getElementById("password");
                passwordInput.select();
                document.execCommand("copy");
                copyBtn.setAttribute("data-bs-original-title", "Password Copied")
                tooltip.show()
            })
            copyBtn.addEventListener("mouseout", function() {
                copyBtn.setAttribute("data-bs-original-title", "Copy Password")
            })

            generateRandomPassword()
            koordinat_rumah = null
            alamat = null
            $('#simpan').removeClass('d-none')
        })

        $('#Modal').on('shown.bs.modal', function() {
            $(this).on('keypress', function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    $('#simpan').click()
                }
            });
        });

        $('#nama').on('blur', e => {
            if (e.target.value && e.target.value != '') {
                fetch(baseUrl + '/data-pelanggan?wilayah=' + $('#wilayah_id').val() + '&nama=' + $('#nama').val() +
                        '%')
                    .then(response => response.json())
                    .then(data => {
                        if (data.nama) {
                            $('#nama').val(data.nama)
                            $('#no_telp').val(data.no_telp)
                            $('#email').val(data.email)
                            $('#alamat').val(data.alamat)
                            $('#koordinat_rumah').val(data.koordinat_rumah)
                            alamat = data.alamat
                            koordinat_rumah = data.koordinat_rumah
                        }
                    })
            }
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



        let map = null;

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
                    let button = isDetail ? '' :
                        '<button class="btn btn-primary btn-pilih-lokasi w-100">Pilih Lokasi</button>';
                    marker.bindPopup(`
                <div class="mb-3 w-auto">${address}</div>
                ${button}
                `).openPopup();
                    $('.btn-pilih-lokasi').on('click', () => {
                        $('#alamat').val(address);
                        $('#alamat').text(address);
                        $('#koordinat_rumah').val(lat + ',' + long);
                        $('#mapModal').modal('hide');
                        $('#Modal').modal('show');
                        alamat = address
                        koordinat_rumah = lat + ',' + long
                    });
                });
            } else {
                let button = isDetail ? '' :
                    '<button class="btn btn-primary btn-pilih-lokasi w-100">Pilih Lokasi</button>';
                const marker = L.marker([lat, long]).addTo(map);
                marker.bindPopup(`
                <div class="mb-3 w-auto">${alamat}</div>
                ${button}
                `).openPopup();
                $('.btn-pilih-lokasi').on('click', () => {
                    $('#alamat').val(alamat);
                    $('#alamat').text(alamat);
                    $('#koordinat_rumah').val(lat + ',' + long);
                    $('#mapModal').modal('hide');
                    $('#Modal').modal('show');
                    alamat = address
                    koordinat_rumah = lat + ',' + long
                });
            }

            if (!isDetail) {
                let button = isDetail ? '' :
                    '<button class="btn btn-primary btn-pilih-lokasi w-100">Pilih Lokasi</button>';
                map.on('click', async e => {
                    popup
                        .setLatLng(e.latlng)
                        .setContent(`
                <div class="text-center">
                    <i class="fa-solid fa-spinner fa-spin me-1"></i>
                </div>
            `).openOn(map);
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
                        $('#Modal').modal('show');
                        alamat = address
                        koordinat_rumah = e.latlng.lat + ',' + e.latlng.lng
                    });
                });
            }


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
        $('#generate-btn').on('click', e => {
            e.preventDefault()
            generateRandomPassword()
        })

        function generateRandomPassword() {
            var length = 6;
            var charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            var password = "";

            for (var i = 0; i < length; i++) {
                var randomIndex = Math.floor(Math.random() * charset.length);
                password += charset.charAt(randomIndex);
            }
            $('#password').val(password);
        }

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
        $('input[type="file"]').on('change', function(e) {
            e.preventDefault();
            readURL(this);
        });




        // end event listners
    </script>
@endpush
