<div class="form-group  {{$class ?? null}}">
    <label for="{{ $name }}" class="form-control-label text-capitalize">{!! $label ?? str_replace('_', ' ', $name) !!}</label>
    <input type="{{ $type ?? 'text' }}" class="form-control" id="{{ $name }}"
        placeholder="{{ $placeholder ?? '' }}">
    <div id="{{ $name }}Feedback" class="invalid-feedback text-xs"></div>
</div>
