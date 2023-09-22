<div class="form-group {{$class ?? null}}">
    <label for="{{ $name }}" class="form-control-label text-capitalize">{!! $label ?? str_replace('_', ' ', $name) !!}</label>
    <select class="form-select" id="{{ $name }}">
        @if (isset($placeholder))
            <option selected disabled>{{$placeholder}}</option>
        @endif
        @if (isset($option))
            @foreach ($option as $o)
                <option value="{{ $o[$id] }}">{{ $o[$value] }}</option>
            @endforeach
        @endif
    </select>
    <div id="{{ $name }}Feedback" class="invalid-feedback text-xs"></div>
</div>
