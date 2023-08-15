@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('css')
    @include('components.select2css')
@endpush

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Laporan'])
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
                        <span class="btn-inner--text d-none d-sm-inline-block">Tambah Laporan</span>
                    </button>

                </div>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    #</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Pelapor</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Penerima</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Jenis Gangguan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Waktu Lapor</th>
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
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-md-down modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Tambah Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="no_telp" class="form-control-label">Wilayah</label>
                                <select class="form-control" id="wilayah_id" tabindex="1">
                                    @foreach ($wilayahs as $wilayah)
                                        <option value="{{ $wilayah->id }}">{{ $wilayah->nama_wilayah }}</option>
                                    @endforeach
                                </select>
                                <div id="wilayah_idFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>

                        <div class="col-md-8">
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
                                <label for="jenis_gangguan_id">Jenis Gangguan</label>
                                <select id="jenis_gangguan_id" class="form-control">
                                    <option selected disabled value="0">-- Jenis Gangguan --</option>
                                    @foreach ($jenis_gangguans as $jg)
                                        <option value="{{ $jg->id }}">{{ $jg->nama_gangguan }}</option>
                                    @endforeach
                                </select>
                                <div id="jenis_gangguan_idFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
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

    {{-- <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-lg-down modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header gap-2">
                    <img src="/storage/private/profile/dummy.png" alt="foto profil" height="50" class="rounded-3"
                        id="ModalImg">
                    <div class="d-flex flex-column justify-content-end">
                        <small class="m-0">Detail Laporan <span id="detailIdLaporan"></span><span
                                class="badge bg-gradient-success ms-2" id="detailStatus">Pending</span></small>
                        <h5 class="m-0 lh-1" id="detailModalLabel"></h5>
                    </div>
                    <div class="ms-auto text-end">
                        <small class="m-0 d-block" id="detailModalTime"></small>
                        <small class="m-0 d-block" id="detailModalDate"></small>
                    </div>
                    <button type="button" class="btn bg-gradient-danger" data-bs-toggle="tooltip"
                        data-bs-title="Hapus Laporan">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <button type="button" class="btn bg-gradient-warning" data-bs-toggle="tooltip"
                        data-bs-title="Hapus Laporan">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="detailSerial_number">Serial Number</label>
                                    <input type="text" id="detailSerial_number" class="form-control"
                                        placeholder="Serial Number" autofocus tabindex="1" disabled>
                                    <div id="detailSerial_numberFeedback" class="invalid-feedback text-xs"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="detailPaket">Paket Terpilih</label>
                                    <input type="text" id="detailPaket" class="form-control"
                                        placeholder="Serial Number" tabindex="2" disabled>
                                    <div id="detailPaketFeedback" class="invalid-feedback text-xs"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="detailJenis_gangguan">Jenis Gangguan</label>
                                    <select id="detailJenis_gangguan" class="form-control" tabindex="3" disabled>
                                        @foreach ($jenis_gangguans as $jg)
                                            <option value="{{ $jg->id }}">{{ $jg->nama_gangguan }}</option>
                                        @endforeach
                                    </select>
                                    <div id="detailJenis_gangguanFeedback" class="invalid-feedback text-xs"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="detailKet">Keterangan</label>
                                <textarea id="detailKet" class="form-control" rows="2" placeholder="Detail Gangguan" tabindex="4" disabled></textarea>
                                <div id="detailKetFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="detailAlamat">Alamat</label>
                                <textarea id="detailAlamat" class="form-control" rows="2" placeholder="Detail Gangguan" tabindex="5"
                                    disabled></textarea>
                                <div id="detailAlamatFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="detailKoordinat_rumah">Koordinat Rumah</label>
                                    <input type="text" id="detailKoordinat_rumah" class="form-control"
                                        placeholder="Serial Number" tabindex="6" disabled>
                                    <div id="detailKoordinat_rumahFeedback" class="invalid-feedback text-xs"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="detailKoordinat_odp">Koordinat ODP</label>
                                    <input type="text" id="detailKoordinat_odp" class="form-control"
                                        placeholder="Serial Number" tabindex="7" disabled>
                                    <div id="detailKoordinat_odpFeedback" class="invalid-feedback text-xs"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-6 col-sm-2">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="detailPort_odp">Port ODP</label>
                                    <input type="number" id="detailPort_odp" class="form-control"
                                        placeholder="Serial Number" tabindex="8" disabled>
                                    <div id="detailPort_odpFeedback" class="invalid-feedback text-xs"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="detailPoin">Poin Pekerjaan</label>
                                    <input type="number " id="detailPoin" class="form-control"
                                        placeholder="Poin Pekerjaan" autofocus tabindex="9">
                                    <div id="detailPoinFeedback" class="invalid-feedback text-xs"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 d-flex align-items-end justify-content-stretch">
                            <div class="form-group w-100">
                                <button type="button" class="btn bg-gradient-primary w-100" id="tugaskan">Tugaskan Tim
                                    Perbaikan</button>
                                <select id="tim" class="form-control">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div id="timFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary me-2" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="detailSimpan">Tugaskan</button>
                </div>
            </div>
        </div>
    </div> --}}
