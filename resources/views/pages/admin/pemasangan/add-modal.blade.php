@push('modal')
    <div class="modal fade" id="addPemasanganModal" tabindex="-1" role="dialog" aria-labelledby="addPemasanganModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-md-down modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPemasanganModalLabel">Tambah Pemasangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-3 pelanggan-row">

                        @include('components.forms.select-input', [
                            'name' => 'pelanggan_idPemasangan',
                            'label' => 'Pelanggan',
                            'placeholder' => '',
                            'class' => 'flex-grow-1',
                        ])

                        @include('pages.admin.pemasangan.btn-group-add-pelanggan')
                    </div>

                    @include('components.forms.select-input', [
                        'name' => 'marketer_idPemasangan',
                        'label' => 'Marketer',
                        'placeholder' => '',
                    ])

                    @include('components.forms.normal-input', [
                        'name' => 'nikPemasangan',
                        'label' => 'NIK',
                        'placeholder' => 'cth. 6171234567890123',
                    ])

                    @include('components.forms.alamat-input', [
                        'name' => 'alamatPemasangan',
                        'label' => 'Alamat Rumah',
                    ])

                    @include('components.forms.normal-input', [
                        'name' => 'koordinat_rumahPemasangan',
                        'placeholder' => 'cth. -0.123,110.123',
                        'label' => 'Koordinat rumah',
                    ])

                    @include('components.forms.select-input', [
                        'name' => 'paket_idPemasangan',
                        'placeholder' => '-- Pilih Paket --',
                        'label' => 'Pilih Paket',
                        'value' => 'nama_paket',
                        'id' => 'id',
                        'option' => $pakets,
                    ])

                    @include('components.forms.group-input', [
                        'name' => 'total_tarikanPemasangan',
                        'label' => 'Total Tarikan',
                        'type' => 'number',
                        'append' => ['Meter'],
                        'placeholder' => 'cth. 150',
                    ])


                    <div class="row ">
                        <div class="col-md-6">
                            @include('components.forms.image-input', [
                                'name' => 'foto_ktpPemasangan',
                                'modal' => 'addPemasanganModal',
                                'label' => 'Foto KTP',
                            ])
                        </div>
                        <div class="col-md-6">
                            @include('components.forms.image-input', [
                                'name' => 'foto_rumahPemasangan',
                                'modal' => 'addPemasanganModal',
                                'label' => 'Foto Rumah',
                            ])
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary me-2" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary btn-simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('js')
    <script>
        let isEditPemasangan = false;
        let btnGroupAddPelanggan = `@include('pages.admin.pemasangan.btn-group-add-pelanggan')`
        let koordinatRumah = $('#koordinat_rumahPemasangan').val();
        let isHasPelanggan = false;

        function editPemasangan(id) {
            $('.btn-group-add-pelanggan').remove()
            console.log(id);
            isEditPemasangan = true;
            pemasangan_id = id
            select2pelanggan(wilayah)
            select2marketer(wilayah)
            deleteSelectedMarketer();
            deleteSelectedPelanggan();
            axios.get(`${appUrl}/api/pemasangan/${id}/edit`)
                .then(response => {
                    data = response.data;
                    pelanggan_id = data.pelanggan_id
                    drawSelectedPelanggan(data.pelanggan);
                    if (data.marketer != null) {
                        drawSelectedMarketer(data.marketer);
                    }
                    $('#nikPemasangan').val(data.nik);
                    $('#alamatPemasangan').val(data.alamat);
                    $('#koordinat_rumahPemasangan').val(data.koordinat_rumah);
                    $('#paket_idPemasangan').val(data.paket_id);
                    $('#foto_rumahPemasangan').prev().attr('src', `${appUrl}/storage/private/${data.foto_rumah}`);
                    $('#foto_ktpPemasangan').prev().attr('src', `${appUrl}/storage/private/${data.foto_ktp}`);
                    $('#addPemasanganModal').find('.form-control-image').prev().removeClass('d-none')

                })
        }

        $('.btn-add-pemasangan').on('click', () => {
            if (isEditPemasangan) {
                deleteSelectedPelanggan();
                $('#addPemasanganModal').find('.form-control').val('')
                $('#addPemasanganModal').find('.form-select').prop('selectedIndex', 0)
                $('#addPemasanganModal').find('.form-control-image').prev().addClass('d-none')
                $('#addPemasanganModal').find('.form-control-image').prev().removeAttr('src')
            }
            if (pelanggan_id == null) {
                select2pelanggan(wilayah);
            }

            if (marketer_id == null) {
                select2marketer(wilayah);
            }

            isEditPemasangan = false;
        });


        function trPelanggan(pelanggan) {
            no_telp = pelanggan.no_telp + '';
            no = no_telp.replace('628', '08');
            return $(`<div class="form-control m-0 d-flex gap-2">
                <img src="/storage/private/${pelanggan.foto_profil}" class="avatar-sm rounded-3">
                <div class="d-flex flex-column lh-1">${pelanggan.text}
                    <span class="text-sm">${no}</span>
                </div>
            </div>`);
        }

        function tsPelanggan(pelanggan) {
            return $(`<span class="text-muted">Cari pelanggan . . . </span>`);
        };

        function select2pelanggan(wilayahId) {
            $('#pelanggan_idPemasangan').select2({
                ajax: {
                    url: `${appUrl}/api/pemasangan/select2-pelanggan?wilayah=${wilayahId}`,
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
                templateResult: trPelanggan,
                templateSelection: tsPelanggan,
                dropdownParent: $('#addPemasanganModal'),
                theme: 'bootstrap-5',
            });
        }

        $('#pelanggan_idPemasangan').on('change', e => {
            if (e.target.value == '') {
                return
            }
            pelanggan_id = $('#pelanggan_idPemasangan').val()
            axios.get(`${appUrl}/api/pelanggan/${pelanggan_id}`)
                .then(response => {
                    data = response.data
                    drawSelectedPelanggan(data);
                })
                .catch(errors => {
                    error = errors.response.data.message;
                    alert(error);
                })
        });

        function drawSelectedPelanggan(data) {
            $('.btn-group-add-pelanggan').remove()
            btnRemove = isEditPemasangan ? '' : `<button class="btn btn-link text-danger btn-delete-pelanggan fw-normal ms-auto p-0 me-1">
                <i class="fa-solid fa-xmark"></i>
            </button>`
            $('[for="pelanggan_idPemasangan"]').after(`<div id="selected_pelanggan" class="form-control m-0 d-flex gap-2 align-items-center">
                <img src="/storage/private/${data.foto_profil}" class="avatar-sm rounded-3">
                <div class="d-flex flex-column lh-1">${data.nama}
                    <span class="text-sm">${(data.no_telp+'').replace('628','08')}</span>
                </div>
                ${btnRemove}
            </div>`);

            $('.btn-delete-pelanggan').on('click', () => {
                deleteSelectedPelanggan();
            })


            $('#pelanggan_idPemasangan').addClass('d-none');
            $('#pelanggan_idPemasangan').select2('destroy');
        }

        function deleteSelectedPelanggan() {
            pelanggan_id = null;
            $('.pelanggan-row').append(btnGroupAddPelanggan)
            $('#pelanggan_idPemasangan').prop('selectedIndex', 0)
            $('#pelanggan_idPemasangan').removeClass('d-none');
            $('#selected_pelanggan').remove();
            if (!isEditPemasangan) {
                select2pelanggan(wilayah)
            }
        }


        function trMarketer(marketer) {
            return $(`<div class="form-control m-0 d-flex gap-2">
                <img src="/storage/private/${marketer.foto_profil}" class="avatar-sm rounded-3">
                <div class="d-flex flex-column lh-1">${marketer.text}
                    <span class="text-sm">${marketer.speciality}</span>
                </div>
            </div>`);
        }

        function tsMarketer(markter) {
            return $(`<span class="text-muted">Cari marketer . . . </span>`);
        };

        function select2marketer(wilayahId) {
            $('#marketer_idPemasangan').select2({
                ajax: {
                    url: `${appUrl}/api/pemasangan/select2-marketer?wilayah=${wilayahId}`,
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
                templateResult: trMarketer,
                templateSelection: tsMarketer,
                dropdownParent: $('#addPemasanganModal'),
                theme: 'bootstrap-5',
            });
        }

        $('#marketer_idPemasangan').on('change', e => {
            if (e.target.value == '') {
                return
            }
            marketer_id = $('#marketer_idPemasangan').val()
            axios.get(`${appUrl}/api/teknisi/${marketer_id}`)
                .then(response => {
                    data = response.data
                    drawSelectedMarketer(data);
                })
                .catch(errors => {
                    error = errors.response.data.message;
                    alert(error);
                })
        });

        function drawSelectedMarketer(data) {
            $('[for="marketer_idPemasangan"]').after(`<div id="selected_marketer" class="form-control m-0 d-flex gap-2 align-items-center">
                <img src="/storage/private/${data.foto_profil}" class="avatar-sm rounded-3">
                <div class="d-flex flex-column lh-1">${data.nama}
                    <span class="text-sm">${data.speciality}</span>
                </div>
                <button class="btn btn-link text-danger btn-delete-marketer fw-normal ms-auto p-0 me-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>`);

            $('.btn-delete-marketer').on('click', () => {
                deleteSelectedMarketer();
            })

            $('#marketer_idPemasangan').addClass('d-none');
            $('#marketer_idPemasangan').select2('destroy');
        }

        function deleteSelectedMarketer() {
            marketer_id = null;
            $('#marketer_idPemasangan').prop('selectedIndex', 0)
            $('#marketer_idPemasangan').removeClass('d-none');
            $('#selected_marketer').remove();
            if (!isEditPemasangan) {
                select2marketer(wilayah)
            }
        }



        $('#addPemasanganModal .btn-simpan').on('click', e => {
            pemasanganData = {
                pelanggan_id: pelanggan_id,
                marketer_id: marketer_id,
                nik: $('#nikPemasangan').val(),
                foto_ktp: $('#foto_ktpPemasangan')[0].files[0],
                foto_rumah: $('#foto_rumahPemasangan')[0].files[0],
                alamat: $('#alamatPemasangan').val(),
                koordinat_rumah: $('#koordinat_rumahPemasangan').val(),
                paket_id: $('#paket_idPemasangan').val(),
                total_tarikan: $('#total_tarikanPemasangan').val(),
            }

            if (isEditPemasangan) {
                pemasanganData._method = 'put';
                axios.post(`${appUrl}/api/pemasangan/${pemasangan_id}`, pemasanganData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response => {
                        resolvePemasangan(response)
                    })
                    .catch(error => {
                        rejectPemasangan(error)
                    })
                return;
            }
            axios.post(`${appUrl}/api/pemasangan`, pemasanganData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    resolvePemasangan(response)
                })
                .catch(error => {
                    rejectPemasangan(error)
                })

        })

        function handleOpenMapalamatPemasangan() {
            koordinatField = '#koordinat_rumahPemasangan'
            alamatField = '#alamatPemasangan'
        }

        function resolvePemasangan(response) {
            $("#addPemasanganModal").find(".invalid-feedback").text("");
            $("#addPemasanganModal").find(".form-select, .form-control").removeClass("is-invalid");
            $('#addPemasanganModal').find('.form-control').text('')
            $('#addPemasanganModal').find('.form-control, .form-select').val('')
            $('#addPemasanganModal').find('.form-control-image').prev().removeAttr('src')
            pelanggan_id = null
            table.ajax.reload();
            $('#addPemasanganModal').modal('hide');
            @include('components.swal.success')
        }

        function rejectPemasangan(error) {
            $("#addPemasanganModal").find(".invalid-feedback").text("");
            $("#addPemasanganModal").find(".form-select, .form-control").removeClass("is-invalid");
            var errors = error.response.data.errors;
            if (error.response.status == 422) {
                for (const key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        $("#" + key + 'Pemasangan').addClass("is-invalid");
                        $("#" + key + 'Pemasangan' + "Feedback").show();
                        $("#" + key + 'Pemasangan' + "Feedback").text(errors[key]);
                    }
                }
            } else {
                @include('components.swal.error')
                $("#addPemasanganModal").modal("hide");
            }
        }
    </script>
@endpush
