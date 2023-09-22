@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@include('components.datatables.dataTables')
@include('components.select2css')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pemasangan'])
    <div class="container-fluid px-0 px-sm-4 py-sm-4">
        <div class="card mb-4">
            <div class="card-header align-items-center d-flex gap-2 pb-0 flex-column flex-md-row">
                <div class="d-flex gap-3 w-100">
                    <div class="form-group flex-grow-1 flex-md-grow-0 m-0">
                        <label for="wilayah" class="m-0 flex-shrink-0 me-2">Wilayah</label>
                        <select class="form-select form-select-sm pe-md-5" id="wilayah">
                            <option value="">Semua Wilayah</option>
                            @foreach ($wilayahs as $wilayah)
                                <option value="{{ $wilayah->id }}"
                                    {{ auth()->user()->wilayah_id == $wilayah->id ? 'selected' : '' }}>
                                    {{ $wilayah->nama_wilayah }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group flex-grow-1 flex-md-grow-0 m-0">
                        <label for="tanggal" class="m-0 flex-shrink-0 me-2">Tanggal</label>
                        <input type="date" id="tanggal" class="form-control form-control-sm"
                            value="{{ $date }}">
                    </div>

                    <button class="btn btn-icon bg-gradient-danger m-0 ms-auto align-self-end btn-add-pemasangan"
                        data-bs-toggle="modal" data-bs-target="#addPemasanganModal">
                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                        <span class="btn-inner--text d-none d-sm-inline-block">Pemasangan Baru</span>
                    </button>

                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="table" width="100%" cellspacing="0">
                        <thead>
                            <tr>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Pelanggan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Marketer</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Alamat</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Waktu<br>Daftar</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    total<br>Tarikan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                </th>
                            </tr>
                        </thead>

                    </table>
                </div>


            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
    </div>
@endsection
@push('modal')
    <!-- modal -->



    @include('components.lightbox.modal')
@endpush
@push('js')
    <script>
        const baseUrl = appUrl + '/api/pemasangan'

        let wilayah = $('#wilayah').val();
        let tanggal = $('#tanggal').val();
        let table = $('#table');
        let url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`
        let pemasangan_id;
        let tim_id;
        let pelanggan_id;
        let marketer_id;

        function format(d) {
            pekerjaan = '';
            if (d.pekerjaan) {
                anggota = '';
                d.pekerjaan.tim.forEach((e, i) => {
                    anggota += `
                    <div class="lh-sm">
                        <div class="lh-sm d-flex gap-3">
                            <img src="${appUrl}/storage/private/${e.foto_profil }"
                                class="avatar avatar-sm" alt="foto profil">
                            <div class="">
                                <div>${e.nama}</div>
                                <small class="text-xxs text-uppercase">${e.speciality}</small>
                            </div>
                        </div>
                    </div>
                    `
                })
                pekerjaan = `
                <div class="col-md-4">
                    <p class="text-uppercase">Informasi Pekerjaaan</p>
                    <div class="lh-sm">
                        <small class="text-xs">TIM Pemasangan</small>
                        <p>TIM ${d.pekerjaan.tim_id }</p>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        ${anggota}
                    </div>
                </div>
            `
            }
            return (`
                <div class="row">
                    <div class="col-md-4">
                        <p class="text-uppercase">Informasi Pelanggan</p>
                        <div class="lh-sm d-flex gap-3 ">
                            <img src="${appUrl}/storage/private/${d.pelanggan.foto_profil }"
                                class="avatar avatar-sm" alt="foto profil">
                            <div class="">
                                <div class="small text-xs">Nama Pelanggan</div>
                                <p>${d.pelanggan.nama } <small
                                        class="badge bg-gradient-secondary text-xxs">${d.wilayah }</small>
                                </p>
                            </div>

                        </div>
                        <div class="lh-sm">
                            <div class="small text-xs">NIK</div>
                            <p>${d.nik }</p>
                        </div>
                        <div class="lh-sm">
                            <div class="small text-xs">Email</div>
                            <p>${d.pelanggan.email }</p>
                        </div>
                        <div class="lh-sm">
                            <div class="small text-xs">No Telp</div>
                            <p>
                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-title="Salin nomor" onclick="copyText('body','Nomor','${(d.pelanggan.no_telp+'').replace('628','08')}')">
                                    ${(d.pelanggan.no_telp+'').replace('628','08')}</a>
                                <button class="btn btn-circle"></button>
                            </p>
                        </div>


                    </div>

                    <div class="col-md-8">
                        <p class="text-uppercase">Informasi Pemasangan</p>

                        <div class="lh-sm">
                            <div class="small text-xs">Alamat</div>
                            <p class="force-wrap-space">${d.alamat}</p>
                        </div>
                        <div class="row">
                            <div class="col-6 lh-sm">
                                <div class="small text-xs">Tikor Rumah</div>
                                <p>${d.koordinat_rumah }</p>
                            </div>
                            <div class="col-6 lh-sm">
                                <div class="small text-xs">Tikor ODP</div>
                                <p>${d.koordinat_odp ?? 'Belum ada data' }</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 lh-sm">
                                <div class="small text-xs">SN</div>
                                <p>${d.serial_number ?? 'Belum ada data' }</p>
                            </div>
                            <div class="col-md-4 lh-sm">
                                <div class="small text-xs">SSID</div>
                                <p>${d.SSID ?? 'Belum ada data' }</p>
                            </div>
                            <div class="col-md-4 lh-sm">
                                <div class="small text-xs">Password</div>
                                <p>${d.password ?? 'Belum ada data' }</p>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-4 lh-sm">
                                <small class="text-xs">Hasil OPM User</small>
                                <p>${d.hasil_opm_user ?? 'Belum ada data' }</p>
                            </div>
                            <div class="col-md-4 lh-sm">
                                <small class="text-xs">Hasil OPM User</small>
                                <p>${d.hasil_opm_odp ?? 'Belum ada data' }</p>
                            </div>
                            <div class="col-md-4 lh-sm">
                                <small class="text-xs">Kabel Terpakai</small>
                                <p>${d.kabel_terpakai ?? 'Belum ada data' }</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        ${pekerjaan}

                        <div class="col-md-${d.pekerjaan ? '8' : '12'}">
                            <div class="row">
                                <div class="col-4 mb-3">
                                    <div class="text-xs mb-2">Foto KTP</div>
                                    <img src="${appUrl}/storage/private/${d.foto_ktp}" class="rounded-3 border img-preview w-100" onclick="openLightbox(this)" style="cursor: pointer;">
                                </div>
                                <div class="col-4 mb-3">
                                    <div class="text-xs mb-2">Foto Rumah</div>
                                    <img src="${appUrl}/storage/private/${d.foto_rumah }" class="rounded-3 border img-preview w-100" onclick="openLightbox(this)" style="cursor: pointer;">
                                </div>
                                <div class="col-4 mb-3">
                                    <div class="text-xs mb-2">Foto OPM User</div>
                                    <img src="${appUrl}/storage/private/${d.foto_opm_user ?? 'pemasangan/dummy.jpg'}" class="rounded-3 border img-preview w-100" onclick="openLightbox(this)" style="cursor: pointer;">
                                </div>
                                <div class="col-4 mb-3">
                                    <div class="text-xs mb-2">Foto OPM ODP</div>
                                    <img src="${appUrl}/storage/private/${d.foto_opm_odp ?? 'pemasangan/dummy.jpg' }" class="rounded-3 border img-preview w-100" onclick="openLightbox(this)" style="cursor: pointer;">
                                </div>
                                <div class="col-4 mb-3">
                                    <div class="text-xs mb-2">Foto Modem</div>
                                    <img src="${appUrl}/storage/private/${d.foto_modem ?? 'pemasangan/dummy.jpg'}" class="rounded-3 border img-preview w-100" onclick="openLightbox(this)" style="cursor: pointer;">
                                </div>
                                <div class="col-4 mb-3">
                                    <div class="text-xs mb-2">Foto Letak Modem</div>
                                    <img src="${appUrl}/storage/private/${d.foto_letak_modem ?? 'pemasangan/dummy.jpg' }" class="rounded-3 border img-preview w-100" onclick="openLightbox(this)" style="cursor: pointer;">
                                </div>
                            </div>

                        </div>

                    </div>
                </div>`)
        }


        $(document).ready(function() {
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
                    'excel'
                ],
                order: [
                    [0, "desc"]
                ],
                columns: [

                    {
                        data: 'pelanggan.nama',
                        className: 'dt-control text-start bg-white',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `${data}<br/><span class="text-sm">${row.pelanggan.email}</span>`
                            }
                            return data;
                        }
                    },
                    {
                        data: 'marketer',
                        render: function(data, type, row) {
                            if (data === null) {
                                return '-';
                            }
                            if (type === 'display') {
                                return `${data.nama}<br/><span class="text-sm">${row.marketer.speciality}</span>`
                            }
                            return data;
                        }
                    },
                    {
                        data: 'alamat',
                        className: 'text-sm',
                    },
                    {
                        data: 'get_status',
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span class="badge bg-${data[1]} text-xxs" data-bs-toggle="tooltip" data-bs-title="Salin nomor">${data[0]}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'created_at',
                        className: 'text-center text-xs w-0',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return row.created_atFormat;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'total_tarikan',
                        className: 'text-center text-xs',
                        render: function(data, type, row) {
                            if (data === null) {
                                return '-'
                            }
                            if (type === 'display') {
                                return data + ' Meter';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle bg-white p-0',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                btn = `<a data-id="${data}" href="#addPemasanganModal" class="btn btn-link text-secondary btn-sm btn-icon-only btn-edit-pemasangan" data-bs-toggle="modal" >
                                    <i class="fa-solid fa-gear"></i>
                                </a>`
                                if (row.status == 1) {
                                    btn += `<a data-id="${data}"  href="#timModal" class="btn btn-link text-secondary icon-move-right btn-sm btn-icon-only btn-tim" data-bs-toggle="modal" >
                                        <i class="fa-solid fa-angles-right"></i>
                                    </a>`
                                }else{
                                    btn += `<a href="${appUrl}/pekerjaan/${row.pekerjaan.route_id}" class="btn btn-link text-secondary icon-move-right btn-sm btn-icon-only">
                                        <i class="fa-solid fa-angles-right"></i>
                                    </a>`
                                }

                                return btn;
                            }
                            return null;
                        }
                    },
                ],
                language: {
                    oPaginate: {
                        sNext: '<i class="fa fa-forward"></i>',
                        sPrevious: '<i class="fa fa-backward"></i>',
                        sFirst: '<i class="fa fa-step-backward"></i>',
                        sLast: '<i class="fa fa-step-forward"></i>',
                    }

                },
                createdRow: function(row) {
                    let cell = $('td:eq(2)', row);
                    cell.addClass('force-wrap-space');
                    cell.addClass('extend-min-width');
                },
                fixedColumns: {
                    left: 1,
                    right: 1,
                },
                paging: true,
                scrollX: true,
            });
            table.on('draw.dt', () => {

                $('.btn-tim').on('click', e => {
                    $('#tim_id').removeClass('d-none');
                    initSelect2(wilayah);
                    $('.tim-container').text('');
                    pemasangan_id = e.currentTarget.dataset.id;
                    tim_id = null;
                });

                $('.btn-edit-pemasangan').on('click', e => {
                    editPemasangan(e.currentTarget.dataset.id)
                })
            })
            table.on('click', 'td.dt-control', function(e) {

                let tr = e.target.closest('tr');
                let row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                } else {
                    row.child(format(row.data())).show();
                }
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
        });
    </script>
@endpush
@include('pages.admin.pemasangan.add-modal')
@include('pages.admin.pelanggan.add-modal', ['modal' => '#addPemasanganModal'])
@include('pages.admin.pekerjaan.tim-modal', ['tipe' => 'pemasangan'])
@include('components.leaflet', ['modal' => '#addPemasanganModal'])
