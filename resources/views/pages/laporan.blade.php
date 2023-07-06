@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Laporan'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-baseline me-3">
                            <h6 class=" me-3">Kota</h6>
                            <select id="kota" class="form-select" style="width: 10rem">
                                <option value="Pontianak">Pontianak</option>
                                <option value="Bogor">Bogor</option>
                                <option value="Sintang">Sintang</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-baseline ps-3 border-1 border-start border-light">
                            <h6 class=" me-3">Tanggal</h6>
                            <input type="date" class="form-control" name="" id="">
                        </div>
                    </div>
                    <button class="btn btn-danger">
                        <i class="fas fa-plus me-3"></i>
                        Tambah laporan
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">
                                        Pelapor</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Perihal</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Waktu laporan</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Status</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Keterangan</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < 15; $i++)
                                    
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-sm font-weight-bold mb-0">Kamisato Ayaka</p>
                                    </td>
                                    <td>
                                        <p class="text-xs mb-0">Internet LOSS</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">23/04/18</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <p class="text-xs mb-0">-</p>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm px-3 btn-danger mb-0" data-bs-toggle="modal" data-bs-target="#detailPekerjaanModal">
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
                        <div class="modal fade" id="detailPekerjaanModal" tabindex="-1" role="dialog" aria-labelledby="detailPekerjaanModalLabel" aria-hidden="true">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
</div>
@endsection