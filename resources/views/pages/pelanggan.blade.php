@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('css')
<link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet" />
<link href="{{asset('assets/css/custom-datatables.css')}}" rel="stylesheet" />
@endpush

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Pelanggan'])
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
                            <option value="{{$kota->id}}" {{auth()->user()->kota_id == $kota->id ? 'selected' : ''}}>{{$kota->kota}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-icon btn-3 btn-primary m-0 ms-auto" type="button" data-bs-toggle="modal"
                        data-bs-target="#Modal">
                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                        <span class="btn-inner--text">Tambah Pelanggan</span>
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
                                        Pelanggan</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        No Telepon</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Alamat</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Aksi</th>
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
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="Modal-Content">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Tambah Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-dark">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama" class="form-control-label">Nama Pelanggan</label>
                    <input type="nama" id="nama" class="form-control" placeholder="Nama Pelanggan" autofocus
                        tabindex="1">
                    <div id="namaFeedback" class="invalid-feedback text-xs"></div>
                </div>

                <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Email</label>
                    <input type="email" id="email" class="form-control" placeholder="Alamat email" tabindex="2">
                    <div id="emailFeedback" class="invalid-feedback text-xs"></div>
                </div>
                <div class="form-group">
                    <label for="example-text-input" class="form-control-label">Password</label>
                    <div class="row">
                        <div class="input-group">
                            <input type="text" class="form-control" id="password" tabindex="3">
                            <button type="button" id="copy-btn" class="btn m-0 btn-primary" data-bs-toggle="tooltip">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button type="button" id="generate-btn" class="btn m-0 btn-warning"
                                onclick="generateRandomPassword()">Generate</button>
                        </div>
                        <div id="passwordFeedback" class="invalid-feedback text-xs"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="no_telp" class="form-control-label">No Telepon</label>
                            <input type="number" class="form-control" id="no_telp" placeholder="No Telepon"
                                tabindex="4">
                            <div id="no_telpFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="no_telp" class="form-control-label">Kota</label>
                            <select class="form-control" id="kota_id" tabindex="5">
                                <option selected disabled>--Pilih Kota--</option>
                                @foreach($kotas as $kota)
                                <option value="{{$kota->id}}">{{$kota->kota}}</option>
                                @endforeach
                            </select>
                            <div id="kota_idFeedback" class="invalid-feedback text-xs"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" rows="3" placeholder="Alamat"></textarea>
                    <div id="alamatFeedback" class="invalid-feedback text-xs"></div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary me-2 " data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn bg-gradient-primary" id="simpan">Simpan</button>
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
<script>


    const baseUrl = 'api/pelanggan'
    let url = baseUrl + '?kota=' + $('#kota').val()

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
                data: 'nama',
                render: function (data, type, row) {

                    if (type === 'display') {
                        return `
                        <div class="d-flex flex-row-reverse justify-content-end align-items-center">
                            <div class="ms-3" style="white-space: nowrap;">
                                <div>`+ data + `</div>
                            </div>
                            <img class="rounded-3" src="{{env('APP_URL')}}/storage/private/profile/${row.foto_profil}" alt="foto profil" height="35">
                        </div>
                         `
                    }
                    return data;
                }
            },
            {
                data: 'no_telp',
                render: function (data, type) {
                    if (type === 'display') {
                        no = '0' + data.substring(2);
                        return `
                        <div class="d-flex flex-row-reverse justify-content-end">
                            <button class="btn btn-link btn-copy-number m-0 px-2 py-1 text-secondary fw-normal" data-cst="${no}" data-bs-toggle="tooltip">${no}</button>
                            <a target="blank" href="https://wa.me/${data}" class="btn btn-circle me-1 bg-gradient-success" data-bs-toggle="tooltip" data-bs-title="Hubungi Whatsapp"><i class="fa-brands fa-whatsapp"></i></a>
                        </div>
                        `
                    }
                    return data;
                }
            },
            {
                data: 'pemasangan.alamat',
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function (data, type) {
                    if (type === 'display') {
                        return `
                        <a href="/pelanggan/${data}" class="btn btn-link text-secondary font-weight-normal">
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



    function gantiKota() {
        url = baseUrl + '?kota=' + $('#kota').val()
        console.log(url);
        table.ajax.url(url).load()
    }

    table.on('draw.dt', function () {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

        let btnCopyNumber = document.getElementsByClassName('btn-copy-number');
        Array.from(btnCopyNumber).forEach((btn) => {
            const tooltip = new bootstrap.Tooltip(btn);
            btn.setAttribute("data-bs-original-title", "Salin nomor")
            btn.addEventListener("click", function () {
                const el = document.createElement('textarea');
                el.value = btn.dataset.cst;

                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                btn.setAttribute("data-bs-original-title", "Number Copied")
                tooltip.show()
            })
            btn.addEventListener("mouseout", function () {
                btn.setAttribute("data-bs-original-title", "Salin nomor")
            })
        })
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

    $('#Modal').on('shown.bs.modal', function () {
        $(this).find('[autofocus]').focus();
        const copyBtn = document.getElementById("copy-btn");
        const tooltip = new bootstrap.Tooltip(copyBtn);
        copyBtn.setAttribute("data-bs-original-title", "Copy Password")
        copyBtn.addEventListener("click", function () {
            var passwordInput = document.getElementById("password");
            passwordInput.select();
            document.execCommand("copy");
            copyBtn.setAttribute("data-bs-original-title", "Password Copied")
            tooltip.show()
        })
        copyBtn.addEventListener("mouseout", function () {
            copyBtn.setAttribute("data-bs-original-title", "Copy Password")
        })

        generateRandomPassword()
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
            url: 'api/pelanggan',
            type: 'POST',
            data: {
                nama: $('#nama').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                no_telp: $('#no_telp').val(),
                kota_id: $('#kota_id').val(),
                alamat: $('#alamat').val(),
            },
            success: function (response) {
                if (response.status == 400) {
                    $('#Modal').find('.invalidFeedback').show();
                    if (response.errors['nama'] == undefined) {
                        $('#nama').removeClass('is-invalid');
                        $('#namaFeedback').hide();
                    } else {
                        $('#namaFeedback').text(response.errors['nama']);
                        $('#nama').addClass('is-invalid');
                        $('#namaFeedback').show();
                    }
                    if (response.errors['email'] == undefined) {
                        $('#email').removeClass('is-invalid');
                        $('#emailFeedback').hide();
                    } else {
                        $('#emailFeedback').text(response.errors['email']);
                        $('#email').addClass('is-invalid');
                        $('#emailFeedback').show();
                    }
                    if (response.errors['password'] == undefined) {
                        $('#password').removeClass('is-invalid');
                        $('#passwordFeedback').hide();
                    } else {
                        $('#passwordFeedback').text(response.errors['password']);
                        $('#password').addClass('is-invalid');
                        $('#passwordFeedback').show();
                    }
                    if (response.errors['no_telp'] == undefined) {
                        $('#no_telp').removeClass('is-invalid');
                        $('#no_telpFeedback').hide();
                    } else {
                        $('#no_telpFeedback').text(response.errors['no_telp']);
                        $('#no_telp').addClass('is-invalid');
                        $('#no_telpFeedback').show();
                    }
                    if (response.errors['kota_id'] == undefined) {
                        $('#kota_id').removeClass('is-invalid');
                        $('#kota_idFeedback').hide();
                    } else {
                        $('#kota_idFeedback').text(response.errors['kota_id']);
                        $('#kota_id').addClass('is-invalid');
                        $('#kota_idFeedback').show();
                    }
                    if (response.errors['alamat'] == undefined) {
                        $('#alamat').removeClass('is-invalid');
                        $('#alamatFeedback').hide();
                    } else {
                        $('#alamatFeedback').text(response.errors['alamat']);
                        $('#alamat').addClass('is-invalid');
                        $('#alamatFeedback').show();
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


    function deletePelanggan(id) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menghapus data pelanggan ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/api/pelanggan/delete/${id}`,
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