@endpush
@include('components.dataTables')
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // init
        const appUrl = `{{ env('APP_URL') }}`
        const baseUrl = appUrl + '/api/laporan'

        let wilayah = $('#wilayah').val();
        let wilayahModal = $('#wilayah_id').val();
        let wilayahSelected = null;
        let laporanSelected = null;
        let tanggal = $('#tanggal').val();

        let url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`

        let isNewPelanggan = false;

        // functions
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
        $(document).ready(function() {

            let table = $('#table').DataTable({
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
                        data: 'pelapor',
                    },
                    {
                        data: 'penerima',
                    },
                    {
                        data: 'nama_gangguan',
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
                                    case 'sedang diproses':
                                        badge = 'bg-gradient-primary'
                                        break;
                                    case 'ditunda':
                                        badge = 'bg-gradient-warning'
                                        break;
                                    case 'selesai':
                                        badge = 'bg-gradient-success'
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
                        data: 'waktu',
                        className: 'text-center text-sm',
                        render: function(data, type) {
                            if (type === 'display') {
                                return data.slice(11, -3)
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
                                return `
                            <button data-laporan="${data}" data-bs-toggle="modal" data-bs-target="#detailModal" class="btn-detail-laporan btn btn-link text-secondary font-weight-normal">
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
                $('#Modal').find('.form-control').val('');
                $('#wilayah_id').val(wilayah);
                $('#jenis_gangguan_id').val(0);
            });

            $('#detailModal').on('shown.bs.modal', function() {
                $(this).find('[autofocus]').focus();
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
                $('#detailJenis_gangguan').val(0);
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
                                $('#serial_numberFeedback').text(response.errors[
                                    'serial_number']);
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
                                $('#jenis_gangguan_idFeedback').text(response.errors[
                                    'jenis_gangguan_id']);
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
                        tim_id: $('#tim').val(),
                        wilayah_id: wilayahSelected,
                        laporan_id: laporanSelected,
                        jenis_pekerjaan_id: 2,
                        poin: $('#detailPoin').val()
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
                                $('#detailPoin').removeClass('is-invalid');
                                $('#detailPoinFeedback').hide();
                            } else {
                                $('#detailPoinFeedback').text(response.errors['poin']);
                                $('#detailPoin').addClass('is-invalid');
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

            table.on('draw', () => {
                $('.btn-detail-laporan').on('click', (e) => {
                    let card;
                    fetch(`${baseUrl}/${e.target.dataset.laporan}`)
                        .then(response => response.json())
                        .then(data => {
                            wilayahSelected = data.wilayah_id;
                            laporanSelected = data.id;
                            $('#ModalImg').attr('src', appUrl + '/storage/private/profile/' +
                                data.foto_profil)
                            $('#detailIdLaporan').text(data.id)
                            $('#detailModalLabel').text(data.nama)
                            $('#detailModalTime').text(data.waktuFormat)
                            $('#detailModalDate').text(data.tanggalFormat)
                            $('#detailSerial_number').val(data.serial_number)
                            $('#detailPaket').val(data.nama_paket)
                            $('#detailJenis_gangguan').val(data.jenis_gangguan_id)
                            $('#detailKet').val(data.ket)
                            $('#detailAlamat').val(data.alamat)
                            $('#detailKoordinat_rumah').val(data.koordinat_rumah)
                            $('#detailKoordinat_odp').val(data.koordinat_odp)
                            $('#detailPort_odp').val(data.port_odp)
                        })
                });
            });
        });






        // end event listners
    </script>
@endpush
