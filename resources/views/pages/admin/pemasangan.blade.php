@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@include('components.dataTables')
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
                        <input type="date" id="tanggal" class="form-control m-0 ms-sm-2" value="{{ $date }}">
                    </div>

                    <button class="btn btn-icon bg-gradient-danger m-0 ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addPemasanganModal">
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
                                    Alamat</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Waktu<br>Daftar</th>
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
    
    <div class="modal fade" id="addPemasanganModal" tabindex="-1" role="dialog" aria-labelledby="addPemasanganModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-md-down modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPemasanganModalLabel">Tambah Pemasangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                <input type="number" class="form-control" id="nik" placeholder="NIK" tabindex="4">
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

    <div class="modal fade" id="timModal" tabindex="-1" role="dialog" aria-labelledby="timModalLabel"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="timModalLabel">Tugaskan Teknisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                        <select class="form-select" id="tim_id" tabindex="1">
                            <option value=""></option>
                        </select>
                        <div class="tim-container"></div>
                        <div id="tim_idFeedback" class="invalid-feedback text-xs"></div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary me-2" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary btn-simpan">Simpan</button>
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
        const appUrl = "{{ env('APP_URL') }}";
        const baseUrl = appUrl + '/api/pemasangan'

        let wilayah = $('#wilayah').val();
        let tanggal = $('#tanggal').val();
        let table = $('#table');
        let url = `${baseUrl}?wilayah=${wilayah}&tanggal=${tanggal}`
        let pemasangan_id;
        let tim_id;

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
                                <a href="javascript:;" class="btn-no_telp" data-bs-toggle="tooltip">
                                    ${d.pelanggan.no_telp}</a>
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
                                <div class="col-4">
                                    <small class="text-xs">Foto KTP</small>
                                    <div class="rounded-3 border overflow-hidden img-container"style="cursor: pointer;"
                                        onclick="">
                                        <img src="${appUrl}/storage/private/${d.foto_ktp}"
                                            class="img-preview w-100">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <small class="text-xs">Foto Rumah</small>
                                    <div class="rounded-3 border overflow-hidden img-container"style="cursor: pointer;"
                                        onclick="">
                                        <img src="${appUrl}/storage/private/${d.foto_rumah }"
                                            class="img-preview w-100">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <small class="text-xs">Foto OPM User</small>
                                    <div class="rounded-3 border overflow-hidden img-container"style="cursor: pointer;"
                                        onclick="">
                                        <img src="${appUrl}/storage/private/${d.foto_opm_user ?? 'pemasangan/dummy.jpg'}"
                                            class="img-preview w-100">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <small class="text-xs">Foto OPM ODP</small>
                                    <div class="rounded-3 border overflow-hidden img-container"style="cursor: pointer;"
                                        onclick="">
                                        <img src="${appUrl}/storage/private/${d.foto_opm_odp ?? 'pemasangan/dummy.jpg' }"
                                            class="img-preview w-100">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <small class="text-xs">Foto Modem</small>
                                    <div class="rounded-3 border overflow-hidden img-container"style="cursor: pointer;"
                                        onclick="">
                                        <img src="${appUrl}/storage/private/${d.foto_modem ?? 'pemasangan/dummy.jpg'}"
                                            class="img-preview w-100">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <small class="text-xs">Foto Letak Modem</small>
                                    <div class="rounded-3 border overflow-hidden img-container"style="cursor: pointer;"
                                        onclick="">
                                        <img src="${appUrl}/storage/private/${d.foto_letak_modem ?? 'pemasangan/dummy.jpg' }"
                                            class="img-preview w-100">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>`)
        }

        $(document).ready(function() {
            initSelect2()
            initSelect2(wilayah)
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
                columns: [{
                        data: 'pelanggan.nama',
                        className: 'text-sm dt-control text-start details-control',
                    },
                    {
                        data: 'alamat',
                        className: 'text-sm',
                    },
                    {
                        data: 'get_status',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span class="badge bg-${data[1]} text-xxs">${data[0]}</span>`;
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
                        data: 'route_id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle bg-white p-0',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                if (row.status == 1) {
                                    return `<a data-id="${data}" href="#Modal"
                                            class="btn btn-link text-secondary btn-sm btn-icon-only"
                                            data-bs-toggle="modal" >
                                            <i class="fa-solid fa-gear"></i>
                                        </a>
                                        <a data-id="${data}"  href="#timModal"
                                            class="btn btn-link text-secondary icon-move-right btn-sm btn-icon-only btn-tim"
                                            data-bs-toggle="modal" >
                                            <i class="fa-solid fa-angles-right"></i>
                                        </a>`
                                }
                                if (row.status == 2 || row.status == 4) {
                                    return `<a href="${appUrl}/pekerjaan/${row.pekerjaan.route_id}"
                                            class="btn btn-link text-secondary icon-move-right btn-sm btn-icon-only">
                                        <i class="fa-solid fa-angles-right"></i>
                                        </a>`
                                }
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
                    let cell = $('td:eq(1)', row);
                    cell.addClass('force-wrap-space');
                    cell.addClass('extend-min-width');
                },
                fixedColumns: {
                    left: 1,
                    right: 1,
                },
                paging: true,
                scrollX: true
            });
            table.on('draw', () => {
                $('.btn-tim').on('click', e => {
                    $('#tim_id').removeClass('d-none');
                    initSelect2(wilayah);
                    $('.tim-container').text('');
                    pemasangan_id = e.currentTarget.dataset.id;
                    tim_id = null;
                })
            })
            table.on('childRow.dt', e => {
                let tooltips = document.querySelectorAll('.btn-no_telp');
                tooltips.forEach(tooltip => {
                    console.log(tooltip);
                    const bsTooltip = new bootstrap.Tooltip(tooltip);
                    tooltip.setAttribute("data-bs-original-title", "Salin Nomor");
                    tooltip.addEventListener('click', function() {
                        tooltip.setAttribute("data-bs-original-title", "Nomor Disalin");
                        const textToCopy = tooltip.innerText;
                        const tempTextarea = document.createElement('textarea');
                        tempTextarea.value = textToCopy;
                        document.body.appendChild(tempTextarea);
                        tempTextarea.select();
                        document.execCommand('copy');
                        document.body.removeChild(tempTextarea);
                        bsTooltip.show();
                    });
                    tooltip.addEventListener('mouseout', function() {
                        tooltip.setAttribute("data-bs-original-title", "Salin Nomor");
                        bsTooltip.hide();
                    })
                });
                const tooltipTriggerList = [].slice.call(document.querySelectorAll(
                    '[data-bs-toggle="tooltip"]'))
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
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

        function templateResult(tim) {
            if (!tim.id) {
                return tim.text;
            }
            let tc = `<div class="form-control m-0 d-flex flex-column gap-2">
                <p class="mb-2">${tim.text}</p>`;

            tim.anggota.forEach(a => {
                tc += `<div class="d-flex align-items-center gap-2">
                <img src="/storage/private/${a.foto_profil}" class="avatar-sm rounded-3">
                <div class="text-sm">${a.nama}</div>
                <span class="badge bg-gradient-danger text-xxs">${a.speciality}</span>
            </div>`;
            });

            tc += '</div>'
            return $(tc);
        }

        function templateSelection(tim) {
            return $(`<span class="text-muted">Cari tim . . . </span>`);
        };

        function initSelect2(wilayahId) {
            $('#tim_id').select2({
                ajax: {
                    url: `${appUrl}/api/pekerjaan/select2-tim?wilayah=${wilayahId}`,
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
                templateResult: templateResult,
                templateSelection: templateSelection,
                dropdownParent: $('#timModal'),
                theme: 'bootstrap-5',
            });
        }

        $('#tim_id').on('change', e => {
            tim_id = $('#tim_id').val()
            axios(`${appUrl}/api/pekerjaan/data-tim/` + $('#tim_id').val())
                .then(response => {

                    d = response.data
                    c = '',
                        d.forEach(a => {
                            c += `<div class="d-flex align-items-center gap-2">
                        <img src="/storage/private/${a.user.foto_profil}" class="avatar-sm rounded-3">
                        <div class="text-sm">${a.user.nama}</div>
                        <span class="badge bg-gradient-danger text-xxs">${a.user.speciality}</span>
                    </div>`
                        });
                    $('.tim-container').append(`
                    <div class="form-control m-0 d-flex flex-column gap-2">
                        <p class="mb-2 d-flex">
                            TIM ${response.data[0].tim_id}
                            <button class="btn btn-link text-danger btn-delete-tim fw-normal ms-auto">
                                <i class="fa-solid fa-xmark"></i>    
                            </button>    
                        </p>
                        ${c}
                    </div>`);


                    $('.btn-delete-tim').on('click', e => {
                        $('#tim_id').removeClass('d-none');
                        initSelect2(wilayah);
                        $('.tim-container').text('');
                    })
                })
            $('#tim_id').select2('destroy');
            $('#tim_id').addClass('d-none');
        })

        $('#timModal .btn-simpan').on('click', () => {
            data = {
                tim_id: tim_id,
                poin: $('#poin').val()
            }
            axios.post(`${baseUrl}/store-pekerjaan/` + pemasangan_id, data)
                .then(response => {
                    table.ajax.reload()
                    $('#timModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.data.message,
                        timer: 1500,
                        showConfirmButton: false,
                    });
                })
                .catch(error => {
                    $('#timModal').find('.invalid-feedback').text('')
                    $('#timModal').find('.form-select').removeClass('is-invalid')
                    var errors = error.response.data.errors;
                    if (error.response.status == 422) {
                        for (const key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + 'Feedback').show();
                                $('#' + key + 'Feedback').text(errors[key]);
                            }
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.response.data.message,
                            timer: 1500,
                            showConfirmButton: false,
                        });
                        $('#timModal').modal('hide');
                    }

                })
        })
    </script>
@endpush
