@push('modal')
    <div class="modal fade" id="timModal" tabindex="-1" role="dialog" aria-labelledby="timModalLabel" aria-hidden="true">
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
@endpush

@push('js')
    <script>
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
            if (e.target.value == '') {
                return;
            }
            tim_id = $('#tim_id').val()
            getTimUrl = `${appUrl}/api/pekerjaan/data-tim/${tim_id}`;
            axios.get(getTimUrl)
                .then(response => {
                    d = response.data
                    console.log(d);
                    c = '',
                        d.forEach(a => {
                            c += `<div class="d-flex align-items-center gap-2">
                                <img src="/storage/private/${a.user.foto_profil}" class="avatar-sm rounded-3">
                                <div class="text-sm">${a.user.nama}</div>
                                <span class="badge bg-gradient-danger text-xxs">${a.user.speciality}</span>
                            </div>`
                        });
                    $('.tim-container').append(`<div class="form-control m-0 d-flex flex-column gap-2">
                                                <p class="mb-2 d-flex">
                                                    TIM ${response.data[0].tim_id}
                                                    <button class="btn btn-link text-danger btn-delete-tim fw-normal ms-auto">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </p>
                                                ${c}
                                            </div>`);
                    $('.btn-delete-tim').on('click', e => {
                        tim_id = null;
                        $('#tim_id').prop('selectedIndex',0)
                        $('#tim_id').removeClass('d-none');
                        initSelect2(wilayah);
                        $('.tim-container').text('');
                    })

                    $('#tim_id').addClass('d-none');
                    $('#tim_id').select2('destroy');
                });
        })

        $('#timModal .btn-simpan').on('click', () => {
            data = {
                tim_id: tim_id,
                poin: $('#poin').val()
            }
            axios.post(`${baseUrl}/store-pekerjaan/` + {{ $tipe }}_id, data)
                .then(response => {
                    table.ajax.reload()
                    $('#timModal').modal('hide');
                    @include('components.swal.success')
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
                        @include('components.swal.error')
                        $('#timModal').modal('hide');
                    }

                })
        })
    </script>
@endpush
