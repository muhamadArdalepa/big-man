<div class="form-group  {{ $class ?? null }}">
    <label for="{{ $name }}" class="form-control-label text-capitalize">{!! $label ?? str_replace('_', ' ', $name) !!}</label>
    <div class="input-group">
        @isset($prepend)
            @foreach ($prepend as $item)
                <span class="input-group-text">{{ $item }}</span>
            @endforeach
        @endisset
        <input type="{{ $type ?? 'text' }}" class="form-control" id="{{ $name }}"
            placeholder="{{ $placeholder ?? '' }}">
        @isset($append)
            @foreach ($append as $item)
                <span class="input-group-text">{{ $item }}</span>
            @endforeach
        @endisset

    </div>

    <div id="{{ $name }}Feedback" class="invalid-feedback text-xs"></div>
</div>
