<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            <!-- Navbar -->
            <nav
                class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
                <div class="container-fluid">
                    <img src="./img/logos/big-warna.png" class="navbar-brand-img" alt="main_logo" height="25">
                    <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="{{ route('home') }}">
                        BIG Net Manajemen
                    </a>
                    <div class="collapse navbar-collapse" id="navigation">
                        <ul class="navbar-nav mx-auto">
                           
                        </ul>
                        <ul class="navbar-nav d-lg-block d-none">
                            <li class="nav-item">
                                <a href="{{Route::currentRouteName()=='login'? route('register'):  route('login')}}" class="btn btn-sm mb-0 me-1 btn-danger">{{Route::currentRouteName()=='login'? 'Daftar':'Masuk'}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
    </div>
</div>
