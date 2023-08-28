<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    data-scroll="false">
    <div class="container-fluid py-1 px-0 px-sm-3">
        @include('components.breadcrumbs')
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            </div>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center" id="iconNavbarSidenav">
                    <span style="cursor: pointer;" class="nav-link text-white p-0 me-3">
                        <div class="sidenav-toggler-inner nav-toggler">
                            <i class="sidenav-toggler-line bg-white nav-toggler"></i>
                            <i class="sidenav-toggler-line bg-white nav-toggler"></i>
                            <i class="sidenav-toggler-line bg-white nav-toggler"></i>
                        </div>
                    </span>
                </li>

                <li class="nav-item dropdown pe-2 d-flex align-items-center me-2">
                    <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-end  p-2 me-sm-n4"
                        aria-labelledby="dropdownMenuButton">
                        <li class="">
                            <a class="dropdown-item border-radius-md" href="javascript:;">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <img src="{{route('storage.private','/profile/Nilou.webp')}}" class="avatar avatar-sm  me-3 ">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">
                                            <span class="font-weight-bold">New message</span> from Laur
                                        </h6>
                                        <p class="text-xs text-secondary mb-0">
                                            <i class="fa fa-clock me-1"></i>
                                            13 minutes ago
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item align-self-center">
                    <span class="text-white me-2">{{auth()->user()->nama}}</span>
                </li>

                <li class="nav-item dropdown d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0" id="dropdownProfile"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{route('storage.private',auth()->user()->foto_profil)}}" class="avatar avatar-sm"
                            alt="foto profil">
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-end  p-2 me-sm-n4" aria-labelledby="dropdownProfile">
                        <li>
                            <a class="dropdown-item border-radius-md" href="{{route('auth.profile')}}">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                    <img src="{{route('storage.private',auth()->user()->foto_profil)}}" class="avatar avatar-sm  me-3 ">
                                    </div>
                                    <h6 class="text-sm font-weight-normal align-self-center m-0">
                                        Lihat Profil
                                    </h6>
                                </div>
                            </a>
                        </li>
                        <hr class="horizontal dark my-2">
                        <li>
                            <form role="form" method="post" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <span id="btn-logout" class="dropdown-item border-radius-md text-danger" type="submit">
                                    <div class="d-flex py-1 align-items-center ">
                                        <i class="fa-solid fa-right-from-bracket me-3"></i>
                                        <h6 class="text-sm font-weight-normal mb-1 ">
                                            Keluar
                                        </h6>
                                    </div>
                                </span>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->