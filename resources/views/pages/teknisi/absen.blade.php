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
                    Rekap Absen Saya
                </h6>
                @if($action)
                <div class="d-flex ms-sm-auto w-100 w-sm-auto gap-3" id="absenAction">
                    <div class="d-flex align-items-center flex-grow-1 flex-sm-grow-0">
                        <div>Sudah waktunya absen</div>
                    </div>
                    <div class="d-flex align-items-center flex-grow-1 flex-sm-grow-0">
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#absenModal">Absen
                            Sekarang</button>
                    </div>
                </div>
                @endif
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
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
<div class="modal fade" id="absenModal" tabindex="-1" role="dialog" aria-labelledby="absenModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header gap-2">
                <img src="{{route('storage.private','profile/'. auth()->user()->foto_profil)}}" alt="foto profil"
                    height="50" class="rounded-3" id="absenModalImg">
                <div class="d-flex flex-column justify-content-end">
                    <h5 class="m-0 lh-1" id="absenModalLabel">Absen {{$whichAbsen}}</h5>
                </div>
                <div class="ms-auto text-end">
                    <p class="m-0" id="absenModalDate"></p>
                    <p class="m-0">{{$date}}</p>
                </div>
            </div>
            <div class="modal-body">
                <input type="hidden" name="image" id="image-tag">
                <div class="mb-3" id="location"></div>
                <div id="myCamera" class="d-block rounded-3 overflow-hidden">
                </div>
                <textarea id="ket" class="form-control my-3" rows="3" placeholder="Keterangan pekerjaan"></textarea>
                <div class="d-flex justify-content-between gap-3">
                    <button id="cameraButton" class="btn bg-gradient-primary btn-lg flex-grow-1">Ambil Foto</button>
                    <button id="absenButton" class="btn bg-gradient-suuccess btn-lg flex-grow-1">Absen</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header gap-2">
                <h5 class="m-0 lh-1" id="ModalLabel">Detail Absen</h5>
                <p class="m-0 ms-auto" id="ModalDate"></p>
            </div>
            <div class="modal-body">
                @for($i=1;$i<=4;$i++)
                <div class="card mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex flex-sm-row flex-column gap-3">
                            <img src="/storage/private/absen/" style="max-width: 50%;" class="rounded-3">
                            <div class="w-100">
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <div class="form-check p-0 m-0 d-flex gap-2">
                                        <input class="form-check-input m-0 " type="checkbox" checked
                                        onclick="return false;">
                                        <label class="custom-control-label m-0">12:00</label>
                                    </div>
                                    <small class="modal-koordinat ms-auto fs-xxs opacity-7"></small>
                                </div>
                                <p class="modal-alamat fs-xxs opacity-7 lh-sm"></p>
                                <p class="modal-ket"></p>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
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
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

<script>
    // inits
    const kota = document.getElementById('kota');
    const tanggal = document.getElementById('tanggal');
    const authId = `{{ auth()->user()->id}}`
    const baseUrl = `{{env('APP_URL')}}/api/teknisi-absen`
    let koordinat;
    let alamat;
    let url = baseUrl;

    // functions
    function toHM(time) {
        if (time === undefined || time === null) {
            return null;
        }
        return time.slice(0, -3)
    }

    function startCamera(btn) {
        const cameraWidth = document.getElementById('myCamera').offsetWidth
        Webcam.set({
            width: cameraWidth,
            height: cameraWidth * 3 / 4,
            image_format: 'jpeg',
            jpeg_quality: 50
        });

        Webcam.attach('#myCamera');
        btn.setAttribute('onclick', 'takePicture(this)')
        btn.classList.replace('bg-gradient-warning', 'bg-gradient-primary');
        btn.innerHTML = 'Ambil Foto'
        $('#absenButton').hide()
        getLocation()
    }


    function takePicture(btn) {
        Webcam.snap(function (data_uri) {
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
        koordinat = latitude+', '+longitude
        // Buat permintaan ke API Nominatim
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`)
            .then(response => response.json())
            .then(data => {
                var address = data.display_name;
                alamat = address
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
                document.getElementById('location').textContent = 'Waktu permintaan untuk mendapatkan lokasi pengguna habis.';
                break;
            case error.UNKNOWN_ERROR:
                document.getElementById('location').textContent = 'Terjadi kesalahan yang tidak diketahui.';
                break;
        }
    }

    function getAddress(koordinat) {
        var latlong = koordinat.split(', ');
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlong[0]}&lon=${latlong[1]}`)
            .then(response => response.json())
            .then(data => {
                return data.display_name;
            })
            .catch(error => {
                return 'Gagal mendapatkan alamat';
            });
    }



    // event listeners
    $(document).ready(() => {

        $('#absenButton').hide()
        let modalJamInterval;
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
                    data: 'tanggal',
                    render: function (data, type, row) {
                        if (type === 'display') {
                            return row.tanggalParsed
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
            order: [[0, 'desc']]
        });
        const modalBody = document.querySelector('#Modal .modal-body');
        const cards = modalBody.querySelectorAll('#Modal .card');
        cards.forEach((card) => {
            card.setAttribute('hidden', true)
        });

        table.on('draw', () => {
            $('.btn-detail-absen').on('click', (e) => {
                fetch('api/teknisi-absen/' + e.target.dataset.absen)
                    .then(response => response.json())
                    .then(data => {
                        $('#ModalDate').text(data.tanggalFormat)
                        $('#ModalDate').text(data.tanggalFormat)
                        let waktu = [
                            data.waktu1,
                            data.waktu2,
                            data.waktu3,
                            data.waktu4,
                        ];
                        let koordinat = [
                            data.koordinat1,
                            data.koordinat2,
                            data.koordinat3,
                            data.koordinat4,
                        ];
                        let alamat = [
                            data.alamat1,
                            data.alamat2,
                            data.alamat3,
                            data.alamat4,
                        ];
                        let lokasi = [
                            data.lokasi1,
                            data.lokasi2,
                            data.lokasi3,
                            data.lokasi4,
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
                            card.getElementsByClassName('modal-koordinat')[0].innerHTML = koordinat[i];
                            card.getElementsByClassName('modal-alamat')[0].innerHTML = alamat[i];
                            card.getElementsByTagName('img')[0].src = `/storage/private/absen/${foto[i]}`
                            card.getElementsByTagName('label')[0].innerHTML = waktu[i]
                            card.getElementsByClassName('modal-ket')[0].innerHTML = ket[i]
                        });
                    })
            });
        });

        $('#absenModal').on('shown.bs.modal', function () {
            startCamera(document.getElementById('cameraButton'))
            $('#absenModalDate').text(updateClock())
            modalJamInterval = setInterval(() => {
                $('#absenModalDate').text(updateClock())
            }, 1000);
        });

        $('#absenModal').on('hide.bs.modal', function () {
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
                    image: $('#image-tag').val(),
                    koordinat: koordinat,
                    alamat: alamat,
                    ket: $('#ket').val(),
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
                    }
                    else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false,
                        });
                        $('#absenAction').hide()
                        $('#absenModal').modal('hide');
                        $('#ket').val('')
                        Webcam.reset()
                        table.ajax.reload()
                    }
                }
            });
        })
    })



</script>

@endpush