@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('css')
<link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet" />
<link href="{{asset('assets/css/custom-datatables.css')}}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Laporan'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <label for="kota" class="m-0">Kota</label>
                        <select class="form-control ms-2" id="kota" onchange="(gantiKota())">
                            <option value="">Semua Kota</option>
                            @foreach ($kotas as $kota)
                            <option value="{{$kota->id}}" {{auth()->user()->kota_id == $kota->id ? 'selected' :
                                ''}}>{{$kota->kota}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center ms-3">
                        <label for="tanggal" class="m-0">Tanggal</label>
                        <input type="date" id="tanggal" class="form-control ms-2" value="{{$date}}">
                    </div>
                    <button class="btn btn-icon btn-3 btn-primary m-0 ms-auto" type="button" data-bs-toggle="modal"
                        data-bs-target="#Modal">
                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                        <span class="btn-inner--text">Tambah Laporan</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">
                                        #</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Pelapor</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Penerima</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Jenis Gangguan</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Status</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    </th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
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
                            <select class="form-control" id="kota_id" tabindex="5">
                                @foreach($kotas as $kota)
                                <option value="{{$kota->id}}">{{$kota->kota}}</option>
                                @endforeach
                            </select>
                            <div id="kota_idFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="form-group">
                            <div class="d-flex align-items-center">
                                <label for="nama" class="form-control-label">Nama Pelanggan</label>
                                <div class="form-check form-switch ms-auto">
                                    <input class="form-check-input" type="checkbox" id="pelanggan-baru"
                                        onchange="ubahTipePelanggan()">
                                    <label class="form-check-label" for="pelanggan-baru">Pelanggan baru</label>
                                </div>
                            </div>
                            <input type="nama" id="nama" class="form-control" placeholder="Nama Pelanggan" autofocus
                                tabindex="1">
                            <select id="pelanggan" class="form-control w-100"></select>
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
                            <input type="email" id="email" class="form-control" placeholder="Alamat email" tabindex="2">
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
                                @foreach($jenis_gangguans as $jg)
                                <option value="{{$jg->id}}">{{$jg->jenis_gangguan}}</option>
                                @endforeach
                            </select>
                            <div id="jenis_gangguan_idFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="ket">Keterangan</label>
                            <textarea class="form-control" id="ket" rows="3" placeholder="Keterangan"></textarea>
                        </div>
                    </div>
                </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary me-2" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn bg-gradient-primary" id="simpan">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endpush
@push('js')

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // init
    const kota = $('#kota').val();
    const tanggal = $('#tanggal').val();
    const baseUrl = 'api/laporan'
    let url = `${baseUrl}?kota=${kota}&tanggal=${tanggal}`

    let isNewPelanggan = false;

    // caller
    ubahTipePelanggan();


    // datatable init
    let table = $('#table').DataTable({
        ajax: {
            url: url,
            type: 'GET',
            serverside: true,
            dataSrc: '',
        },
        dom: "<'d-flex flex-column flex-md-row gap-3 align-items-center '<'whitespace-nowrap'B><l><'ms-0 ms-md-auto'f>>" +
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
                data: 'pelapor.nama',
            },
            {
                data: 'penerima.nama',
            },
            {
                data: 'jenis_gangguan.jenis_gangguan',
            },
            {
                data: 'status',
                className: 'text-center',
                render: function (data, type) {
                    if (type === 'display') {
                        let badge = []
                        switch (data) {
                            case 1:
                                badge[0] = 'warning'
                                badge[1] = 'Pending'
                                break;
                            case 2:
                                badge[0] = 'secondary'
                                badge[1] = 'Diterima'
                                break;
                            case 3:
                                badge[0] = 'secondary'
                                badge[1] = 'Diproses'
                                break;
                            case 4:
                                badge[0] = 'success'
                                badge[1] = 'Selesai'
                                break;
                            default:
                                break;
                        }
                        return `
                        <span class="badge badge-sm text-xxs bg-gradient-${badge[0]}">${badge[1]}</span>
                        `
                    }
                    return data
                }
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function (data, type) {
                    if (type === 'display') {
                        return `
                        <a href="/teknisi/${data}" class="btn btn-link text-secondary font-weight-normal">
                            Detail
                        </a>
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
        createdRow: function (row) {
            let cell = $('td:eq(3)', row);
            cell.addClass('force-wrap-space');
        },
    });


    // functions
    function gantiKota() {
        url = baseUrl + '?kota=' + $('#kota').val()
        table.ajax.url(url).load()
    }

    function ubahTipePelanggan() {
        isNewPelanggan = (!isNewPelanggan);
        $('#nama').hide();
        $('#pelanggan').hide()

        if (isNewPelanggan) {
            $('#nama').hide();
            $('#pelanggan').show()
        } else {
            $('#nama').show();
            $('#pelanggan').hide()
        }
        $('#nama').val('');
        $('#pelanggan').val(null).trigger('change');
    }



    function showTeknisi(id) {
        window.location.href = "{{env('APP_URL')}}/teknisi/" + id;
    }

    // event listener

    table.on('draw.dt', function () {

    })

    $('#Modal').on('shown.bs.modal', function () {
        $('#kota_id').val(kota)
        $('#pelanggan').select2({
            ajax: {
                url: `api/select2-laporan-pelanggan?kota=${$('#kota_id').val()}`, 
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        nama: params.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results,
                    };
                },
                cache: true
            },
            cache: true,
            dropdownParent: $('#Modal'),
            theme: 'bootstrap-5',
        })
        $(this).find('[autofocus]').focus();
        $(this).on('keypress', function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                $('#simpan').click()
            }
        });
    });

    $('#Modal').on('hide.bs.modal', function () {
        $('#Modal').find('.form-control').removeClass('is-invalid');
        $('#Modal').find('.invalidFeedback').hide();
    });

    $(document).on('click', '#simpan', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: 'api/laporan',
            type: 'POST',
            data: {
                is_new: 'true',
                nama: $('#nama').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                no_telp: $('#no_telp').val(),
                kota_id: $('#kota_id').val(),
                alamat: $('#alamat').val(),
                jenis_gangguan_id: $('#jenis_gangguan_id').val(),
                ket: $('#ket').val(),
            },
            success: function (response) {
                if (response.status == 400) {
                    console.log(response);
                    $('#Modal').find('.invalidFeedback').show();
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
                    if (response.errors['password'] == undefined) {
                        $('#password').removeClass('is-invalid');
                        $('#passwordFeedback').hide();
                    } else {
                        $('#passwordFeedback').text(response.errors['password']);
                        $('#password').addClass('is-invalid');
                    }
                    if (response.errors['no_telp'] == undefined) {
                        $('#no_telp').removeClass('is-invalid');
                        $('#no_telpFeedback').hide();
                    } else {
                        $('#no_telpFeedback').text(response.errors['no_telp']);
                        $('#no_telp').addClass('is-invalid');
                    }
                    if (response.errors['kota_id'] == undefined) {
                        $('#kota_id').removeClass('is-invalid');
                        $('#kota_idFeedback').hide();
                    } else {
                        $('#kota_idFeedback').text(response.errors['kota_id']);
                        $('#kota_id').addClass('is-invalid');
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
    function deleteTeknisi(id) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menghapus data teknisi ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/api/teknisi/delete/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false,
                        });
                        table.ajax.reload()
                    },
                    error: function (response) {
                        Swal.fire('Error', response.message, 'error');
                    }
                });
            }
        });
    }
</script>

@endpush