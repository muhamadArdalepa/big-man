<div class="modal fade" id="addPelangganModal" tabindex="-1" role="dialog" aria-labelledby="addPelangganModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="addPelangganModal-Content">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPelangganModalLabel">Tambah Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('components.forms.normal-input', [
                    'name' => 'namaPelanggan',
                    'label' => 'Nama',
                    'placeholder' => 'Nama',
                ])
                @include('components.forms.no_telp-input', [
                    'name' => 'no_telpPelanggan',
                    'label' => 'No Telepon/Whatsapp',
                    'placeholder' => 'eg. 628xxxxx',
                ])
                @include('components.forms.select-input', [
                    'name' => 'wilayah_idPelanggan',
                    'label' => 'Wilayah',
                    'id' => 'id',
                    'value' => 'nama_wilayah',
                    'placeholder' => 'Wilayah',
                    'option' => $wilayahs,
                ])
                @include('components.forms.normal-input', [
                    'name' => 'emailPelanggan',
                    'label' => 'email',
                    'type' => 'email',
                    'placeholder' => 'Email',
                ])

                <div class="d-flex align-items-center gap-3">

                    @include('components.forms.normal-input', [
                        'name' => 'passwordPelanggan',
                        'label' => 'password',
                        'placeholder' => 'Password',
                        'class' => 'flex-grow-1',
                    ])

                    <div class="form-group d-flex flex-column">
                        <label class="form-control-label opacity-0">-</label>
                        <button type="button" class="btn m-0 btn-primary btn-copy-password" data-bs-toggle="tooltip"
                            data-bs-title="Salin password">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>

                    <div class="form-group d-flex flex-column">
                        <label class="form-control-label opacity-0">-</label>
                        <button type="button" id="generate-btn" class="btn m-0 btn-warning"
                            onclick="generateRandomPassword()">Generate</button>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary me-2 " data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn bg-gradient-primary btn-simpan">Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        function generateRandomPassword() {
            var length = 6;
            var charset =
                "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            var password = "";

            for (var i = 0; i < length; i++) {
                var randomIndex = Math.floor(Math.random() * charset.length);
                password += charset.charAt(randomIndex);
            }
            $("#passwordPelanggan").val(password);
        }

        $("#addPelangganModal").on("shown.bs.modal", function() {
            generateRandomPassword();
            $(this).on("keypress", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    $("#addPelangganModal .btn-simpan").click();
                }
            });
        });

        $("#addPelangganModal").on("hide.bs.modal", function() {
            $("#addPelangganModal").find(".form-control").removeClass("is-invalid");
            $("#addPelangganModal").find(".invalidFeedback").hide();
            @if (isset($modal))
                $('{{ $modal }}').modal('show')
            @endif
        });

        $('#addPelangganModal .btn-simpan').on("click", () => {
            data = {
                nama: $("#namaPelanggan").val(),
                email: $("#emailPelanggan").val(),
                password: $("#passwordPelanggan").val(),
                no_telp: $("#no_telpPelanggan").val(),
                wilayah_id: $("#wilayah_idPelanggan").val(),
            };
            axios
                .post(`${appUrl}/api/pelanggan`, data)
                .then((response) => {
                    $("#addPelangganModal").find(".form-control").val("");
                    $("#addPelangganModal").find(".form-select").val("");
                    $("#addPelangganModal").find("#no_telp").val("62");
                    @if (!isset($modal))
                        table.ajax.reload();
                    @endif
                    $("#addPelangganModal").modal("hide");
                    @include('components.swal.success')
                })
                .catch((error) => {
                    $("#timModal").find(".invalid-feedback").text("");
                    $("#timModal").find(".form-select").removeClass("is-invalid");
                    var errors = error.response.data.errors;
                    if (error.response.status == 422) {
                        for (const key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                $("#" + key + 'Pelanggan').addClass("is-invalid");
                                $("#" + key + 'Pelanggan' + "Feedback").show();
                                $("#" + key + 'Pelanggan' + "Feedback").text(errors[key]);
                            }
                        }
                    } else {
                        @include('components.swal.error')
                        $("#timModal").modal("hide");
                    }
                });
        });

        function deletePelanggan(id) {
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin menghapus data pelanggan ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/pelanggan/delete/${id}`,
                        type: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false,
                            });
                            table.ajax.reload();
                        },
                        error: function(response) {
                            Swal.fire("Error", response.message, "error");
                        },
                    });
                }
            });
        }
    </script>
@endpush
