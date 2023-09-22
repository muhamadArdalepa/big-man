@php($modal = $modal ?? null)

<div class="form-group">
    <label for="{{ $name }}" class="form-control-label text-capitalize">{{$label ?? str_replace('_',' ',$name)}}</label>
    <img class="w-100 rounded-3" style="cursor: pointer;"
        onclick="$('#lightbox img').attr('src',this.src);@if ($modal) $('#{{ $modal }}').modal('hide');$('#lightbox').attr('data-modal','{{ $modal }}'); @endif $('#lightbox').modal('show')
    " />
    <input type="file" id="{{ $name }}" class="form-control-image form-control form-control-sm"
    accept="image/*"    
    onchange="if (this.files && this.files[0]) {reader = new FileReader(); reader.onload = (e) => {this.previousElementSibling.src = e.target.result};reader.readAsDataURL(this.files[0])}" />
    <div id="{{ $name }}Feedback" class="invalid-feedback text-xs"></div>
</div>