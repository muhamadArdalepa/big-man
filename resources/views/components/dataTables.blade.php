@push('css')
    <link href="{{ asset('assets/plugins/DataTables/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/Buttons/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/custom-dataTables.css') }}" rel="stylesheet" />
@endpush

@push('js')
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/DataTables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/DataTables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/Buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/Buttons/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/Buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/Buttons/buttons.print.min.js') }}"></script>
@endpush
