@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
    <style>
        .form-group {
            margin-bottom: 0px !important;
        }
    </style>
@endpush
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Teknisi'])
    <div class="container-fluid px-0 px-sm-4 py-4">
        @if ($action)
            <div class="card mb-4">
                <div class="card-body d-flex justify-content-between" id="absenAction">
                    <div class="d-flex align-items-center flex-grow-1 flex-sm-grow-0">
                        <div>Sudah waktunya absen</div>
                    </div>
                    <div class="d-flex align-items-center flex-grow-1 flex-sm-grow-0">
                        <button class="btn btn-danger whitespace-nowrap" data-bs-toggle="modal" data-bs-target="#absenModal">
                            Absen&nbsp;Sekarang</button>
                    </div>
                </div>
            </div>
        @endif
        <div class="card mb-4">
            <div class="card-header align-items-center d-flex gap-2 pb-0 flex-column flex-md-row">

                <h6 class="m-0 lh-1 flex-shrink-0">
                    Rekap Absen Saya
                </h6>
                <div class="d-flex gap-3 w-100 justify-content-end">
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
                        <select class="form-select form-select-sm pe-md-5" id="year">
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
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Tanggal</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Tanggal</th>
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
                    Rekap Absen Per-tahun
                </h6>
                <div class="ms-auto d-flex align-items-center flex-grow-1 flex-md-grow-0">
                    <label for="year" class="m-0 flex-shrink-0 me-2">Tahun</label>
                    <select class="form-select form-select-sm pe-md-5" id="year2">
                        @for ($i = date('Y'); $i >= 2019; $i--)
                            <option
                                @if (request()->year) {{ $i == request()->year ? 'selected' : '' }} @else {{ $i == date('Y') ? 'selected' : '' }} @endif
                                value="{{ $i }}">
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table2" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 bg-white">
                                    Bulan
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
    @if ($action)
        <div class="modal fade" id="absenModal" tabindex="-1" role="dialog" aria-labelledby="absenModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="gap-2 mb-2 d-flex">
                            <h5 class="m-0 lh-1" id="absenModalLabel">Absen {{ $whichAbsen }}</h5>

                            <div class="ms-auto text-end lh-sm">
                                <div id="absenModalDate"></div>
                                <div>{{ $date }}</div>
                            </div>
                        </div>
                        <input type="hidden" name="image" id="image-tag">
                        <div class="mb text-xs opacity-7" id="koordinat"></div>
                        <div class="text-xs opacity-7" id="location"></div>
                        <div id="koordinatFeedback" class="invalid-feedback text-xs"></div>
                        <div id="myCamera" class="mt-3 d-block rounded-3 overflow-hidden"></div>

                        <div id="fotoFeedback" class="invalid-feedback text-xs"></div>

                        <div class="d-flex justify-content-between gap-3 mt-3">
                            <button id="cameraButton" class="btn bg-gradient-primary flex-grow-1">Ambil Foto</button>
                        </div>

                        <textarea id="aktivitas" class="form-control mt-3" rows="3" placeholder="Aktivitas"></textarea>
                        <div id="aktivitasFeedback" class="invalid-feedback text-xs"></div>
                        <div class="d-flex justify-content-end gap-3 mt-3">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                            <button id="absenButton" class="btn bg-gradient-success">Absen</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-fullscreen-sm-down" role="document">
            <div class="modal-content">
                <div class="modal-header gap-2">
                    <h5 class="m-0 lh-1" id="ModalLabel">Detail Absen</h5>
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
@include('components.dataTables')
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

    <script>
        // inits
        const wilayah = document.getElementById('wilayah');
        const authId = `{{ auth()->user()->id }}`
        const appUrl = `{{ env('APP_URL') }}`
        const baseUrl = `${appUrl}/api/absen`
        let koordinat;
        let alamat;
        let url = baseUrl + '?month=' + $('#month').val() + '&year=' + $('#year').val();
        let url2 = baseUrl + '/all?year=' + $('#year2').val();
       
        // functions
        @if ($action)
            function startCamera(btn) {
                const cameraWidth = document.getElementById('myCamera').offsetWidth
                const cameraHeight = window.innerWidth > window.innerHeight ? cameraWidth * 3 / 4 : cameraWidth * 4 / 3;
                Webcam.set({
                    width: cameraWidth,
                    height: cameraHeight,
                    image_format: 'jpeg',
                    jpeg_quality: 70
                });

                Webcam.attach('#myCamera');
                btn.setAttribute('onclick', 'takePicture(this)')
                btn.classList.replace('bg-gradient-warning', 'bg-gradient-primary');
                btn.innerHTML = 'Ambil Foto'
                $('#absenButton').hide()
                getLocation()
            }


            function takePicture(btn) {
                Webcam.snap(function(data_uri) {
                    $("#image-tag").val(data_uri);
                    document.getElementById('myCamera').innerHTML = '<img src="' + data_uri + '" />';
                });
                btn.setAttribute('onclick', 'startCamera(this)');
                btn.classList.replace('bg-gradient-primary', 'bg-gradient-warning');
                btn.innerHTML = 'Ulangi'
                $('#absenButton').show()

            }

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(getAddressFromCoordinates, showError);
                } else {
                    document.getElementById('location').textContent = 'Geolokasi tidak didukung oleh browser ini.';
                }
            }

            function getAddressFromCoordinates(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;
                koordinat = latitude + ',' + longitude
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
                    .then(response => response.json())
                    .then(data => {
                        var address = data.display_name;
                        alamat = address
                        document.getElementById('koordinat').textContent = koordinat;
                        document.getElementById('location').textContent = address;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('location').textContent = 'Gagal mendapatkan alamat';
                    });
            }

            function showError(error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        document.getElementById('location').textContent = 'Pengguna menolak permintaan Geolokasi.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        document.getElementById('location').textContent = 'Informasi lokasi tidak tersedia.';
                        break;
                    case error.TIMEOUT:
                        document.getElementById('location').textContent =
                            'Waktu permintaan untuk mendapatkan lokasi pengguna habis.';
                        break;
                    case error.UNKNOWN_ERROR:
                        document.getElementById('location').textContent = 'Terjadi kesalahan yang tidak diketahui.';
                        break;
                }
            }
        @endif

        // event listeners
        $(document).ready(() => {
            $('#aktivitasFeedback').hide();
            $('#koordinatFeedback').hide();
            $('#fotoFeedback').hide();
            $('#absenButton').hide()
            let modalJamInterval;
            let table = $('#table').DataTable({
                ajax: {
                    url: url,
                    type: 'GET',
                    serverside: true,
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
                        data: 'created_at',
                        visible: false
                    },
                    {
                        data: 'created_at',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return row.tanggalFormat
                            }
                            return row.tanggalFormat;
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
                                return `<span class="badge ${data[1]} text-xss">${data[0]}</span>
                            `
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
                    [0, 'desc']
                ]
            });

            table.on('draw', () => {
                $('.btn-detail-absen').on('click', (e) => {
                    let card = '';
                    fetch(baseUrl + '/' + e.target.dataset.absen)
                        .then(response => response.json())
                        .then(data => {
                            $('#ModalDate').text(data.tanggalFormat)
                            let aktivitass = data.aktivitass
                            for (const absen of aktivitass) {
                                let time = new Date(absen.updated_at)
                                card = `
                            <div class="card mb-3">
                                <div class="card-body p-3">
                                    <div class="d-flex flex-sm-row flex-column gap-3">
                                        <img src="${appUrl}/storage/private/${absen.foto}" style="max-width: ${window.innerWidth > 576 ? '50%' : '100%'}" class="rounded-3">
                                        <div class="w-100">
                                            <div class="d-flex align-items-center justify-content-between w-100">
                                                <div class="form-check p-0 m-0 d-flex gap-2">
                                                    <input class="form-check-input m-0 " type="checkbox" checked
                                                    onclick="return false;">
                                                    <label class="custom-control-label m-0">${absen.time}</label>
                                                </div>
                                                <small class="ms-auto fs-xxs opacity-7">${absen.koordinat}</small>
                                            </div>
                                            <p class="fs-xxs opacity-7 lh-sm">${absen.alamat}</p>
                                            <p class="m-0">${absen.aktivitas}</p>
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

            $('#month').on('change', function() {
                url = baseUrl + '?month=' + $('#month').val() + '&year=' + $('#year').val();
                table.ajax.url(url).load()
            });
            $('#year').on('change', function() {
                url = baseUrl + '?month=' + $('#month').val() + '&year=' + $('#year').val();
                table.ajax.url(url).load()
            });

            @if ($action)

                $('#absenModal').on('shown.bs.modal', function() {
                    startCamera(document.getElementById('cameraButton'))
                    $('#absenModalDate').text(updateClock())
                    modalJamInterval = setInterval(() => {
                        $('#absenModalDate').text(updateClock())
                    }, 1000);
                });

                $('#absenModal').on('hide.bs.modal', function() {
                    Webcam.reset();
                    clearInterval(modalJamInterval)
                });

                $('#absenButton').on('click', e => {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: `${baseUrl}`,
                        type: 'POST',
                        data: {
                            foto: $('#image-tag').val(),
                            koordinat: koordinat,
                            alamat: alamat,
                            aktivitas: $('#aktivitas').val(),
                        },
                        success: function(response) {
                            if (response.status == 400) {
                                $('#Modal').find('.invalidFeedback').show();
                                if (response.errors['aktivitas'] == undefined) {
                                    $('#aktivitas').removeClass('is-invalid');
                                    $('#aktivitasFeedback').hide();
                                } else {
                                    $('#aktivitasFeedback').text(response.errors['aktivitas']);
                                    $('#aktivitas').addClass('is-invalid');
                                    $('#aktivitasFeedback').show();
                                }
                                if (response.errors['koordinat'] == undefined) {
                                    $('#koordinatFeedback').hide();
                                } else {
                                    $('#koordinatFeedback').text(response.errors['koordinat']);
                                    $('#koordinatFeedback').show();
                                }
                                if (response.errors['foto'] == undefined) {
                                    $('#fotoFeedback').hide();
                                } else {
                                    $('#fotoFeedback').text(response.errors['foto']);
                                    $('#fotoFeedback').show();
                                }
                            } else if (response.status == 401) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'error',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                });
                                $('#absenAction').remove()
                                $('#absenModal').modal('hide');
                                $('#aktivitas').val('')
                                Webcam.reset()
                                table.ajax.reload()
                                $('#absenModal').find('.form-control').val('');
                                $('#absenModal').find('.form-control').removeClass(
                                    'is-invalid');
                                $('#absenModal').find('.invalidFeedback').hide();
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                });
                                $('#absenAction').remove()
                                $('#absenModal').modal('hide');
                                $('#aktivitas').val('')
                                Webcam.reset()
                                table.ajax.reload()
                                $('#absenModal').find('.form-control').val('');
                                $('#absenModal').find('.form-control').removeClass(
                                    'is-invalid');
                                $('#absenModal').find('.invalidFeedback').hide();
                            }
                        }
                    });
                })
            @endif

            $('#Modal').on('hide.bs.modal', function() {
                $('#Modal .modal-body').text('')
            });

            columns = [];
            columns.push({
                data: 'i',
                visible: false
            }, {
                data: 'bulan'
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
                    data: i,
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
                    [0, 'desc'],
                ]
            })
            $('#year2').on('change', function() {
                url2 = baseUrl + '/all?year=' + $('#year2').val();
                table2.ajax.url(url2).load()
            });
        })
    </script>
@endpush
