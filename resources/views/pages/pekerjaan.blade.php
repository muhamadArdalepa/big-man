@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])
@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Pekerjaan'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-baseline me-3">
                            <h6 class=" me-3">Region</h6>
                            <select class="js-example-basic-single form-control" name="state">
                                <option value="AL">Alabama</option>
                                <option value="WY">Wyoming</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-baseline ps-3 border-1 border-start border-light">
                            <h6 class=" me-3">Tanggal</h6>
                            <input type="date" class="form-control" name="" id="">
                        </div>
                    </div>
                    <button onclick="tambahPekerjaan()" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#tambahPekerjaanModal">
                        <i class="fas fa-plus me-3"></i>
                        Tambah pekerjaan
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">
                                        Tim</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Ketua</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Pekerjaan</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Status</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Waktu ditugaskan</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < 15; $i++) <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">TIM12</p>
                                    </td>
                                    <td>
                                        <div class="d-flex ">
                                            <div>
                                                <img src="/img/team-2.jpg" class="avatar avatar-sm me-3" alt="user1">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">John Michael</h6>
                                                <p class="text-xs text-secondary mb-0">john@creative-tim.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs mb-0">Pemasangan baru di Jl. Adi Sucipto</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">23/04/18</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm px-3 btn-danger mb-0"
                                            data-bs-toggle="modal" data-bs-target="#detailPekerjaanModal">
                                            <i class="fas fa-info"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm px-3 btn- mb-0">
                                            <i class="fas fa-info"></i>
                                        </button>
                                    </td>
                                    </tr>
                                    @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
</div>
<div class="modal fade" id="detailPekerjaanModal" tabindex="-1" role="dialog"
    aria-labelledby="detailPekerjaanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailPekerjaanModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn bg-gradient-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tambahPekerjaanModal" tabindex="-1" role="dialog"
    aria-labelledby="tambahPekerjaanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPekerjaanModalLabel">Tambah Pekerjaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-dark">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="nav-wrapper position-relative end-0">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                                    <i class="ni ni-app"></i>
                                    <span class="ms-2">Pilih Tim</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <i class="ni ni-email-83"></i>
                                    <span class="ms-2">Buat Tim Baru</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <select class="" name="state">
                    <option value="AL">Alabama</option>
                    <option value="WY">Wyoming</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn bg-gradient-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2({
            theme: 'bootstrap'
        });

    });
    const tambahPekerjaan = () => {
        $('.moving-tab').attr('style', 'padding: 0px; width: 208px; transform: translate3d(0px, 0px, 0px); transition: all 0.5s ease 0s;');
    }
</script>

@endpush