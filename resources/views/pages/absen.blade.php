@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Kelola Teknisi'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-8">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                    <h6>Absen Harian Teknisi</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">
                                        No</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Nama</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Waktu Absen</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Update Aktivitas</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        status</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < 15; $i++) <tr>
                                    <td class="align-middle text-center">
                                        <p class="text-xs mb-0">{{$i+1}}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex ">
                                            <div>
                                                <img src="/img/team-2.jpg" class="avatar avatar-sm me-3" alt="user1">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">John Michael</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary font-weight-bold">19:00</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary font-weight-bold">19:00</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-success">Bekerja</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm px-3 btn-secondary mb-0"
                                            data-bs-toggle="modal" data-bs-target="#detailPekerjaanModal">
                                            Edit
                                        </button>
                                    </td>
                                    </tr>
                                    @endfor
                            </tbody>
                        </table>
                        <div class="modal fade" id="detailPekerjaanModal" tabindex="-1" role="dialog"
                            aria-labelledby="detailPekerjaanModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailPekerjaanModalLabel">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn bg-gradient-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn bg-gradient-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
</div>
@endsection