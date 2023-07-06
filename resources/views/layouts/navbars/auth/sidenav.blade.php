<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('home') }}"
            target="_blank">
            <img src="./img/B Warna.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">BIG Net Manajemen</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}" href="{{ route('home') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'laporan' ? 'active' : '' }}" href="{{ route('laporan') }}">
                        <div
                        class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-copy-04 text-warning text-sm opacity-10 "></i>
                    </div>
                    <span class="nav-link-text ms-1">Laporan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'kelola-teknisi' ? 'active' : '' }}" href="{{ route('kelola-teknisi') }}">
                        <div
                        class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-ungroup text-success text-sm opacity-10 "></i>
                    </div>
                    <span class="nav-link-text ms-1">Kelola Teknisi</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'absen' ? 'active' : '' }}" href="{{ route('absen') }}">
                        <div
                        class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-badge text-info text-sm opacity-10 "></i>
                    </div>
                    <span class="nav-link-text ms-1">Absensi</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'pekerjaan' ? 'active' : '' }}" href="{{ route('pekerjaan') }}">
                        <div
                        class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-briefcase-24 text-danger text-sm opacity-10 "></i>
                    </div>
                    <span class="nav-link-text ms-1">Pekerjaan</span>
                </a>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Kelola pengguna</h6>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}" href="{{ route('profile') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Teknisi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}" href="{{ route('profile') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Pelanggan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}" href="{{ route('profile') }}">
                    <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Admin</span>
                </a>
            </li>

        
        </ul>
    </div>
    <div class="sidenav-footer mx-3 ">
        <form role="form" method="post" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); showLogoutConfirmation();"
                class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-sign-out-alt me-2" style="transform: rotateY(180deg)"></i> 
                    Log out
            </a>
        </form>
    </div>
    @push('js')
          
    <script>
        function showLogoutConfirmation() {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Anda yakin ingin keluar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form setelah konfirmasi positif
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
      
    @endpush
</aside>
