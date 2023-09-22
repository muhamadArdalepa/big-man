<div class="modal fade " id="lightbox" tabindex="-1" aria-hidden="true">
    <div style="max-width: unset"
        class="modal-dialog d-flex position-relative w-100 justify-content-center align-items-center h-100 p-3 m-0">
        <img style="margin: 0 auto" class="d-none">
    </div>
</div>

@push('js')
    <script>
        function openLightbox(e) {
            $('#lightbox img').attr('src', e.src);
            $('#lightbox').modal('show')
        }
        
        $('#lightbox').on('shown.bs.modal', () => {
            $('#lightbox img').attr('class', '')
            if ($('#lightbox img')[0].offsetWidth > window.innerWidth) {
                $('#lightbox img').attr('class', 'w-100')
            }
            if ($('#lightbox img')[0].offsetHeight > window.innerHeight) {
                $('#lightbox img').attr('class', 'h-100')
            }
        })
        
        $('#lightbox').on('hide.bs.modal', () => {
            $('#' + $('#lightbox').attr('data-modal')).modal('show');
        })
    </script>
@endpush
