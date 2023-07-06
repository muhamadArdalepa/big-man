@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Kelola Tim'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body  d-flex align-items-center justify-content-between">
                    <h6 class="m-0">Kelola Tim Teknisi</h6>
                    <button class="btn btn-danger m-0">
                        <i class="fas fa-plus me-3"></i>
                        Tambah Tim
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tim A</h5>
              <h6 class="card-subtitle mb-2 text-muted">Ketua Tim: John Doe</h6>
              <h6 class="card-subtitle mb-2 text-muted">Anggota:</h6>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-circle me-2">
                    <span>Anggota 1: Jane Smith</span>
                  </div>
                </li>
                <li class="list-group-item">
                  <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-circle me-2">
                    <span>Anggota 2: Michael Johnson</span>
                  </div>
                </li>
                <li class="list-group-item">
                  <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-circle me-2">
                    <span>Anggota 3: Sarah Williams</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
  
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tim B</h5>
              <h6 class="card-subtitle mb-2 text-muted">Ketua Tim: Alex Thompson</h6>
              <h6 class="card-subtitle mb-2 text-muted">Anggota:</h6>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-circle me-2">
                    <span>Anggota 1: Emily Davis</span>
                  </div>
                </li>
                <li class="list-group-item">
                  <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-circle me-2">
                    <span>Anggota 2: David Wilson</span>
                  </div>
                </li>
                <li class="list-group-item">
                  <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-circle me-2">
                    <span>Anggota 3: Olivia Brown</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
  
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tim C</h5>
              <h6 class="card-subtitle mb-2 text-muted">Ketua Tim: Ryan Martinez</h6>
              <h6 class="card-subtitle mb-2 text-muted">Anggota:</h6>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-circle me-2">
                    <span>Anggota 1: Sophia Anderson</span>
                  </div>
                </li>
                <li class="list-group-item">
                  <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-circle me-2">
                    <span>Anggota 2: William Rodriguez</span>
                  </div>
                </li>
                <li class="list-group-item">
                  <div class="d-flex align-items-center">
                    <img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-circle me-2">
                    <span>Anggota 3: Mia Lopez</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    @include('layouts.footers.auth.footer')
</div>
@endsection