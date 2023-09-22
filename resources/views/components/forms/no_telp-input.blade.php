<div class="form-group  {{$class ?? null}}">
    <label for="{{ $name }}" class="form-control-label text-capitalize">{!! $label ?? str_replace('_', ' ', $name) !!}</label>
    <div class="input-group">
        <span class="input-group-text">+</span>
        <input type="number" class="form-control" class="form-control" id="{{ $name }}"
        placeholder="{{ $placeholder ?? '' }}" value="62">
    </div>
    <div id="{{ $name }}Feedback" class="invalid-feedback text-xs"></div>
</div>
