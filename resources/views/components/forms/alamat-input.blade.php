<div class="form-group">
    <div class="d-flex align-items-end">
        <label for="{{ $name }}"
            class="form-control-label text-capitalize">{{ $label ?? str_replace('_', ' ', $name) }}</label>
        <button id="btn-open-map{{ $name }}" class="btn ms-auto p-2 btn-link text-secondary font-weight-normal"
            data-bs-toggle="modal" data-bs-target="#mapModal">
            <i class="fa-solid fa-location-dot"></i>
            <span>Pilih Lewat Peta</span>
        </button>
    </div>
    <textarea class="form-control" id="{{ $name }}" rows="3" placeholder="cth. Jalan Profesor Dokter Haji Hadari Nawawi, Bansir Laut, Pontianak Tenggara"></textarea>
    <div id="{{ $name }}Feedback" class="invalid-feedback text-xs"></div>
</div>

@push('js')
    <script>
        $('#btn-open-map{{ $name }}').on('click', () => {
            handleOpenMap{{ $name }}()
        })
    </script>
@endpush
