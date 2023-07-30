@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('css')
<link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet" />
<link href="{{asset('assets/css/custom-datatables.css')}}" rel="stylesheet" />
<link href="{{asset('assets/css/custom-select2.css')}}" rel="stylesheet" />


@endpush

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Teknisi'])
<div class="container-fluid px-0 px-sm-4 py-4">
    <div class="card mb-4">
        <div class="card-header pb-0">
            <div class="d-flex flex-column flex-sm-row">
                <h6 class="m-0 d-flex align-items-center">
                    Absen Harian Teknisi
                </h6>
                <div class="d-flex ms-sm-auto w-100 w-sm-auto gap-3">
                    <div class="d-flex align-items-center flex-grow-1 flex-sm-grow-0">
                        <label for="kota" class="m-0 d-none d-sm-inline-block">Kota</label>
                        <select class="form-control m-0 ms-sm-2" id="kota">
                            <option value="">Semua Kota</option>
                            @foreach ($kotas as $kota)
                            <option value="{{$kota->id}}" {{auth()->
                                user()->kota_id==$kota->id?'selected':''}}>{{$kota->kota}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex align-items-center flex-grow-1 flex-sm-grow-0">
                        <label for="tanggal" class="m-0 d-none d-sm-inline-block">Tanggal</label>
                        <input type="date" id="tanggal" class="form-control ms-2" value="{{$date}}">
                    </div>
                </div>

            </div>

        </div>
        <div class="card-body">
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
                            <th></th>
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
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header gap-2">
                <img src="/storage/private/profile/" alt="foto profil" height="50" class="rounded-3" id="ModalImg">
                <div class="d-flex flex-column justify-content-end">
                    <small class="m-0">Detail absen</small>
                    <h5 class="m-0 lh-1" id="ModalLabel"></h5>
                </div>
                <p class="m-0 ms-auto" id="ModalDate"></p>
            </div>
            <div class="modal-body">
                <div class="card mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex flex-sm-row flex-column gap-3">
                            <img src="/storage/private/absen/" style="max-width: 50%;" class="rounded-3">
                            <div class="">
                                <div class="form-check p-0 m-0 d-flex gap-2">
                                    <input class="form-check-input m-0 " type="checkbox" checked
                                        onclick="return false;">
                                    <label class="custom-control-label m-0">12:00</label>
                                </div>
                                <p class="card-text">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolorum
                                    culpa excepturi ab facilis? Est, tempore.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex flex-sm-row flex-column gap-3">
                            <img src="/storage/private/absen/" style="max-width: 50%;" class="rounded-3">
                            <div class="">
                                <div class="form-check p-0 m-0 d-flex gap-2">
                                    <input class="form-check-input m-0 " type="checkbox" checked
                                        onclick="return false;">
                                    <label class="custom-control-label m-0">12:00</label>
                                </div>
                                <p class="card-text">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolorum
                                    culpa excepturi ab facilis? Est, tempore.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex flex-sm-row flex-column gap-3">
                            <img src="/storage/private/absen/" style="max-width: 50%;" class="rounded-3">
                            <div class="">
                                <div class="form-check p-0 m-0 d-flex gap-2">
                                    <input class="form-check-input m-0 " type="checkbox" checked
                                        onclick="return false;">
                                    <label class="custom-control-label m-0">12:00</label>
                                </div>
                                <p class="card-text">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolorum
                                    culpa excepturi ab facilis? Est, tempore.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex flex-sm-row flex-column gap-3">
                            <img src="/storage/private/absen/dummy.jpg" style="max-width: 50%;" class="rounded-3">
                            <div class="">
                                <div class="form-check p-0 m-0 d-flex gap-2">
                                    <input class="form-check-input m-0 " type="checkbox" checked
                                        onclick="return false;">
                                    <label class="custom-control-label m-0">12:00</label>
                                </div>
                                <p class="card-text">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolorum
                                    culpa excepturi ab facilis? Est, tempore.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
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
    // inits
    const kota = document.getElementById('kota');
    const tanggal = document.getElementById('tanggal');
    const baseUrl = 'api/absen'
    let url = `${baseUrl}?kota=${kota.value}&tanggal=${tanggal.value}`

    // functions
    function toHM(time) {
        if (time === undefined || time === null) {
            return null;
        }
        return time.slice(0, -3)
    }

    // event listeners
    $(document).ready(() => {

        const modalBody = document.querySelector('.modal-body');
        const cards = modalBody.querySelectorAll('.card');
        cards.forEach((card) => {
            card.setAttribute('hidden', true)
        });

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
            columns: [
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
                    data: 'waktu1',
                    className: 'text-center',
                    render: function (data, type, row) {
                        if (data !== null) {
                            if (type === 'display') {
                                return `
                                <div class="form-check p-0 justify-content-center m-0 d-flex gap-2">
                                    <input class="form-check-input m-0 " type="checkbox" id="absen-0-${row.id}" checked onclick="return false;">
                                    <label class="custom-control-label m-0" for="absen-0-${row.id}">${toHM(data)}</label>
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
                    data: 'waktu2',
                    className: 'text-center',
                    render: function (data, type, row) {
                        if (data !== null) {
                            if (type === 'display') {
                                return `
                                <div class="form-check p-0 justify-content-center m-0 d-flex gap-2">
                                    <input class="form-check-input m-0 " type="checkbox" id="absen-0-${row.id}" checked onclick="return false;">
                                    <label class="custom-control-label m-0" for="absen-0-${row.id}">${toHM(data)}</label>
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
                    data: 'waktu3',
                    className: 'text-center',
                    render: function (data, type, row) {
                        if (data !== null) {
                            if (type === 'display') {
                                return `
                                <div class="form-check p-0 justify-content-center m-0 d-flex gap-2">
                                    <input class="form-check-input m-0 " type="checkbox" id="absen-0-${row.id}" checked onclick="return false;">
                                    <label class="custom-control-label m-0" for="absen-0-${row.id}">${toHM(data)}</label>
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
                    data: 'waktu4',
                    className: 'text-center',
                    render: function (data, type, row) {
                        if (data !== null) {
                            if (type === 'display') {
                                return `
                                <div class="form-check p-0 justify-content-center m-0 d-flex gap-2">
                                    <input class="form-check-input m-0 " type="checkbox" id="absen-0-${row.id}" checked onclick="return false;">
                                    <label class="custom-control-label m-0" for="absen-0-${row.id}">${toHM(data)}</label>
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
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function (data, type) {
                        if (data === null) {
                            return ''
                        }
                        if (type === 'display') {
                            return `
                            <button data-teknisi="${data}" data-bs-toggle="modal" data-bs-target="#Modal" class="btn-detail-absen btn btn-link text-secondary font-weight-normal">
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
            }
        });

        table.on('draw', () => {
            $('.btn-detail-absen').on('click', (e) => {
                fetch('api/absen/' + e.target.dataset.teknisi)
                    .then(response => response.json())
                    .then(data => {
                        $('#ModalImg').attr('src', `/storage/private/profile/${data.user.foto_profil}`)
                        $('#ModalLabel').text(data.user.nama)
                        $('#ModalDate').text(data.tanggal)
                        let waktu = [
                            data.waktu1,
                            data.waktu2,
                            data.waktu3,
                            data.waktu4,
                        ];
                        let foto = [
                            data.foto1,
                            data.foto2,
                            data.foto3,
                            data.foto4,
                        ];
                        let ket = [
                            data.ket1,
                            data.ket2,
                            data.ket3,
                            data.ket4,
                        ];
                        cards.forEach((card, i) => {
                            if (waktu[i] === null) {
                                return
                            }
                            card.removeAttribute('hidden')
                            card.getElementsByTagName('img')[0].src = `/storage/private/absen/${foto[i]}`
                            card.getElementsByTagName('label')[0].innerHTML = waktu[i]
                            card.getElementsByTagName('p')[0].innerHTML = ket[i]
                        });
                    })
            });
        });
        $('#Modal').on('hide.bs.modal', function () {
            cards.forEach((card) => {
                card.setAttribute('hidden', true)
            });
        });


        $('#kota').on('change', function () {
            url = `${baseUrl}?kota=${kota.value}&tanggal=${tanggal.value}`
            table.ajax.url(url).load()
        });

        $('#tanggal').on('change', function () {
            url = `${baseUrl}?kota=${kota.value}&tanggal=${tanggal.value}`
            table.ajax.url(url).load()
        });
    })



</script>

@endpush