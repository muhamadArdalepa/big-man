@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
    @include('components.select2css')
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pekerjaan'])
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
                        <input type="date" id="tanggal" class="form-control ms-2" value="{{ $date }}" />
                    </div>
                    <button id="btn-add" class="btn btn-icon btn-3 bg-gradient-danger m-0 ms-auto" type="button"
                        data-bs-toggle="modal" data-bs-target="#Modal" data-bs-title="Tambah Laporan">
                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                        <span class="btn-inner--text d-none d-sm-inline-block">Tambah Pekerjaan</span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table" width="100%" cellspacing="0">
                        <thead>
                            <tr class="align-middle">
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    TIM
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Pekerjaan
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Detail
                                </th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Waktu
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
    @endsection @push('modal')
    <!-- modal -->
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-md-down modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel"></h5>

                </div>
                <div class="modal-body">


                    <div class="form-group lainnya-item">
                        <label for="nama_pekerjaan">Nama Pekerjaan</label>
                        <input type="text" name="nama_pekerjaan" id="nama_pekerjaan" class="form-control"
                            placeholder="Nama Pekerjaan">
                        <div id="nama_pekerjaanFeedback" class="invalid-feedback text-xs"></div>
                    </div>

                    <div class="form-group new-item">
                        <label for="detail">Keterangan Pekerjaan</label>
                        <textarea id="detail" class="form-control" rows="3" placeholder="Detail Pekerjaan"></textarea>
                        <div id="detailFeedback" class="invalid-feedback text-xs"></div>
                    </div>


                    <div class="form-group pemasangan-item">
                        <label for="pemasangan_id">Pilih Pemasangan</label>
                        <select class="form-select" id="pemasangan_id"></select>
                        <div id="pemasangan_idFeedback" class="invalid-feedback text-xs"></div>
                    </div>


                    <div class="form-group laporan-item">
                        <label for="laporan_id">Pilih Laporan</label>
                        <select class="form-select" id="laporan_id"></select>
                        <div id="laporan_idFeedback" class="invalid-feedback text-xs"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 pemasangan-item">
                            <div class="form-group">
                                <label for="nik" class="form-control-label">NIK</label>
                                <input type="number" class="form-control" id="nik" placeholder="NIK" disabled>
                                <div id="nikFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>


                        <div class="col-md-6 pemasangan-item laporan-item">
                            <div class="form-group">
                                <label for="no_telp" class="form-control-label">No Telepon</label>
                                <input type="number" class="form-control" id="no_telp" placeholder="No Telepon"
                                    disabled>
                                <div id="no_telpFeedback" class="invalid-feedback text-xs"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group pemasangan-item">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control" placeholder="Email Pelanggan"
                            >
                        <div id="emailFeedback" class="invalid-feedback text-xs"></div>
                    </div>
                    <div class="form-group pemasangan-item">
                        <label for="paket_id" class="form-control-label">Paket Pilihan</label>
                        <select class="form-select" id="paket_id" >
                            <option selected disabled value="">--Paket Pilihan--</option>
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
                                <i class="fa-solid fa-map"></i>
                                <span>Lihat Peta</span>
                            </button>
                        </div>
                        <textarea class="form-control" id="alamat" rows="3" placeholder="Alamat Pekerjaan" ></textarea>
                        <div id="alamatFeedback" class="invalid-feedback text-xs"></div>
                    </div>

                    <div class="form-group">
                        <label for="koordinat_rumah" class="form-control-label">Koordinat Rumah</label>
                        <input type="text" class="form-control" id="koordinat_rumah" placeholder="Koordinat Rumah" >
                        <div id="koordinat_rumahFeedback" class="invalid-feedback text-xs"></div>
                    </div>

                    <div class="row pemasangan-item">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto_ktp" class="form-control-label">Foto KTP</label>
                                <input type="file" id="foto_ktp" class="d-none">
                                <div class="rounded-3 border overflow-hidden position-relative img-container">
                                    <img src="" class="img-preview w-100">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto_rumah" class="form-control-label">Foto Rumah</label>
                                <input type="file" id="foto_rumah" class="d-none">
                                <div class="rounded-3 border overflow-hidden position-relative img-container">
                                    <img src="" class="img-preview w-100">
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row laporan-item d-none">
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

                    <div class="form-group">
                        <label for="poin">Tingkat Kesulitan</label>
                        <select id="poin" class="form-select">
                            <option selected disabled value="">-- Poin --</option>
                            @foreach (\App\Models\Kesulitan::all() as $poin)
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
        // init
        const appUrl = `{{ env('APP_URL') }}`;
        const baseUrl = `${appUrl}/api/pekerjaan`;
        let koordinat_rumah = null
        let alamat = null

        let wilayah = $("#wilayah").val();
        let wilayah_id = $("#wilayah_id").val();
        let tanggal = $("#tanggal").val();

        let url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`;

        // functions
        function trPemasangan(data) {
            if (!data.id) {
                return data.nama;
            }
            var $teknisi = $(`
				<div>
					<div>${data.nama}</div>
					<div class="text-sm">${data.alamat}</div>
				</div>`
            );
            return $teknisi;
        };

        function templateSelection(data) {
            if (!data.id) {
                return data.nama;
            }
            var $teknisi = $(`${data.nama} - ${data.alamat}`);
            return $teknisi;
        };

        function initPemasangan() {
            $('#pemasangan_id').select2({
                ajax: {
                    url: baseUrl + '/select2-pemasangan',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            nama: params.term,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results,
                        };
                    },
                    cache: true,
                },
                width: '100%',
                cache: true,
                templateResult: trPemasangan,
                templateSelection: templateSelection,
                dropdownParent: $('#Modal'),
                theme: 'bootstrap-5',
            });
        }

        $('#pemasangan_id').on('change', e => {
            $('#nik').val('')
            $('#no_telp').val('')
            $('#email').val('')
            $('#paket_id').val('')
            $('#alamat').val('')
            koordinat_rumah = null
            alamat = null
            console.log($('#pelapor').val());
            // if ($('#pelapor').val() != '') {
            //     fetch(baseUrl + '/data-pemasangan/' + $('#pemasangan_id').val())
            //         .then(response => response.json())
            //         .then(data => {
            //             console.log(data);
            //             $('#serial_number').val(data.serial_number);
            //             $('#alamat').val(data.alamat);
            //             $('#port_odp').val(data.port_odp);
            //             koordinat_rumah = data.koordinat_rumah;
            //             alamat = data.alamat;
            //             $('#btn-open-map').removeClass('d-none')
            //         })
            // }
        })

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
                url: baseUrl + '/select2-tim?wilayah=' + e,
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

        // end functions

        // event listeners
        $(document).ready(function() {
            initTim(wilayah)
            initPemasangan(wilayah)
            let table = $("#table").DataTable({
                ajax: {
                    url: url,
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
                columns: [{
                        data: "id",
                        className: "text-center text-sm p-1 w-0",
                    },
                    {
                        data: "anggota",
                        render: function(data, type, row) {
                            if (type === "display") {
                                return (
                                    `
                                <div class="d-flex flex-row-reverse justify-content-end">
                                    <div class="ms-2 lh-1">
                                        <strong class="text-xxs text-secondary d-block  mb-1">TIM ${row.tim_id}</strong>
                                        <div class="lh-sm text-base">` +
                                    data +
                                    `</div>
                                    </div>
                                    <img class="rounded-3" src="{{ env('APP_URL') }}/storage/private/${row.foto_profil}" alt="foto profil" height="35">
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
                        className: "text-center w-0 text-sm",
                    },
                    {
                        data: "status",
                        className: "text-center w-0",
                        render: function(data, type) {
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
                        render: function(data, type) {
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
                createdRow: function(row) {
                    let cell = $("td:eq(3)", row);
                    cell.addClass("extend-min-width");
                    cell.addClass("force-wrap-space");
                },
            });
            $("#wilayah").on("change", function() {
                wilayah = $("#wilayah").val();
                tanggal = $("#tanggal").val();
                url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`;
                table.ajax.url(url).load();
                wilayah_id = wilayah;
            });

            $("#tanggal").on("change", function() {
                wilayah = $("#wilayah").val();
                tanggal = $("#tanggal").val();
                url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`;
                table.ajax.url(url).load();
                wilayah_id = wilayah;
            });


            $('#simpan').on('click', function(e) {
                let data = null;
                if ($('#jenis_pekerjaan_id').val() == 0) {
                    data = {
                        jenis_pekerjaan_id: $('#jenis_pekerjaan_id').val(),
                        tim_id: $('#tim_id').val(),
                        wilayah_id: $('#wilayah_id').val(),
                        poin: $('#poin').val(),
                        nama_pekerjaan: $('#nama_pekerjaan').val(),
                        detail: $('#detail').val(),
                        alamat: $('#alamat').val(),
                    }
                }

                axios.post(baseUrl, data)
                    .then(response => {
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
        });

        $("#wilayah_id").on("change", function() {
            $('#tim_id').val(null).trigger('change');
            initTim($('#wilayah_id').val());
        });

        $("#jenis_pekerjaan_id").on("change", function() {
            switch ($("#jenis_pekerjaan_id").val()) {
                case '0':
                    $('.pemasangan-item').addClass('d-none')
                    $('.laporan-item').addClass('d-none')
                    $('.lainnya-item').removeClass('d-none')
                    $('.new-item').removeClass('d-none')
                    $('#btn-open-map span').text('Pilih lewat peta')
                    $('#btn-open-map i').removeClass('fa-map')
                    $('#btn-open-map i').addClass('fa-location-dot')
                    break;
                case '1':
                    $('.lainnya-item').addClass('d-none')
                    $('.new-item').addClass('d-none')
                    $('.laporan-item').addClass('d-none')
                    $('.pemasangan-item').removeClass('d-none')
                    $('#btn-open-map span').text('Lihat peta')
                    $('#btn-open-map i').removeClass('fa-location-dot')
                    $('#btn-open-map i').addClass('fa-map')
                    break;
                case '2':
                    $('.lainnya-item').addClass('d-none')
                    $('.new-item').addClass('d-none')
                    $('.pemasangan-item').addClass('d-none')
                    $('.laporan-item').removeClass('d-none')
                    $('#btn-open-map span').text('Lihat peta')
                    $('#btn-open-map i').removeClass('fa-location-dot')
                    $('#btn-open-map i').addClass('fa-map')
                    break;
                default:
                    $('.lainnya-item').addClass('d-none')
                    $('.pemasangan-item').addClass('d-none')
                    $('.laporan-item').addClass('d-none')
                    $('.new-item').removeClass('d-none')
                    $('#btn-open-map span').text('Pilih lewat peta')
                    $('#btn-open-map i').removeClass('fa-location-dot')
                    $('#btn-open-map i').addClass('fa-map')
                    break;
            }
        });

        $("#Modal").on("shown.bs.modal", function() {
            $(this).on("keypress", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    $("#simpan").click();
                }
            });
        });

        $('#btn-add').on('click', () => {
            $('#Modal .form-control').val(null);
            $('#Modal .form-select:not(#wilayah_id)').val('');
            $('.detail-item').addClass('d-none')
            $('#edit-perform').addClass('d-none')
            $('#ModalLabel').text('Tambah Pekerjaan');
            $('#wilayah_id').val(wilayah);
            koordinat_rumah = null
            alamat = null
            $('#simpan').removeClass('d-none')
        })

        // end event listners
    </script>
@endpush
