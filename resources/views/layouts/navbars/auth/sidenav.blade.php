<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ url('dashboard') }}" target="_blank">
            <img src="{{ asset('img/logos/big-warna.png') }}" class="navbar-brand-img h-100" alt="main_logo" />
            <span class="ms-1 font-weight-bold">BIG Net Manajemen</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0" />
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('*dashboard*') ? 'active' : '' }}" href="{{ url('dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            @if (auth()->user()->role != 3)
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('*absen*') ? 'active' : '' }}" href="{{ url('absen') }}">
                        <div
                            class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-badge text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Absensi</span>
                    </a>
                </li>
                @endif @if (auth()->user()->role != 3)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('*' . 'pekerjaan') ? 'active' : '' }}"
                            href="{{ url('pekerjaan') }}">
                            <div
                                class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-briefcase-24 text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Pekerjaan</span>
                        </a>
                    </li>
                    @endif @if (auth()->user()->role != 2)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('*pemasangan*') ? 'active' : '' }}"
                                href="{{ route('pemasangan') }}">
                                <div
                                    class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="ni ni-single-copy-04 text-success text-sm opacity-10"></i>
                                </div>
                                <span class="nav-link-text ms-1">Pemasangan</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('*laporan*') ? 'active' : '' }}"
                                href="{{ route('laporan') }}">
                                <div
                                    class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                                    <i
                                        class="ni ni-single-copy-04 text-{{ auth()->user()->role == 3 ? 'secondary' : 'danger' }} text-sm opacity-10 "></i>
                                </div>
                                <span
                                    class="nav-link-text ms-1">{{ auth()->user()->role == 3 ? 'Riwayat Laporan' : 'Laporan' }}</span>
                            </a>
                        </li>
                        @endif @if (auth()->user()->role != 3)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('*tim*') ? 'active' : '' }}"
                                    href="{{ route('tim') }}">
                                    <div
                                        class="icon icon-shape icon-sm text-center me-2 d-flex align-items-center justify-content-center">
                                        <i class="ni ni-ungroup text-secondary text-sm opacity-10"></i>
                                    </div>
                                    <span class="nav-link-text ms-1">Tim</span>
                                </a>
                            </li>
                            @endif @if (auth()->user()->role == 1)
                                <li class="nav-item mt-3">
                                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">
                                        Kelola pengguna
                                    </h6>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ in_array(request()->route()->getName(),['teknisi', 'teknisi.show'])? 'active': '' }}"
                                        href="{{ route('teknisi') }}">
                                        <div
                                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                            <i class="ni ni-single-02 text-warning text-sm opacity-10"></i>
                                        </div>
                                        <span class="nav-link-text ms-1">Teknisi</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('*pelanggan*') ? 'active' : '' }}"
                                        href="{{ route('pelanggan') }}">
                                        <div
                                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                            <i class="ni ni-single-02 text-success text-sm opacity-10"></i>
                                        </div>
                                        <span class="nav-link-text ms-1">Pelanggan</span>
                                    </a>
                                </li>

                                <li class="nav-item mt-3">
                                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">
                                        Pengaturan
                                    </h6>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('*wilayah*') ? 'active' : '' }}"
                                        href="{{ route('wilayah') }}">
                                        <div
                                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                            <i class="ni ni-pin-3 text-dark text-sm opacity-10"></i>
                                        </div>
                                        <span class="nav-link-text ms-1">Wilayah</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('*paket*') ? 'active' : '' }}"
                                        href="{{ route('paket') }}">
                                        <div
                                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                            <i class="ni ni-app text-dark text-sm opacity-10"></i>
                                        </div>
                                        <span class="nav-link-text ms-1">Paket</span>
                                    </a>
                                </li>
                            @endif
        </ul>
    </div>
    @push('js')
        <script>
            function showLogoutConfirmation() {
                Swal.fire({
                    title: "Konfirmasi",
                    text: "Anda yakin ingin keluar?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    confirmButtonText: "Keluar",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form setelah konfirmasi positif
                        document.getElementById("logout-form").submit();
                    }
                });
            }
        </script>
    @endpush
</aside>
