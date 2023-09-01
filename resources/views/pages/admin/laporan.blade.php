@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @include('components.select2css')

    <style>
        #map>div.leaflet-pane.leaflet-map-pane>div.leaflet-pane.leaflet-popup-pane>div>div.leaflet-popup-content-wrapper>div {
            width: 300px !important;
        }
    </style>
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
                    <button id="btn-add" class="btn btn-icon btn-3 bg-gradient-danger m-0 ms-auto" type="button"
                        data-bs-toggle="modal" data-bs-target="#Modal" data-bs-title="Tambah Laporan">
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

                            <li>
                                <a id="terima" class="dropdown-item btn-selesai border-radius-md py-1 " href="javascript:;">
                                    <i class="fa-solid fa-check me-2 text-success"></i>
                                    Terima dan tugaskan perbaikan

                                </a>
                            </li>

                            <li>
                                <a id="lihatDetail" class="dropdown-item btn-selesai border-radius-md py-1 " >
                                    <i class="fa-solid fa-info"></i>
                                    Lihat Detail Pemasangan

                                </a>
                            </li>

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
                    <div class="text-xs mb-1 detail-item"><i class="fa-solid me-2 fa-calendar-plus"></i><span
                            id="created_at"></span></div>
                    <div class="text-xs detail-item status-terima mb-3">
                        <i class="fa-solid fa-calendar-check me-2"></i>
                        <span id="recieve_at" class=""></span> - <span id="penerima"></span>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="jenis_gangguan_id">Jenis Gangguan</label>
                                <select id="jenis_gangguan_id" class="form-select">
                                    <option disabled value="null">-- Jenis Gangguan --</option>
                                    @foreach ($jenis_gangguans as $jg)
                                        <option value="{{ $jg->id }}">{{ $jg->nama_gangguan }}</option>
                                    @endforeach
                                </select>
                                <div id="jenis_gangguan_idFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
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
                    </div>
                    <div class="form-group">
                        <label for="ket">Keterangan</label>
                        <textarea class="form-control" id="ket" rows="3" placeholder="Keterangan"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="pelapor" class="form-control-label">Nama Pelanggan</label>
                        <select id="pelapor" class="form-control w-100">
                        </select>
                        <div id="pelaporFeedback" class="invalid-feedback text-xs"></div>
                    </div>


                    <div class="form-group">
                        <div class="d-flex align-items-end">
                            <label for="alamat" class="form-control-label">Alamat</label>
                            <button id="btn-open-map"
                                class="detail-item btn ms-auto p-2 btn-link text-secondary font-weight-normal"
                                data-bs-toggle="modal" data-bs-target="#mapModal">
                                <i class="fa-solid fa-map"></i>
                                Lihat Peta
                            </button>
                        </div>
                        <textarea class="form-control disabled" id="alamat" rows="3" disabled></textarea>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <label for="serial_number">Serial Number</label>
                                <input type="text" id="serial_number" class="form-control disabled" disabled>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label for="port_odp">Port ODP</label>
                                <input type="text" id="port_odp" class="form-control disabled" disabled>
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

    <div class="modal fade" id="tugaskanModal" tabindex="-1" role="dialog" aria-labelledby="tugaskanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-md-down modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tugaskanModalLabel">Tugaskan Teknisi</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="poin">Tingkat Kesulitan</label>
                        <select id="poin" class="form-select">
                            <option selected disabled value="">-- Poin --</option>
                            @foreach (\App\Models\Poin::all() as $poin)
                                <option value="{{ $poin->poin }}">{{ $poin->kesulitan }}
                                    +{{ $poin->poin }}poin
                                </option>
                            @endforeach
                        </select>
                        <div id="poinFeedback" class="invalid-feedback text-xs"></div>
                    </div>

                    <div class="form-group">
                        <label for="tim_id">Tim Teknisi</label>
                        <select class="form-select" id="tim_id" tabindex="1"></select>
                        <div id="tim_idFeedback" class="invalid-feedback text-xs"></div>
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
@include('components.dataTables')
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // global init
        const appUrl = `{{ env('APP_URL') }}`
        const baseUrl = appUrl + '/api/laporan'


        let koordinat_rumah = null
        let alamat = null
        function tsTim(data) {
            if (data.id === '') {
                return $('<span style="color:#b3bbc2">Nama Pelanggan</span>');
            }
            var $data = $(
                `<div>TIM ${data.id} <span class="text-sm">${data.text}</span> </div>`
            );
            return $data;
        }

        function trTim(data) {
            if (!data.id) {
                return data.text;
            }
            var $data = $(
                `<div>
					<div>TIM ${data.id}</div>
					<div class="text-sm">${data.text}</div>
				</div>`
            );
            return $data;
        };

        function initTim(e) {
            $('#tim_id').empty();
            $('#tim_id').append($('<option>', {
                value: null,
            }));
            if ($('#tim_id').data('select2')) {
                $('#tim_id').select2('destroy')
            }
            $.ajax({
                url: appUrl+'api/pekerjaan/select2-tim?wilayah=' + e,
                type: 'GET',
                dataType: 'json',
                success: ((data) => {
                    data.forEach((option) => {
                        $('#tim_id').append($('<option>', {
                            value: option.id,
                            text: option.text,
                        }));
                    });
                    $('#tim_id').select2({
                        templateSelection: tsTim,
                        templateResult: trTim,
                        width: '100%',
                        dropdownParent: $('#Modal'),
                        theme: 'bootstrap-5',
                    });
                })
            })
        }
        function templateSelection(data) {
            if (data.id === '') {
                return $('<span style="color:#b3bbc2">Nama Pelanggan</span>');
            }
            return data.text;
        }

        function initSelect2(e, pId = null) {
            $('#pelapor').empty();
            $('#pelapor').append($('<option>', {
                value: null,
            }));
            if ($('#pelapor').data('select2')) {
                $('#pelapor').select2('destroy')
            }
            $.ajax({
                url: baseUrl + '/select2-pelanggan?wilayah=' + e,
                type: 'GET',
                dataType: 'json',
                success: ((data) => {
                    data.forEach((option) => {
                        $('#pelapor').append($('<option>', {
                            value: option.id,
                            text: option.text
                        }));
                    });
                    $('#pelapor').select2({
                        templateSelection: templateSelection,
                        width: '100%',
                        dropdownParent: $('#Modal'),
                        theme: 'bootstrap-5',
                    });

                    $('#pelapor').val(pId).trigger('change');
                })
            })

        }


        // datatable
        let wilayah = $('#wilayah').val();
        let tanggal = $('#tanggal').val();
        let table = $('#table');

        let url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`
        let laporanId = null

        $(document).ready(function() {
            initTim(wilayah)

            initSelect2(wilayah);
            $('#pelapor').val(null).trigger('change');
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
                columns: [{
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
                        data: 'created_at',
                        className: 'text-center text-xs',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return row.timeFormat;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return '<button data-laporan="' + data +
                                    '" data-bs-toggle="modal" data-bs-target="#Modal" class="btn-detail-laporan btn btn-link text-secondary font-weight-normal">Detail</button>'
                            }
                            return data;
                        }
                    }
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

            $('#wilayah').on('change', function() {
                wilayah = $('#wilayah').val()
                tanggal = $('#tanggal').val()
                url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`
                table.ajax.url(url).load()
                wilayah_id = wilayah
            });

            $('#tanggal').on('change', function() {
                wilayah = $('#wilayah').val()
                tanggal = $('#tanggal').val()
                url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`
                table.ajax.url(url).load()
                wilayah_id = wilayah
            });

            table.on('draw', () => {
                $('.btn-detail-laporan').on('click', (e) => {
                    laporanId = e.currentTarget.dataset.laporan
                    $('.detail-item').removeClass('d-none')
                    $('#simpan').addClass('d-none')
                    $('#edit-perform').addClass('d-none')
                    $('#Modal .form-control').attr('disabled', 'true')
                    $('#Modal .form-select').attr('disabled', 'true')
                    fetch(`${baseUrl}/${laporanId}`)
                        .then(response => response.json())
                        .then(data => {
                            $('#ModalLabel').text('Detail Laporan ' + data.id);
                            $('#ModalStatus').text(data.status);
                            var badge = null
                            switch (data.status) {
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
                            if (data.penerima != null) {
                                $('.status-terima').removeClass('d-none')
                                $('#penerima').text(data.penerima_nama)
                                $('#recieve_at').text(data.recieve_atFormat)
                            } else {
                                $('.status-terima').addClass('d-none')
                            }
                            $('#ModalStatus').addClass(badge);
                            $('#created_at').text(data.created_atFormat)
                            $('#jenis_gangguan_id').val(data.jenis_gangguan_id);
                            $('#penerima').val(data.penerima_nama);
                            $('#ket').val(data.ket);
                            $('#wilayah_id').val(data.wilayah_id);
                            initSelect2(data.wilayah_id, data.pelapor)
                            $('#alamat').val(data.alamat)
                            $('#serial_number').val(data.serial_number)
                            $('#port_odp').val(data.port_odp)
                            koordinat_rumah = data.koordinat_rumah
                            alamat = data.alamat
                            $('#Modal').modal('show');
                        })
                });
            });


            $('#simpan').on('click', function(e) {
                $('#Modal').find('.invalid-feedback').text('')
                $('#Modal').find('.form-control').removeClass('is-invalid')
                $('#Modal').find('.form-select').removeClass('is-invalid')
                axios.post(baseUrl, {
                        jenis_gangguan_id: $('#jenis_gangguan_id').val(),
                        pelapor: $('#pelapor').val(),
                        status: 'menunggu konfirmasi',
                        penerima: {{ auth()->user()->id }},
                        ket: $('#ket').val(),
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
            $('#edit-perform').on('click', () => {
                $('#Modal').find('.invalid-feedback').text('')
                $('#Modal').find('.form-control').removeClass('is-invalid')
                $('#Modal').find('.form-select').removeClass('is-invalid')
                axios.patch(baseUrl + '/' + laporanId, {
                        jenis_gangguan_id: $('#jenis_gangguan_id').val(),
                        pelapor: $('#pelapor').val(),
                        ket: $('#ket').val(),
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

        });
        $('#terima').on('click', function(e) {
            $('#Modal').modal('hide')
            $('#tugaskanModal').modal('show')
        })
        $('#edit').on('click', function(e) {
            $('.form-control:not(.disabled)').removeAttr('disabled')
            $('.form-select:not(.disabled)').removeAttr('disabled')
            $('#edit-perform').removeClass('d-none');
        })
        $('#hapus').on('click', function(e) {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus data laporan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(baseUrl + '/' + laporanId)
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
        })
        $('#pelapor').on('change', e => {
            $('#serial_number').val('')
            $('#alamat').val('')
            $('#port_odp').val('')
            koordinat_rumah = null
            alamat = null
            console.log($('#pelapor').val());
            if ($('#pelapor').val() != '') {
                fetch(baseUrl + '/data-pelanggan/' + $('#pelapor').val())
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        $('#serial_number').val(data.serial_number);
                        $('#alamat').val(data.alamat);
                        $('#port_odp').val(data.port_odp);
                        koordinat_rumah = data.koordinat_rumah;
                        alamat = data.alamat;
                        $('#btn-open-map').removeClass('d-none')
                    })
            }
        })

        $('#btn-add').on('click', () => {
            $('#Modal .form-control').val(null);
            $('#Modal .form-control:not(.form-control.disabled)').removeAttr('disabled');
            $('#Modal .form-select:not(#wilayah_id)').val(null);
            $('#Modal .form-select:not(.form-control.disabled)').removeAttr('disabled');
            $('.detail-item').addClass('d-none')
            $('#edit-perform').addClass('d-none')
            initSelect2(wilayah);
            $('#ModalLabel').text('Tambah Laporan');
            $('#wilayah_id').val(wilayah);
            $('#jenis_gangguan_id').val('null').trigger('change');
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


        $('#wilayah_id').on('change', function() {
            $('#pelapor').val(null).trigger('change');
            initSelect2($('#wilayah_id').val());
        });


        let map = null;

        function setupMap(coords, address) {
            var parts = coords.split(',');
            var lat = parseFloat(parts[0]);
            var long = parseFloat(parts[1]);
            if (map) {
                map.remove();
            }
            map = L.map('map').setView([lat, long], 16);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 20,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            var marker = L.marker([lat, long]).addTo(map);
            marker.bindPopup(`<div class="mb-3 w-auto">${address}</div>`)
                .openPopup();

        }

        $('#mapModal').on('shown.bs.modal', e => {
            // setup map 
            mapHeight = document.getElementById('map-container').offsetHeight
            $('#map').css('height', mapHeight)
            setupMap(koordinat_rumah, alamat)
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
        // end event listners
    </script>
@endpush
