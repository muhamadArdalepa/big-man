@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100']) @push('css')
<link
    href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    rel="stylesheet"
/>
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
/>
@endpush @section('content') @include('layouts.navbars.auth.topnav', ['title' =>
'Pekerjaan'])
<div class="container-fluid px-0 px-sm-4 py-sm-4">
    <div class="card mb-4">
        <div class="card-header pb-0">
            <div class="d-flex gap-3">
                <div class="d-flex align-items-center">
                    <label for="wilayah" class="m-0 d-none d-sm-inline-block"
                        >Wilayah</label
                    >
                    <select class="form-control m-0 ms-sm-2" id="wilayah">
                        <option value="">Semua Wilayah</option>
                        @foreach ($wilayahs as $wilayah)
                        <option value="{{$wilayah->id}}" {{auth()->
                            user()->wilayah_id==$wilayah->id?'selected':''}}>{{$wilayah->nama_wilayah}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex align-items-center">
                    <label for="tanggal" class="m-0 d-none d-sm-inline-block"
                        >Tanggal</label
                    >
                    <input
                        type="date"
                        id="tanggal"
                        class="form-control ms-2"
                        value="{{ $date }}"
                    />
                </div>
                <button
                    class="btn btn-icon btn-3 bg-gradient-danger m-0 ms-auto"
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#Modal"
                    data-bs-title="Tambah Laporan"
                >
                    <span class="btn-inner--icon"
                        ><i class="fas fa-plus"></i
                    ></span>
                    <span class="btn-inner--text d-none d-sm-inline-block"
                        >Tambah Pekerjaan</span
                    >
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table
                    class="table table-hover"
                    id="table"
                    width="100%"
                    cellspacing="0"
                >
                    <thead>
                        <tr class="align-middle">
                            <th
                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                            ></th>
                            <th
                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"
                            >
                                TIM
                            </th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                            >
                                Pekerjaan
                            </th>
                            <th
                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                            >
                                Detail
                            </th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                            >
                                Waktu
                            </th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                            >
                                Status
                            </th>
                            <th
                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                            ></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @include('layouts.footers.auth.footer')
</div>

@endsection @push('modal')
<!-- modal -->
<div
    class="modal fade"
    id="Modal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="ModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Tambah Pekerjaan</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="no_telp" class="form-control-label"
                                >Wilayah</label
                            >
                            <select
                                class="form-control"
                                id="wilayah_id"
                                tabindex="5"
                            >
                                @foreach($wilayahs as $wilayah)
                                <option value="{{$wilayah->id}}">
                                    {{$wilayah->nama_wilayah}}
                                </option>
                                @endforeach
                            </select>
                            <div
                                id="wilayah_idFeedback"
                                class="invalid-feedback text-xs"
                            ></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="no_telp" class="form-control-label"
                                >Jenis Pekerjaan</label
                            >
                            <select
                                class="form-control"
                                id="wilayah_id"
                                tabindex="5"
                            >
                                @foreach($jenis_pekerjaans as $jenis_pekerjaan)
                                <option value="{{$jenis_pekerjaan->id}}">
                                    {{$jenis_pekerjaan->nama_pekerjaan}}
                                </option>
                                @endforeach
                            </select>
                            <div
                                id="jenis_pekerjaan_idFeedback"
                                class="invalid-feedback text-xs"
                            ></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="laporan" class="form-control-label"
                                >Nama Pelanggan</label
                            >
                            <select id="laporan" class="form-control">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="koordinat_rumah"
                                class="form-control-label"
                                >Koordinat Rumah</label
                            >
                            <input
                                type="text"
                                id="koordinat_rumah"
                                class="form-control"
                            />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tim" class="form-control-label">Alamat</label>
                    <textarea
                        id="alamat"
                        rows="2"
                        class="form-control"
                    ></textarea>
                </div>
                <div class="form-group">
                    <label for="tim" class="form-control-label"
                        >Tim Teknisi</label
                    >
                    <select id="tim" class="form-control">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn bg-gradient-secondary me-2"
                    data-bs-dismiss="modal"
                >
                    Close
                </button>
                <button
                    type="button"
                    class="btn bg-gradient-primary"
                    id="simpan"
                >
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>
@endpush @include('components.dataTables') @push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // init
    const appUrl = `{{env('APP_URL')}}`;
    const baseUrl = `${appUrl}/api/pekerjaan`;

    let wilayah = $("#wilayah").val();
    let wilayahModal = $("#wilayah_id").val();
    let tanggal = $("#tanggal").val();

    let url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`;

    // functions
    function initSelect2(wilayahModal) {
        $("#pelanggan").select2({
            ajax: {
                url: `api/select2-laporan-pelanggan?wilayah=${wilayahModal}`,
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        terms: params.term,
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results,
                    };
                },
                cache: true,
            },
            width: "100%",
            dropdownParent: $("#Modal"),
            theme: "bootstrap-5",
        });
    }
    // end functions

    // event listeners
    $(document).ready(function () {
        let table = $("#table").DataTable({
            ajax: {
                url: url,
                type: "GET",
                serverside: true,
                dataSrc: "",
            },
            dom:
                "<'d-flex flex-column flex-md-row gap-3 align-items-center '<'d-flex align-items-center w-100 w-sm-auto'<'whitespace-nowrap'B><'ms-sm-3 ms-auto'l>><'ms-0 ms-md-auto'f>>" +
                "<'row'<'col-sm-12't>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: ["excel", "pdf"],
            order: [[0, "desc"]],
            columns: [
                {
                    data: "id",
                    className: "text-center p-0 pe-3 w-0",
                },
                {
                    data: "anggota",
                    render: function (data, type, row) {
                        if (type === "display") {
                            return (
                                `
                        <div class="d-flex flex-row-reverse justify-content-end align-items-center">
                            <div class="ms-3" style="white-space: nowrap;">
                                <p class="text-xs text-secondary mb-0">TIM ${row.tim_id}</p>
                                <div>` +
                                data +
                                `</div>
                            </div>
                            <img class="rounded-3" src="{{env('APP_URL')}}/storage/private/profile/${row.foto_profil}" alt="foto profil" height="35">
                        </div>
                         `
                            );
                        }
                        return data;
                    },
                },
                {
                    data: "nama_pekerjaan",
                    className: "w-0",
                },
                {
                    data: "detail",
                },
                {
                    data: "created_atFormat",
                    className: "text-center w-0",
                },
                {
                    data: "status",
                    className: "text-center w-0",
                    render: function (data, type) {
                        if (type === "display") {
                            let badge = null;
                            switch (data) {
                                case "sedang diproses":
                                    badge = "bg-gradient-primary";
                                    break;
                                case "ditunda":
                                    badge = "bg-gradient-warning";
                                    break;
                                case "selesai":
                                    badge = "bg-gradient-success";
                                    break;
                                case "dibatalkan":
                                    badge = "bg-gradient-danger";
                                    break;
                            }
                            return `
                                <span class="badge badge-sm text-xxs ${badge}">${data}</span>
                                `;
                        }
                        return data;
                    },
                },
                {
                    data: "id",
                    orderable: false,
                    searchable: false,
                    className: "text-center p-0",
                    render: function (data, type) {
                        if (type === "display") {
                            return `
                        <a href="${appUrl}/pekerjaan/${data}" class="btn btn-link text-secondary font-weight-normal">
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
            createdRow: function (row) {
                let cell = $("td:eq(3)", row);
                cell.addClass("force-wrap-space");
            },
        });
        initSelect2(wilayahModal);

        $("#wilayah").on("change", function () {
            wilayah = $("#wilayah").val();
            tanggal = $("#tanggal").val();
            url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`;
            table.ajax.url(url).load();
            wilayahModal = wilayah;
        });

        $("#tanggal").on("change", function () {
            wilayah = $("#wilayah").val();
            tanggal = $("#tanggal").val();
            url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`;
            table.ajax.url(url).load();
            wilayahModal = wilayah;
        });

        $("#wilayah_id").on("change", function () {
            wilayahModal = $("#wilayah_id").val();
            if (!isNewPelanggan) {
                $("#pelanggan").select2("destroy");
                $("#pelanggan").val(null).trigger("change");
                initSelect2(wilayahModal);
            }
        });

        $("#Modal").on("shown.bs.modal", function () {
            $(this).find("[autofocus]").focus();
            $(this).on("keypress", function (event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    $("#simpan").click();
                }
            });
        });

        $("#Modal").on("hide.bs.modal", function () {
            $("#Modal").find(".form-control").removeClass("is-invalid");
            $("#Modal").find(".invalidFeedback").hide();
            $("#Modal").find(".form-control").val("");
            $("#Modal").find("select").val();
        });

        $("#simpan").on("click", function (e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });
            $.ajax({
                url: "api/laporan",
                type: "POST",
                data: {
                    is_new: isNewPelanggan,
                    id: $("#pelanggan").val(),
                    nama: $("#nama").val(),
                    email: $("#email").val(),
                    password: $("#password").val(),
                    no_telp: $("#no_telp").val(),
                    wilayah_id: $("#wilayah_id").val(),
                    alamat: $("#alamat").val(),
                    jenis_gangguan_id: $("#jenis_gangguan_id").val(),
                    ket: $("#ket").val(),
                },
                success: function (response) {
                    if (response.status == 400) {
                        console.log(response);
                        $("#Modal").find(".invalidFeedback").show();
                        if (response.errors["nama"] == undefined) {
                            $("#nama").removeClass("is-invalid");
                            $("#namaFeedback").hide();
                        } else {
                            $("#namaFeedback").text(response.errors["nama"]);
                            $("#nama").addClass("is-invalid");
                        }
                        if (response.errors["email"] == undefined) {
                            $("#email").removeClass("is-invalid");
                            $("#emailFeedback").hide();
                        } else {
                            $("#emailFeedback").text(response.errors["email"]);
                            $("#email").addClass("is-invalid");
                        }
                        if (response.errors["no_telp"] == undefined) {
                            $("#no_telp").removeClass("is-invalid");
                            $("#no_telpFeedback").hide();
                        } else {
                            $("#no_telpFeedback").text(
                                response.errors["no_telp"]
                            );
                            $("#no_telp").addClass("is-invalid");
                        }
                        if (response.errors["alamat"] == undefined) {
                            $("#alamat").removeClass("is-invalid");
                            $("#alamatFeedback").hide();
                        } else {
                            $("#alamatFeedback").text(
                                response.errors["alamat"]
                            );
                            $("#alamat").addClass("is-invalid");
                        }
                        if (response.errors["wilayah_id"] == undefined) {
                            $("#wilayah_id").removeClass("is-invalid");
                            $("#wilayah_idFeedback").hide();
                        } else {
                            $("#wilayah_idFeedback").text(
                                response.errors["wilayah_id"]
                            );
                            $("#wilayah_id").addClass("is-invalid");
                        }
                        if (response.errors["jenis_gangguan_id"] == undefined) {
                            $("#jenis_gangguan_id").removeClass("is-invalid");
                            $("#jenis_gangguan_idFeedback").hide();
                        } else {
                            $("#jenis_gangguan_idFeedback").text(
                                response.errors["jenis_gangguan_id"]
                            );
                            $("#jenis_gangguan_id").addClass("is-invalid");
                        }
                    } else {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false,
                        });
                        $("#Modal").find(".form-control").val("");
                        $("#Modal")
                            .find(".form-control")
                            .removeClass("is-invalid");
                        $("#Modal").find(".invalidFeedback").hide();
                        $("#Modal").modal("hide");
                        table.ajax.reload();
                    }
                },
            });
        });
    });

    // end event listners
</script>

@endpush
