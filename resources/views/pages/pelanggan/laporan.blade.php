@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100']) @push('css')
    <style>
        textarea {
            resize: none;
        }
    </style>
    @endpush @section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Lapor Gangguan'])
    <div class="container-fluid px-0 px-sm-4 py-sm-4">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex gap=-3">
                    <h6 class="m-0 d-flex align-items-center">Riwayat Laporan</h6>
                    <button class="btn btn-icon btn-3 btn-danger m-0 ms-auto" type="button" data-bs-toggle="modal"
                        data-bs-target="#Modal" data-bs-title="Tambah Laporan">
                        <span class="btn-inner--icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
                        <span class="btn-inner--text d-none d-sm-inline-block">Lapor Gangguan</span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Tanggal lapor
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Penerima
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Jenis Gangguan
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Waktu Lapor
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status
                                </th>
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
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Tambah Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jenis_gangguan_id">Jenis Gangguan</label>
                        <select id="jenis_gangguan_id" class="form-control" autofocus tabindex="1">
                            <option selected disabled value="null">
                                -- Pilih jenis gangguan --
                            </option>
                            @foreach ($jenis_gangguans as $jg)
                                <option value="{{ $jg->id }}">
                                    {{ $jg->nama_gangguan }}
                                </option>
                            @endforeach
                        </select>
                        <div id="jenis_gangguan_idFeedback" class="invalid-feedback text-xs"></div>
                    </div>
                    <div class="form-group">
                        <label for="ket">Keterangan</label>
                        <textarea class="form-control" id="ket" rows="3" placeholder="Tambahkan keterangan gangguan" tabindex="2"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary me-2" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn bg-gradient-danger" id="simpan" tabindex="3">
                        Lapor
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endpush @include('components.dataTables') @push('js')
    <script>
        // init
        const appUrl = `{{ env('APP_URL') }}`;
        const baseUrl = appUrl + "/api/laporan";

        // event listeners
        $(document).ready(function() {
            let table = $("#table").DataTable({
                ajax: {
                    url: baseUrl,
                    type: "GET",
                    serverside: true,
                    dataSrc: "",
                },
                dom: "<'d-flex flex-column flex-md-row gap-3 align-items-center '<'d-flex align-items-center w-100 w-sm-auto'<'whitespace-nowrap'B><'ms-sm-3 ms-auto'l>><'ms-0 ms-md-auto'f>>" +
                    "<'row'<'col-sm-12't>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: ["excel", "pdf"],
                order: [
                    [0, "desc"]
                ],
                columns: [
                    {
                        data: 'created_at',
                        className: 'text-sm',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return row.dateFormat;
                            }
                            return data;
                        }
                    },
                    {
                        data: "nama",

                    },
                    {
                        data: "nama_gangguan",
                    },
                    {
                        data: 'created_at',
                        className: 'text-center text-sm w-0 px-1',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return row.timeFormat;
                            }
                            return data;
                        }
                    },
                    {
                        data: "status",
                        className: "text-center w-0",
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
                        },
                    },
                    {
                        data: "id",
                        orderable: false,
                        searchable: false,
                        className: "text-center w-0",
                        render: function(data, type) {
                            if (type === "display") {
                                return `
                        <a href="/teknisi/${data}" class="btn btn-link text-secondary font-weight-normal">
                            Detail
                        </a>
                        `;
                            }
                            return data;
                        },
                    },
                ],
                language: {
                    oPaginate: {
                        sNext: '<i class="fa fa-forward"></i>',
                        sPrevious: '<i class="fa fa-backward"></i>',
                        sFirst: '<i class="fa fa-step-backward"></i>',
                        sLast: '<i class="fa fa-step-forward"></i>',
                    },
                },
            });


            $("#Modal").on("shown.bs.modal", function() {
                $(this).on("keypress", function(event) {
                    if (event.keyCode === 13) {
                        event.preventDefault();
                        $("#simpan").click();
                    }
                });
            });

            $("#Modal").on("hide.bs.modal", function() {
                $("#jenis_gangguan_id").val(null);
            });

            $("#simpan").on("click", function(e) {
                axios.post(baseUrl, {
                        jenis_gangguan_id: $("#jenis_gangguan_id").val(),
                        ket: $("#ket").val(),
                    })
                    .then(response => {
                        $("#Modal .form-control").removeClass("is-invalid");
                        $("#Modal .form-select").removeClass("is-invalid");
                        $("#Modal").modal("hide");
                        table.ajax.reload();
                        Swal.fire({
                            icon: "success",
                            title: "Success",
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
                    });
            });
        });
        // end event listners
    </script>
@endpush
