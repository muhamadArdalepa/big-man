@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pelanggan'])
    <div class="container-fluid px-0 px-sm-4 py-4">
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



                    <button class="btn btn-icon bg-gradient-danger m-0 ms-auto align-self-end btn-add-pemasangan"
                        data-bs-toggle="modal" data-bs-target="#addPelangganModal">
                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                        <span class="btn-inner--text d-none d-sm-inline-block">Tambah Pelanggan</span>
                    </button>

                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">
                                    #</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Pelanggan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    No Telepon</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Alamat</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Aksi</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
@include('components.datatables.dataTables')
@push('js')
    <script>
        const baseUrl = appUrl + '/api/pelanggan'
        let url = baseUrl + '?wilayah=' + $('#wilayah').val()
        let table = $('#table');

        $(document).ready(() => {
            table = $('#table').DataTable({
                ajax: {
                    url: url,
                    type: 'GET',
                    serverside: true,
                    dataSrc: '',
                },
                dom: @include('components.datatables.dom'),
                buttons: [
                    'excel'
                ],
                order: [
                    [0, "desc"]
                ],
                columns: [

                    {
                        data: 'id',
                        className: 'text-center',
                    },
                    {
                        data: 'nama',
                        render: function(data, type, row) {

                            if (type === 'display') {
                                return `<div class="d-flex flex-row-reverse justify-content-end align-items-center">
                                <div class="ms-3" style="white-space: nowrap;">
                                    <div>` + data + `</div>
                                </div>
                                <img class="rounded-3" src="{{ env('APP_URL') }}/storage/private/${row.foto_profil}" alt="foto profil" height="35">
                            </div>
                         `
                            }
                            return data;
                        }
                    },
                    {
                        data: 'email',
                        render: function(data, type) {
                            if (type === 'display') {
                                return `<button class="btn btn-link m-0 px-2 py-1 text-secondary fw-normal" onclick="copyText('body','Email','${data}')" data-bs-toggle="tooltip" data-bs-title="Salin email">${data}</button>`
                            }
                            return data;
                        }
                    },
                    {
                        data: 'no_telp',
                        render: function(data, type) {
                            if (type === 'display') {
                                no = '0' + data.substring(2);
                                return `<div class="d-flex flex-row-reverse justify-content-end">
                                    <button class="btn btn-link m-0 px-2 py-1 text-secondary fw-normal btn-copy-number" onclick="copyText('body','Nomor','${no}')" data-bs-toggle="tooltip" data-bs-title="Salin nomor">${no}</button>
                                    <a target="blank" href="https://wa.me/${data}" class="btn btn-circle me-1 bg-gradient-success" data-bs-toggle="tooltip" data-bs-title="Hubungi Whatsapp"><i class="fa-brands fa-whatsapp"></i></a>
                                </div>`
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
                            if (type === 'display') {
                                return `
                        <a href="${appUrl}/pelanggan/${data}" class="btn btn-link text-secondary font-weight-normal">
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
            });
        })

        table.on('draw.dt', () => {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        })


        $('#wilayah').on('change', () => {
            url = baseUrl + '?wilayah=' + $('#wilayah').val()
            table.ajax.url(url).load()
        })

    </script>
@endpush
@include('pages.admin.pelanggan.add-modal')
