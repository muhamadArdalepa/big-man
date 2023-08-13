@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@include('components.dataTables')
@include('components.select2css')
<style>
    textarea {
        resize: none;
    }

    .img-container {
        min-height: 100;
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
                        <input type="date" id="tanggal" class="form-control ms-2" value="{{ $date }}">
                    </div>
                    <button class="btn btn-icon btn-3 btn-primary m-0 ms-auto" type="button" data-bs-toggle="modal"
                        data-bs-target="#Modal" data-bs-title="Tambah Laporan">
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
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Tambah Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <div class="d-flex align-items-center">
                                    <label for="no_telp" class="form-control-label">Kota</label>
                                </div>
                                <select class="form-control" id="wilayah_id" tabindex="5">
                                    @foreach ($wilayahs as $wilayah)
                                        <option value="{{ $wilayah->id }}">{{ $wilayah->nama_wilayah }}</option>
                                    @endforeach
                                </select>
                                <div id="wilayah_idFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <div class="d-flex align-items-center">
                                    <label for="nama" class="form-control-label">Nama Pelanggan</label>
                                    <div class="form-check form-switch ms-auto">
                                        <input class="form-check-input" type="checkbox" id="pelanggan-baru">
                                        <label class="form-check-label" for="pelanggan-baru">Pelanggan baru</label>
                                    </div>
                                </div>
                                <input type="text" id="nama" class="form-control" placeholder="Nama Pelanggan"
                                    autofocus tabindex="1">
                                <select id="pelanggan" class="form-control w-100">
                                    <option value=""></option>
                                </select>
                                <div id="namaFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="no_telp" class="form-control-label">No Telepon</label>
                                <input type="number" class="form-control" id="no_telp" placeholder="No Telepon"
                                    tabindex="4">
                                <div id="no_telpFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Email</label>
                                <input type="email" id="email" class="form-control" placeholder="Alamat email"
                                    tabindex="2">
                                <div id="emailFeedback" class="invalid-feedback text-xs"></div>
                            </div>

                        </div>
                    </div>



                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" rows="3" placeholder="Alamat"></textarea>
                        <div id="alamatFeedback" class="invalid-feedback text-xs"></div>
                    </div>

                    <div class="row">

                        <div class="col-6">
                            <div class="form-group">
                                <label for="serial_number">Serial Number</label>
                                <input type="text" id="serial_number" class="form-control"
                                    placeholder="Serial Number" autofocus tabindex="1">
                                <div id="serial_numberFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ket">Keterangan</label>
                        <textarea class="form-control" id="ket" rows="3" placeholder="Keterangan"></textarea>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary me-2" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="simpan">Tambah</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-lg-down modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header row">
                    <div class="col-md-6 d-flex gap-2 mb-3 mb-md-0">
                        <img src="/storage/private/profile/dummy.png" alt="foto profil" height="50" class="rounded-3"
                            id="ModalImg">
                        <div class="flex-grow-1">
                            <div class="text-sm mb-1 d-flex align-items-center w-100">
                                Detail Pemasangan &nbsp;
                                <span id="detailIdPemasangan"></span>
                                <span class="badge bg-gradient-success ms-auto ms-md-2" id="detailStatus"></span>
                            </div>
                            <h5 class="m-0 lh-1" id="detailModalLabel"></h5>
                        </div>
                    </div>
                    <div class="col-md-6 justify-content-end d-flex gap-2">
                        <div class="">
                            <small class="m-0 d-block" id="detailModalTime"></small>
                            <small class="m-0 d-block" id="detailModalDate"></small>
                        </div>
                        <button type="button" class="btn bg-gradient-danger ms-auto ms-md-0" data-bs-toggle="tooltip"
                            data-bs-title="Hapus Pemasangan">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <button type="button" class="btn bg-gradient-warning" data-bs-toggle="tooltip"
                            data-bs-title="Edit Pemasangan">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                    </div>

                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 px-md-4">
                            <p class="text-uppercase text-sm">Informasi Pemasangan</p>
                            <div class="form-group">
                                <label for="detailpaket_id" class="form-control-label">Paket Pilihan</label>
                                <select class="form-select" id="detailpaket_id">
                                    <option disabled value="0">--Pilih Paket--</option>
                                    @foreach ($pakets as $paket)
                                        <option value="{{ $paket->id }}">
                                            {{ $paket->nama_paket }} -- Rp.
                                            {{ $paket->harga }}/{{ $paket->kecepatan }}Mbps
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-sm text-muted" id="paketKet"></div>
                                <div id="detailpaket_idFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                            <div class="form-group">
                                <label for="detailnik" class="form-control-label">NIK</label>
                                <input type="number" id="detailnik" class="form-control">
                                <div class="text-sm text-muted" id="paketKet"></div>
                                <div id="detailnikFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                            <div class="form-group">
                                <label for="detailalamat" class="form-control-label">Alamat</label>
                                <textarea id="detailalamat" rows="3" class="form-control"></textarea>
                                <div class="text-sm text-muted" id="paketKet"></div>
                                <div id="detailalamatFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                            <div class="form-group">
                                <label for="detailkoordinat_rumah" class="form-control-label">Koordinat Rumah</label>
                                <input type="text" id="detailkoordinat_rumah" class="form-control">
                                <div id="detailkoordinatFeedback_rumah" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                        <div class="col-md-7 border-start px-md-4">
                            <p class="text-uppercase text-sm">Foto Pendukung</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="detailfoto_ktp" class="form-control-label">Foto KTP</label>
                                        <input type="file" id="detailfoto_ktp" class="d-none">
                                        <div class="rounded-3 border overflow-hidden position-relative img-container"
                                            style="cursor: pointer;">
                                            <img src="" class="img-preview w-100">
                                            <div class="d-none text-center top-50 start-50 position-absolute img-placeholder"
                                                style="transform: translate(-50%, -50%);">
                                                <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                                <div class="text-sm opacity-7 mt-3">Upload Foto</div>
                                            </div>
                                        </div>
                                        <div id="foto_ktpFeedback" class="invalid-feedback text-xs"></div>
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="detailfoto_rumah" class="form-control-label">Foto Rumah</label>
                                        <input type="file" id="detailfoto_rumah" class="d-none">
                                        <div class="rounded-3 border overflow-hidden position-relative img-container"
                                            style="cursor: pointer;">
                                            <img src="" class="img-preview w-100">
                                            <div class="d-none text-center top-50 start-50 position-absolute img-placeholder"
                                                style="transform: translate(-50%, -50%);">
                                                <i class="fa-regular fa-image fa-2xl mt-3"></i>
                                                <div class="text-sm opacity-7 mt-3">Upload Foto</div>
                                            </div>
                                        </div>
                                        <div id="foto_rumahFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="detailpoin">Level Pekerjaan</label>
                                        <select id="detailpoin" class="form-select">
                                            @foreach (\App\Models\Poin::all() as $poin)
                                                <option value="{{ $poin->poin }}">{{ $poin->kesulitan }}</option>
                                            @endforeach
                                        </select>
                                        <div id="detailpoinFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>
                                <div class="col-md-9 align-self-end">
                                    <div class="form-group w-100">
                                        <button type="button" class="btn bg-gradient-primary w-100"
                                            id="tugaskan">Tugaskan
                                            Tim
                                            Perbaikan</button>
                                        <select id="tim" class="form-control" data-bs-toggle="tooltip">
                                            <option value=""></option>
                                        </select>
                                        <div id="timFeedback" class="invalid-feedback text-xs"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary me-2" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="detailSimpan">Tugaskan</button>
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

            let wilayah = $('#wilayah').val();
            let wilayahModal = $('#wilayah_id').val();

            let wilayahSelected = null;
            let pemasanganSelected = null;
            let tanggal = $('#tanggal').val();

            let url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`

            let table = null;

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

            function templateSelection(data) {
                if (data.id === '') { // adjust for custom placeholder values
                    return $('<span style="color:#b3bbc2">Nama Pelanggan</span>');
                }

                return data.text;
            }

            function detailTemplateSelection(data) {
                if (data.id === '') {
                    return $('<span style="color:#b3bbc2">Pilih TIM</span>');
                }

                return data.text;
            }

            function initSelect2(wilayahModal) {
                $('#pelanggan').select2({
                    ajax: {
                        url: `api/select2-laporan-pelanggan?wilayah=${wilayahModal}`,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                terms: params.term,
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.results,
                            };
                        },
                        cache: true,
                    },
                    cache: true,
                    templateSelection: templateSelection,
                    width: '100%',
                    dropdownParent: $('#Modal'),
                    theme: 'bootstrap-5',
                });
            }

            function initDetailSelect2() {
                $('#tim').select2({
                    ajax: {
                        url: `api/select2-laporan-tim?wilayah=${wilayahSelected}`,
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                terms: params.term,
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.results,
                            };
                        },
                        cache: true,
                    },
                    cache: true,
                    placeholder: 'Pilih Tim',
                    allowClear: true,
                    templateSelection: detailTemplateSelection,
                    width: '100%',
                    dropdownParent: $('#detailModal'),
                    theme: 'bootstrap-5',
                });
            }
            // end functions

            // event listeners
            $("input.d-none").on('change', function(e) {
                e.preventDefault();
                readURL(this);
            });

            $(document).ready(function() {
                table = $('#table').DataTable({
                    ajax: {
                        url: url,
                        type: 'GET',
                        serverside: true,
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
                            className: 'extend-min-width',
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
                            data: 'created_atFormat',
                            className: 'text-center',
                        },
                        {
                            data: 'id',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            render: function(data, type) {
                                if (type === 'display') {
                                    return `
                                <button data-pemasangan="${data}" data-bs-toggle="modal" data-bs-target="#detailModal" class="btn btn-link text-secondary font-weight-normal btn-detail-pemasangan">
                                    Detail
                                </button>
                                `
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
                initSelect2(wilayahModal);
                initDetailSelect2(wilayahSelected);
                $('#tim').next().css('display', 'none');
                $('#tim').css('display', 'none');
                $('#nama').css('display', 'none');



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


                $('#wilayah_id').on('change', function() {
                    wilayahModal = $('#wilayah_id').val();
                    if (!isNewPelanggan) {
                        $('#pelanggan').select2('destroy');
                        $('#pelanggan').val(null).trigger('change');
                        initSelect2(wilayahModal)
                    }
                });

                table.on('draw', () => {
                    $('.btn-detail-pemasangan').on('click', (e) => {
                        let card;
                        fetch(`${baseUrl}/${e.target.dataset.pemasangan}`)
                            .then(response => response.json())
                            .then(data => {
                                wilayahSelected = data.pelanggan.wilayah_id;
                                pemasanganSelected = data.id;
                                $('#ModalImg').attr('src', appUrl + '/storage/private/profile/' +
                                    data.pelanggan.foto_profil)
                                $('#detailIdPemasangan').text(data.id)
                                $('#detailStatus').text(data.status)
                                $('#detailModalLabel').text(data.pelanggan.nama)
                                $('#detailModalTime').text(data.waktuFormat)
                                $('#detailModalDate').text(data.tanggalFormat)
                                $('#detailpaket_id').val(data.paket_id)
                                $('#detailnik').val(data.nik)
                                $('#detailalamat').text(data.alamat)
                                $('#detailalamat').val(data.alamat)
                                $('#detailkoordinat_rumah').val(data.koordinat_rumah)
                                $('#detailPaket').val(data.nama_paket)
                                $('#detailfoto_ktp + .img-container img').attr('src', appUrl +
                                    '/storage/private/pemasangan/' +
                                    data.foto_ktp)
                                $('#detailfoto_rumah + .img-container img').attr('src', appUrl +
                                    '/storage/private/pemasangan/' +
                                    data.foto_rumah)
                            })
                    });
                });
            });

            $('#pelanggan-baru').on('click', function() {
                isNewPelanggan = (!isNewPelanggan);
                if (isNewPelanggan) {
                    $('#nama').css('display', 'inline-block');
                    $('#pelanggan').next().css('display', 'none');
                } else {
                    $('#nama').css('display', 'none');
                    initSelect2(wilayahModal)
                    $('#pelanggan').next().css('display', 'inline-block');
                }
                $('#nama').val('');
                $('#pelanggan').val('').trigger('change');
            })

            $('#pelanggan').on('change', function() {
                $('#nama').val('')
                $('#no_telp').val('')
                $('#email').val('')
                $('#serial_number').val('')
                $('#alamat').val('')
                if ($('#pelanggan').val() != '') {
                    let pelangganId = $('#pelanggan').val();
                    if (pelangganId != null) {
                        $.ajax({
                            url: appUrl + '/api/data-laporan-pelanggan/' + pelangganId,
                            type: 'GET',
                            data: {
                                id: pelangganId,
                            },
                            success: function(response) {
                                $('#nama').val(response.nama)
                                $('#no_telp').val(response.no_telp)
                                $('#email').val(response.email)
                                $('#serial_number').val(response.serial_number)
                                $('#alamat').val(response.alamat)
                                $('#nama').removeClass('is-invalid');
                                $('#namaFeedback').hide();
                                $('#email').removeClass('is-invalid');
                                $('#emailFeedback').hide();
                                $('#serial_number').removeClass('is-invalid');
                                $('#serial_numberFeedback').hide();
                                $('#no_telp').removeClass('is-invalid');
                                $('#no_telpFeedback').hide();
                                $('#alamat').removeClass('is-invalid');
                                $('#alamatFeedback').hide();
                                $('#wilayah_id').removeClass('is-invalid');
                                $('#wilayah_idFeedback').hide();
                            }
                        })
                    }
                }

            })

            $('#tim').on('change', () => {
                if ($('#tim').val() == '') {
                    $('#detailSimpan').hide();
                } else {
                    $('#detailSimpan').show();
                }
            })

            $('#Modal').on('shown.bs.modal', function() {
                $(this).find('[autofocus]').focus();
                $(this).on('keypress', function(event) {
                    if (event.keyCode === 13) {
                        event.preventDefault();
                        $('#simpan').click()
                    }
                });
            });

            $('#Modal').on('hide.bs.modal', function() {
                $('#Modal').find('.form-control').removeClass('is-invalid');
                $('#Modal').find('.invalidFeedback').hide();
                $('#Modal').find('input.form-control').val('');
                $('#Modal').find('textarea.form-control').val('');
                $('#Modal').find('textarea.form-control').text('');
                $('#wilayah_id').val(wilayah);
                $('#jenis_gangguan_id').val(0);
            });

            $('#detailModal').on('shown.bs.modal', function() {
                $(this).on('keypress', function(event) {
                    if (event.keyCode === 13) {
                        event.preventDefault();
                        $('#detailSimpan').click()
                    }
                });
            });

            $('#detailModal').on('hide.bs.modal', function() {
                $('#detailModal').find('.form-control').removeClass('is-invalid');
                $('#detailModal').find('.invalidFeedback').hide();
                $('#detailModal').find('.form-control').val('');
                $('#tim').next().css('display', 'none');
                $('#tugaskan').css('display', 'block');
            });

            $('#tugaskan').on('click', () => {
                $('#tugaskan').hide()
                initDetailSelect2(wilayahSelected);
                $('#tim').show()
            })

            $('#simpan').on('click', function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: appUrl + '/api/laporan',
                    type: 'POST',
                    data: {
                        is_new: isNewPelanggan,
                        id: $('#pelanggan').val(),
                        nama: $('#nama').val(),
                        email: $('#email').val(),
                        password: $('#password').val(),
                        no_telp: $('#no_telp').val(),
                        wilayah_id: $('#wilayah_id').val(),
                        alamat: $('#alamat').val(),
                        serial_number: $('#serial_number').val(),
                        jenis_gangguan_id: $('#jenis_gangguan_id').val(),
                        ket: $('#ket').val(),
                    },
                    success: function(response) {
                        if (response.status == 400) {
                            console.log(response);
                            if (response.errors['nama'] == undefined) {
                                $('#nama').removeClass('is-invalid');
                                $('#namaFeedback').hide();
                            } else {
                                $('#namaFeedback').text(response.errors['nama']);
                                $('#nama').addClass('is-invalid');
                            }
                            if (response.errors['email'] == undefined) {
                                $('#email').removeClass('is-invalid');
                                $('#emailFeedback').hide();
                            } else {
                                $('#emailFeedback').text(response.errors['email']);
                                $('#email').addClass('is-invalid');
                            }
                            if (response.errors['no_telp'] == undefined) {
                                $('#no_telp').removeClass('is-invalid');
                                $('#no_telpFeedback').hide();
                            } else {
                                $('#no_telpFeedback').text(response.errors['no_telp']);
                                $('#no_telp').addClass('is-invalid');
                            }
                            if (response.errors['alamat'] == undefined) {
                                $('#alamat').removeClass('is-invalid');
                                $('#alamatFeedback').hide();
                            } else {
                                $('#alamatFeedback').text(response.errors['alamat']);
                                $('#alamat').addClass('is-invalid');
                            }
                            if (response.errors['serial_number'] == undefined) {
                                $('#serial_number').removeClass('is-invalid');
                                $('#serial_numberFeedback').hide();
                            } else {
                                $('#serial_numberFeedback').text(response.errors['serial_number']);
                                $('#serial_number').addClass('is-invalid');
                            }
                            if (response.errors['wilayah_id'] == undefined) {
                                $('#wilayah_id').removeClass('is-invalid');
                                $('#wilayah_idFeedback').hide();
                            } else {
                                $('#wilayah_idFeedback').text(response.errors['wilayah_id']);
                                $('#wilayah_id').addClass('is-invalid');
                            }
                            if (response.errors['jenis_gangguan_id'] == undefined) {
                                $('#jenis_gangguan_id').removeClass('is-invalid');
                                $('#jenis_gangguan_idFeedback').hide();
                            } else {
                                $('#jenis_gangguan_idFeedback').text(response.errors['jenis_gangguan_id']);
                                $('#jenis_gangguan_id').addClass('is-invalid');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false,
                            });
                            $('#Modal').find('.form-control').val('');
                            $('#Modal').find('.form-control').removeClass('is-invalid');
                            $('#Modal').find('.invalidFeedback').hide();
                            $('#Modal').modal('hide');
                            table.ajax.reload();
                        }
                    }
                });
            });


            $('#detailSimpan').on('click', function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: appUrl + '/api/pekerjaan',
                    type: 'POST',
                    data: {
                        jenis_pekerjaan_id: 1,
                        tim_id: $('#tim').val(),
                        wilayah_id: wilayahSelected,
                        pemasangan_id: pemasanganSelected,
                        poin: $('#detailpoin').val()
                    },
                    success: function(response) {
                        if (response.status == 400) {
                            console.log(response);
                            if (response.errors['tim_id'] == undefined) {
                                $('#tim').removeClass('is-invalid');
                                $('#timFeedback').hide();
                            } else {
                                $('#timFeedback').text(response.errors['tim']);
                                $('#tim').addClass('is-invalid');
                            }
                            if (response.errors['poin'] == undefined) {
                                $('#detailpoin').removeClass('is-invalid');
                                $('#detailpoinFeedback').hide();
                            } else {
                                $('#detailpoinFeedback').text(response.errors['poin']);
                                $('#detailpoin').addClass('is-invalid');
                            }
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false,
                            });
                            $('#detailModal').find('.form-control').val('');
                            $('#detailModal').find('.form-control').removeClass('is-invalid');
                            $('#detailModal').find('.invalidFeedback').hide();
                            $('#detailModal').modal('hide');
                            table.ajax.reload();
                        }
                    }
                });
            });




            // end event listners
        </script>
    @endpush
