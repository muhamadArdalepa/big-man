@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Kelola Wilayah Cabang'])
    <div class="container-fluid px-0 px-sm-4 py-4">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <div class="d-flex gap-3">
                    <div class="d-flex align-items-center">
                        <h5>Wilayah cabang BIG Net</h5>
                    </div>
                    <button class="btn-add btn btn-icon btn-3 btn-primary m-0 ms-auto" type="button" data-bs-toggle="modal"
                        data-bs-target="#Modal" data-bs-title="Tambah Wilayah">
                        <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                        <span class="btn-inner--text d-none d-sm-inline-block">Tambah Teknisi</span>
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
                                    Wilayah</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Keterangan</th>
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
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Tambah Wilayah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-dark">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_wilayah" class="form-control-label">Nama Wilayah</label>
                        <input type="text" id="nama_wilayah" class="form-control" placeholder="Nama Wilayah"
                            tabindex="1">
                        <div id="nama_wilayahFeedback" class="invalid-feedback text-xs"></div>
                    </div>
                    <div class="form-group">
                        <label for="ket" class="form-control-label">Keterangan</label>
                        <textarea id="ket" rows="3" class="form-control" placeholder="Keterangan"></textarea>
                        <div id="ketFeedback" class="invalid-feedback text-xs"></div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary me-2" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary" id="simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endpush
@include('components.dataTables')
@push('js')
    <script>
        const appUrl = '{{ env('APP_URL') }}/'
        const baseUrl = appUrl + 'api/wilayah'

        let table = $('#table').DataTable({
            ajax: {
                url: baseUrl,
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
            order: [
                [0, "desc"]
            ],
            columns: [

                {
                    data: 'id',
                    className: 'text-center',
                },
                {
                    data: 'nama_wilayah',

                },
                {
                    data: 'ket',

                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type) {
                        if (type === 'display') {
                            return `
                        <a href="#Modal" data-id="${data}" data-bs-toggle="modal" class="p-2 text-secondary btn-edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="javascript:;" data-id="${data}" class="text-danger btn-delete p-2">
                            <i class="fa-solid fa-xmark"></i>
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
            }
        });
        $('#Modal').on('shown-bs-modal', () => {
            $('.form-control').removeClass('is-invalid')
            $('.invalid-feedback').text('')
        })
        $('.btn-add').on('click', (e) => {
            isEdit = false;
            $('#ModalLabel').text('Tambah Wilayah')
            $('.form-control').val('')
        })

        table.on('draw', () => {
            $('.btn-edit').on('click', (e) => {
                $('#ModalLabel').text('Ubah Wilayah')
                wilayah_id = e.currentTarget.dataset.id;
                fetch(baseUrl + '/' + wilayah_id)
                    .then(response => response.json())
                    .then(data => {
                        $('#nama_wilayah').val(data.nama_wilayah)
                        $('#ket').val(data.ket)
                    })
                isEdit = true;
            })
            $('.btn-delete').on('click', (e) => {
                wilayah_id = e.currentTarget.dataset.id;
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menghapus data wilayah ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`${baseUrl}/${wilayah_id}`)
                            .then(function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.data.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                });
                                table.ajax.reload();
                            })
                            .catch(function(error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: error.response.data.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                });
                            });

                    }
                });
            })

        })

        $(document).on('click', '#simpan', function(e) {
            e.preventDefault();

            var requestData = {
                nama_wilayah: $('#nama_wilayah').val(),
                ket: $('#ket').val(),
            };

            var axiosConfig = {
                method: isEdit ? 'put' : 'post',
                url: isEdit ? baseUrl + '/' + wilayah_id : baseUrl,
                data: isEdit ? {
                    id: wilayah_id,
                    ...requestData
                } : requestData,
            };

            axios(axiosConfig)
                .then(function(response) {
                    $('#Modal').find('.form-control').val('');
                    $('#Modal').find('.form-control').removeClass('is-invalid');
                    $('.invalidFeedback').hide();
                    $('#Modal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.data.message,
                        timer: 1500,
                        showConfirmButton: false,
                    }).then(() => {
                        table.ajax.reload();
                    });
                })
                .catch(function(error) {
                    $('.invalid-feedback').text('');
                    $('.form-control').removeClass('is-invalid');

                    if (error.response && error.response.data && error.response.data.errors) {
                        var errors = error.response.data.errors;
                        for (const key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                $('#' + key).addClass('is-invalid is-invalid-image');
                                $('#' + key + 'Feedback').show().text(errors[key]);
                            }
                        }
                    }
                });
        });
    </script>
@endpush
