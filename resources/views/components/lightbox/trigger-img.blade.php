<img 
    src="{{$src ?? null}}"
    class="{{$class ?? null}}" 
    style="cursor: pointer;"
    onclick="$('#lightbox img').attr('src',this.src);@if ($modal) $('#{{ $modal }}').modal('hide');$('#lightbox').attr('data-modal','{{ $modal }}'); @endif $('#lightbox').modal('show')"     
/>