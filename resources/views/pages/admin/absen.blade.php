@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@include('components.dataTables')
@push('css')
@endpush

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Teknisi'])
    <div class="container-fluid px-0 px-sm-4 py-4">
        <div class="card mb-4">
            <div class="card-header align-items-center d-flex gap-2 pb-0 flex-column flex-md-row">
                <h6 class="m-md-0 flex-shrink-0 lh-1">
                    Absensi Harian
                </h6>
                <div class="d-flex gap-3 w-100 justify-content-end">
                    <div class="form-group flex-grow-1 flex-md-grow-0">
                        <label for="wilayah" class="m-0 flex-shrink-0 me-2">Wilayah</label>
                        <select class="form-select form-select-sm pe-md-5" id="wilayah">
                            <option value="">Semua wilayah</option>
                            @foreach ($wilayahs as $wilayah)
                                <option value="{{ $wilayah->id }}"
                                    @if (request()->wilayah) {{ $wilayah->id == request()->wilayah ? 'selected' : '' }} @else {{ auth()->user()->wilayah_id == $wilayah->id ? 'selected' : '' }} @endif>
                                    {{ $wilayah->nama_wilayah }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group flex-grow-1 flex-md-grow-0">
                        <label for="tanggal" class="m-0 flex-shrink-0 me-2">Tanggal</label>
                        <input type="date" id="tanggal" class="form-control form-control-sm" id="tanggal"
                            value="{{ request()->date ?? $date }}">
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-hover" id="table" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Teknisi</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    08</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    11</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    13</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    16</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status</th>
                                <th></th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header align-items-center d-flex gap-2 pb-0 flex-column flex-md-row">
                <h6 class="m-md-0 flex-shrink-0 lh-1">
                    Rekap Absen Per-bulan
                </h6>
                <div class="d-flex gap-3 w-100 justify-content-end">
                    <div class="form-group flex-grow-1 flex-md-grow-0">
                        <label for="wilayah2" class="m-0 flex-shrink-0 me-2">Wilayah</label>
                        <select class="form-select form-select-sm pe-md-5" id="wilayah2">
                            <option value="">Semua wilayah</option>
                            @foreach ($wilayahs as $wilayah)
                                <option value="{{ $wilayah->id }}"
                                    @if (request()->wilayah) {{ $wilayah->id == request()->wilayah ? 'selected' : '' }} @else {{ auth()->user()->wilayah_id == $wilayah->id ? 'selected' : '' }} @endif>
                                    {{ $wilayah->nama_wilayah }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group flex-grow-1 flex-md-grow-0">
                        <label for="month" class="m-0 flex-shrink-0 me-2">Bulan</label>
                        <select class="form-select form-select-sm pe-md-5" id="month">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}"
                                    @if (request()->month) {{ $i == request()->month ? 'selected' : '' }} @else {{ $i == date('n') ? 'selected' : '' }} @endif>
                                    {{ \Carbon\Carbon::parse('0000-' . $i . -'01')->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group flex-grow-1 flex-md-grow-0">
                        <label for="year" class="m-0 flex-shrink-0 me-2">Tahun</label>
                        <select class="form-select form-select-sm pe-md-5" id="year2">
                            @for ($i = date('Y'); $i >= 2019; $i--)
                                <option
                                    @if (request()->year) {{ $i == request()->year ? 'selected' : '' }} @else {{ $i == date('Y') ? 'selected' : '' }} @endif
                                    value="{{ $i }}">
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-hover" id="table2" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th
                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 bg-white">
                                    Teknisi
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-white">
                                    H
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-white">
                                    T
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-white">
                                    A
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
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-fullscreen-sm-down" role="document">
            <div class="modal-content">
                <div class="modal-header gap-2">
                    <img src="" alt="foto profil" height="50" class="rounded-3" id="ModalImg">
                    <div class="d-flex flex-column justify-content-end">
                        <small class="m-0">Detail absen</small>
                        <h5 class="m-0 lh-1" id="ModalLabel"></h5>
                    </div>
                    <p class="m-0 ms-auto" id="ModalDate"></p>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('js')
    <script>
        // inits
        const appUrl = `{{ env('APP_URL') }}`;
        const wilayah = document.getElementById('wilayah');
        const tanggal = document.getElementById('tanggal');
        const baseUrl = `${appUrl}/api/absen`;

        let url = `${baseUrl}?wilayah=${wilayah.value}&tanggal=${tanggal.value}`;
        let url2 = baseUrl + '/all?wilayah=' + $('#wilayah2').val() + '&year=' + $('#year2').val() + '&month=' + $('#month')
            .val()

        $(document).ready(() => {
            let table = $('#table').DataTable({
                ajax: {
                    url: url,
                    type: 'GET',
                    dataSrc: '',
                },
                dom: "<'d-flex flex-column flex-md-row gap-3 align-items-center'<'d-flex gap-3 align-items-center'<B><l>><'ms-0 ms-md-auto'f>>" +
                    "<'row'<'col-sm-12't>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Rekap Absen Tanggal ' + $('#tanggal').val(),
                    text: 'Export Excel',
                    titleAttr: 'Export Excel',
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        },
                        customize: function(xlsx) {
                            const sheet = xlsx.xl.worksheets['sheet1.xml'];
                            const row = sheet.getElementsByTagName('row')[0];
                            const cell = document.createElement('c');
                            cell.setAttribute('t', 's');
                            cell.setAttribute('s', '2');
                            cell.appendChild(document.createElement('v')).appendChild(document
                                .createTextNode('Rekap Absen tanggal ' + $('#tanggal')
                                    .val()));
                            row.insertBefore(cell, row.firstChild);
                        }
                    },
                }],
                columns: [{
                        data: 'nama',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<div class="d-flex flex-row-reverse justify-content-end align-items-center">
                                    <div class="ms-3" style="white-space: nowrap;">
                                        <div>${data}</div>
                                    </div>
                                    <img class="rounded-3" src="${appUrl}/storage/private/${row.foto_profil}" alt="foto profil" height="35">
                                </div>`
                            }
                            return data;
                        }
                    },
                    {
                        data: 'absens.0',
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (data !== null) {
                                if (type === 'display') {
                                    return `
                                <div class="form-check p-0 justify-content-center m-0 d-flex gap-2">
                                    <input class="form-check-input m-0 " type="checkbox" id="absen-0-${row.id}" checked onclick="return false;">
                                    <label class="custom-control-label m-0" for="absen-0-${row.id}">${data}</label>
                                </div>
                                `
                                }
                                return data;
                            } else {
                                return '-'
                            }
                        }
                    },
                    {
                        data: 'absens.1',
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (data !== null) {
                                if (type === 'display') {
                                    return `
                                <div class="form-check p-0 justify-content-center m-0 d-flex gap-2">
                                    <input class="form-check-input m-0 " type="checkbox" id="absen-1-${row.id}" checked onclick="return false;">
                                    <label class="custom-control-label m-0" for="absen-1-${row.id}">${data}</label>
                                </div>
                                `
                                }
                                return data;
                            } else {
                                return '-'
                            }
                        }
                    },
                    {
                        data: 'absens.2',
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (data !== null) {
                                if (type === 'display') {
                                    return `
                                <div class="form-check p-0 justify-content-center m-0 d-flex gap-2">
                                    <input class="form-check-input m-0 " type="checkbox" id="absen-2-${row.id}" checked onclick="return false;">
                                    <label class="custom-control-label m-0" for="absen-2-${row.id}">${data}</label>
                                </div>
                                `
                                }
                                return data;
                            } else {
                                return '-'
                            }
                        }
                    },
                    {
                        data: 'absens.3',
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (data !== null) {
                                if (type === 'display') {
                                    return `
                                <div class="form-check p-0 justify-content-center m-0 d-flex gap-2">
                                    <input class="form-check-input m-0 " type="checkbox" id="absen-3-${row.id}" checked onclick="return false;">
                                    <label class="custom-control-label m-0" for="absen-3-${row.id}">${data}</label>
                                </div>
                                `
                                }
                                return data;
                            } else {
                                return '-'
                            }
                        }
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span class="badge ${data[1]} text-xss">${data[0]}</span>`
                            }
                            return data;
                        }
                    },
                    {
                        data: 'absen_id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {

                            if (data === null) {
                                return ''
                            }
                            if (type === 'display') {
                                return `
                            <button data-absen="${data}" data-bs-toggle="modal" data-bs-target="#Modal" class="btn-detail-absen btn btn-link text-secondary font-weight-normal">
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
                order: [
                    [1, 'desc'],
                    [2, 'desc'],
                    [3, 'desc'],
                    [4, 'desc'],
                    [0, 'asc']
                ]
            });

            table.on('draw', () => {
                $('.btn-detail-absen').on('click', (e) => {
                    let card;
                    fetch(`${baseUrl}/${e.currentTarget.dataset.absen}`)
                        .then(response => response.json())
                        .then(data => {
                            $('#ModalImg').attr('src', appUrl + '/storage/private/' + data.user
                                .foto_profil)
                            $('#ModalDate').text(data.tanggalFormat)
                            $('#ModalLabel').text(data.user.nama)
                            let aktivitass = data.aktivitass
                            for (const aktivitas of aktivitass) {
                                card = `
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <div class="d-flex flex-sm-row flex-column gap-3">
                                        <img src="${appUrl}/storage/private/${aktivitas.foto}" style="max-width: ${window.innerWidth > 576 ? '50%' : '100%'}" class="rounded-3">
                                        <div class="w-100">
                                            <div class="d-flex align-items-center justify-content-between w-100">
                                                <div class="form-check p-0 m-0 d-flex gap-2">
                                                    <input class="form-check-input m-0 " type="checkbox" checked
                                                    onclick="return false;">
                                                    <label class="custom-control-label m-0">${aktivitas.time}</label>
                                                </div>
                                                <small class="ms-auto fs-xxs opacity-7">${aktivitas.koordinat}</small>
                                            </div>
                                            <p class="fs-xxs opacity-7 lh-sm">${aktivitas.alamat}</p>
                                            <p class="m-0">${aktivitas.aktivitas}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `;
                                document.querySelector('#Modal .modal-body').insertAdjacentHTML(
                                    'beforeend', card);
                            }
                        })
                });
            });

            $('#Modal').on('hide.bs.modal', function() {
                $('#Modal .modal-body').text('')
            });


            $('#wilayah').on('change', function() {
                url = `${baseUrl}?wilayah=${wilayah.value}&tanggal=${tanggal.value}`
                table.ajax.url(url).load()
            });

            $('#tanggal').on('change', function() {
                url = `${baseUrl}?wilayah=${wilayah.value}&tanggal=${tanggal.value}`
                table.ajax.url(url).load()
            });

            columns = [];
            columns.push({
                data: 'nama',
                className: 'bg-white',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<div class="d-flex flex-row-reverse justify-content-end align-items-center">
                                    <div class="ms-3" style="white-space: nowrap;">
                                        <div>` + data + `</div>
                                    </div>
                                    <img class="rounded-3" src="${appUrl}/storage/private/${row.foto_profil}" alt="foto profil" height="35">
                                </div>`
                    }
                    return data;
                }
            }, {
                data: 'present',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<span class="text-success fw-bold">${data}</span><span class="text-xxs">/${row.n}</span>`
                    }
                    return data;
                }
            }, {
                data: 'late',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<span class="text-warning fw-bold">${data}</span><span class="text-xxs">/${row.n}</span>`
                    }
                    return data;
                }
            }, {
                data: 'absent',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<span class="text-danger fw-bold">${data}</span><span class="text-xxs">/${row.n}</span>`
                    }
                    return data;
                }
            })


            for (let i = 0; i < 31; i++) {
                $('#table2 th:last-child').after(
                    `<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">${i+1}</th>`
                );
                columns.push({
                    data: `detail.` + i,
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            if (data !== null) {
                                return `<span class="badge ${data[1]} text-xss">${data[0]}</span>`
                            }
                            return '-'
                        }
                        return data;
                    }
                })
            }

            let table2 = $('#table2').DataTable({
                ajax: {
                    url: url2,
                    type: 'GET',
                    serverside: true,
                    dataSrc: '',
                },
                dom: "<'d-flex flex-column flex-md-row gap-3 align-items-center'<'d-flex gap-3 align-items-center'<B><l>><'ms-0 ms-md-auto'f>>" +
                    "<'row'<'col-sm-12't>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Rekap Absen Bulan ' + ($('#month').find(":selected").text()).trim(),
                    text: 'Export excel',
                    titleAttr: 'Export Excel',
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        },
                        customize: function(xlsx) {
                            const sheet = xlsx.xl.worksheets['sheet1.xml'];
                            const row = sheet.getElementsByTagName('row')[0];
                            const cell = document.createElement('c');
                            cell.setAttribute('t', 's');
                            cell.setAttribute('s', '2');
                            cell.appendChild(document.createElement('v')).appendChild(document
                                .createTextNode('Rekap Absen Bulan ' + $('#month').find(
                                    ":selected").text()));
                            row.insertBefore(cell, row.firstChild);
                        }
                    }
                }],
                columns: columns,
                language: {
                    oPaginate: {
                        sNext: '<i class="fa fa-forward"></i>',
                        sPrevious: '<i class="fa fa-backward"></i>',
                        sFirst: '<i class="fa fa-step-backward"></i>',
                        sLast: '<i class="fa fa-step-forward"></i>'
                    }
                },
                order: [
                    [1, 'desc'],
                    [0, 'asc']
                ],
            })

            $('#wilayah2').on('change', function() {
                url2 = baseUrl + '/all?wilayah=' + $('#wilayah2').val() + '&year=' + $('#year2').val() +
                    '&month=' + $(
                        '#month').val();
                table2.ajax.url(url2).load()
            });

            $('#month').on('change', function() {
                url2 = baseUrl + '/all?wilayah=' + $('#wilayah2').val() + '&year=' + $('#year2').val() +
                    '&month=' + $(
                        '#month').val();
                table2.ajax.url(url2).load()
            });

            $('#year2').on('change', function() {
                url2 = baseUrl + '/all?wilayah=' + $('#wilayah2').val() + '&year=' + $('#year2').val() +
                    '&month=' + $(
                        '#month').val();
                table2.ajax.url(url2).load()
            });
        })
    </script>
@endpush
