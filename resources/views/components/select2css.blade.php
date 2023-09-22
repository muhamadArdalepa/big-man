<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    .modal>div>div>div.modal-body span>span.selection>span {
        border-radius: .5rem;
        box-shadow: none;
        border-color: #d2d6da;
        padding: 0.5rem 0.75rem;
        min-height: 40.86px;
    }

    .modal>div>div>div.modal-body span>span.selection>span>span {

        font-size: 0.875rem !important;
        font-weight: 400 !important;
        line-height: 1.4rem !important;

    }

    .modal>div>div>div.modal-body span>span.selection>span[aria-expanded=true] {
        border-color: #5e72e4;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    .modal>span>span>span.select2-search.select2-search--dropdown>input {
        border-radius: 0.5rem;
        box-shadow: none;
        border-color: #5e72e4;
    }

    .modal>span>span.select2-dropdown {
        border-radius: 0 0 .5rem .5rem;
        border-color: #5e72e4;
    }
</style>


@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
