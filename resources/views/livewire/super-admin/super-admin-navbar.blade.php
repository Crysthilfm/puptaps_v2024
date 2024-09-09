<div class="container-fluid admin-navbar fixed-top">
    <div class="row py-2 align-items-center">
        <div class="col-8">
            <button type="button" class="btn btn-md btn-outline-light d-inline d-sm-inline d-md-inline d-lg-inline d-xl-none" data-bs-toggle="offcanvas" data-bs-target="#showSuperAdminMenu">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="animate__animated animate__bounce d-none d-lg-none d-xl-inline">
                <span class="fs-5 fw-bold ms-2 ms-sm-2 ms-md-2 ms-lg-2 ms-xl-0">Hello {{ Auth::user()->username }}!</span>
            </div>
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="cssbuttons-io-button fs-7"> <span class="fs-7">Logout</span>
                    <div class="icon"><i class="fa-solid fa-right-from-bracket fs-7 text-light"></i>
                    </div>
                </button>
            </form>
        </div>
    </div>
</div>
