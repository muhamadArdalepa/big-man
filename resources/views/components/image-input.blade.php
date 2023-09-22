<div class="form-group">
    <label for="{{ $name }}" class="form-control-label">Foto KTP</label>
    <input type="file" id="{{ $name }}" class="d-none">
    <div class="rounded-3 border overflow-hidden position-relative img-container" style="cursor: pointer;"
        onclick="document.getElementById('{{ $name }}').click()">
        <img src="" class="img-preview w-100 d-none">
        <div class="text-center top-50 start-50 position-absolute img-placeholder"
            style="transform: translate(-50%, -50%);">
            <i class="fa-regular fa-image fa-2xl mt-3"></i>
            <div class="text-sm opacity-7 mt-3">Upload Foto</div>
        </div>
    </div>
    <div id="{{ $name }}Feedback" class="invalid-feedback text-xs"></div>
</div>
