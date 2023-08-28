@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@push('css')
    <link href="{{ asset('assets/css/dataTables.css') }}" rel="stylesheet" />
@endpush

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Pekerjaan'])
<div class="container-fluid px-0 px-sm-4 py-sm-4">
    <div class="row">
        <div class="col-sm-8">
            <div class="card mb-4">
                <div class="card-header align-items-center d-flex gap-2">
                    <h6 class="m-0">
                        Pekerjaan Berlangsung
                    </h6>
                    <span class="badge bg-gradient-primary ms-auto">{{$pekerjaan->nama_pekerjaan}}</span>
                    <span class="badge bg-gradient-warning">TIM {{$pekerjaan->tim_id}}</span>
                </div>
                <div class="card-body py-0">
                    <div>{{$pekerjaan->detail}}</div>
                </div>
                <div class="card-footer d-flex align-items-center">
                    <a href="{{route('pekerjaan.show',$pekerjaan->id)}}" class="btn bg-gradient-primary">Detail</a>
                    <div class="text-end ms-auto">
                        <small class="">Ditugaskan Pada</small>
                        <small class="">{{$pekerjaan->created_atFormat}}</small>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header pb-0">
            <h6 class="m-0">
                Riwayat Pekerjaan
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="table" width="100%" cellspacing="0">
                    <thead>
                        <tr class="align-middle">
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">
                                Tanggal</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                TIM</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Jenis Pekerjaan</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Detail</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Status</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Poin</th>
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
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Tambah Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary me-2" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn bg-gradient-primary" id="simpan">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endpush
@include('components.dataTables')
@push('js')


<script>
    // init
    const appUrl = `{{env('APP_URL')}}`
    const baseUrl = `${appUrl}/api/teknisi-pekerjaan`

    let url = `${baseUrl}`

    // functions

    // event listeners
    $(document).ready(function () {
        let table = $('#table').DataTable({
            ajax: {
                url: url,
                type: 'GET',
                serverside: true,
                dataSrc: '',
            },
            dom: "<'d-flex flex-column flex-md-row gap-3 align-items-center '<'d-flex align-items-center w-100 w-sm-auto'<'whitespace-nowrap'B><'ms-sm-3 ms-auto'l>><'ms-0 ms-md-auto'f>>" +
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
                    data: 'created_at',
                    className: 'text-sm text-center',
                    render: function (data, type, row) {
                        if (type === 'display') {
                            return row.created_atFormat
                        }
                        return data
                    }

                },
                {
                    data: 'tim_id',
                    render: function (data, type) {
                        if (type === 'display') {
                            return `<div class="text-secondary font-weight-bolder">TIM ${data}</div>`
                        }
                        return data;
                    }
                },
                {
                    data: 'nama_pekerjaan',
                    className: 'w-0'
                },
                {
                    data: 'detail',
                    className:'extend-min-width'
                },

                {
                    data: 'status',
                    className: 'text-center w-0',
                    render: function (data, type, row) {
                        if (type === 'display') {
                            let badge = []
                            switch (data) {
                                case 1:
                                    badge[0] = 'warning'
                                    badge[1] = 'Pending'
                                    badge[2] = ''
                                    break;
                                case 2:
                                    badge[0] = 'secondary'
                                    badge[1] = 'Diterima'
                                    badge[2] = ''
                                    break;
                                case 3:
                                    badge[0] = 'secondary'
                                    badge[1] = 'Diproses'
                                    badge[2] = ''
                                    break;
                                case 4:
                                    badge[0] = 'success'
                                    badge[1] = 'Selesai'
                                    badge[2] = 'pada ' + row.updated_atFormat
                                    break;
                                default:
                                    break;
                            }
                            return `
                        <span class="badge badge-sm text-xxs bg-gradient-${badge[0]}">${badge[1]} ${badge[2]}</span>
                        `
                        }
                        return data
                    }
                },
                {
                    data: 'poin',
                    className: 'w-0 text-center',
                    render: function (data, type) {
                        if (type === 'display') {
                            return `<span class="text-sm font-weight-bolder text-success">+${data}</span>`
                        }
                        return data;
                    }
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    className: 'text-center p-0',
                    render: function (data, type) {
                        if (type === 'display') {
                            return `
                        <a href="/teknisi/${data}" class="btn btn-link text-secondary font-weight-normal">
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
    })

    // end event listners
</script>

@endpush