<div class="container-fluid pt-3 pb-2 px-0 h-100 admin-sidebar position-fixed table-wrapper-scroll-y my-custom-scrollbar">
    <div class="row align-items-center g-0" style="margin-top: 40px;">
        <div class="col-12 text-center">
            <div class="row align-items-center gx-0">
                <div class="col-5 text-center">
                    <a href="{{ route('superAdmin.getSuperAdminIndex') }}" class="text-decoration-none text-white align-items-center">
                        <img src="{{ asset('img/pupLogo.png') }}" style="height: 75px;">
                    </a>
                </div>
                <div class="col-7 text-start">
                    <h5 class="color-pup mt-3 fw-bold mb-0">PUPT - APS</h5>
                    <p class="color-pup fw-bold mt-0">SUPER ADMIN</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row align-items-center g-0" style="margin-top: 30px;">
        <div class="col-12">
            <div class="w-100 sidebar-buttons">
                <!-- Dashboard -->
                <a type="button" href="{{ route('superAdmin.getSuperAdminIndex') }}" class="fw-bold sidebar-button w-100 text-start @yield('active-homepage') py-2">
                    <i class="fa-solid fa-house me-1"></i>
                    Homepage
                </a>

                <hr class="mt-2 mb-2 hr-color opacity-100">
                <!-- Start: Career Management -->
                <a type="button" href="" class="sidebar-button w-100 text-start dropdown-toggle py-2" data-bs-toggle="collapse" data-bs-target="#adminManagement" aria-expanded="false">
                    <i class="fa-solid fa-user-tie me-1"></i>
                    Admin Management
                </a>
                <div class="">
                    <div class="collapse show" id="adminManagement">
                        <a type="button" href="{{ route("superAdmin.getAdminManager") }}" class="sidebar-button w-100 text-start py-2 @yield('active-admin-manager')">
                            <span class="ms-4">Admin Manager</span>
                        </a>
                        <a type="button" href="{{ route("superAdmin.getAddNewAdmin") }}" class="sidebar-button w-100 text-start py-2 @yield('active-add-new-admin')">
                            <span class="ms-4">Add New Admin</span>
                        </a>
                    </div>
                </div>
                <!-- End: Career Management -->

                <hr class="mt-2 mb-2 hr-color opacity-100">
                <!-- Start: Reports -->
                <a type="button" href="" class="sidebar-button w-100 text-start dropdown-toggle py-2" data-bs-toggle="collapse" data-bs-target="#adminManagement" aria-expanded="false">
                    <i class="fa-solid fa-user-tie me-1"></i>
                    Alumni Homepage
                </a>
                <div class="">
                    <div class="collapse show" id="adminManagement">
                        <a type="button" href="{{ route("superAdmin.getAnnouncementSettings") }}" class="sidebar-button w-100 text-start py-2 @yield('active-announcement-settings')">
                            <span class="ms-4">Announcements</span>
                        </a>
                        <a type="button" href="{{ route("superAdmin.getNewsSettings") }}" class="sidebar-button w-100 text-start py-2 @yield('active-news-settings')">
                            <span class="ms-4">News</span>
                        </a>
                    </div>
                </div>

                <hr class="mt-2 mb-2 hr-color opacity-100">
                <!-- Start: Tracer Management -->
                <a type="button" href="" class="sidebar-button w-100 text-start dropdown-toggle py-2" data-bs-toggle="collapse" data-bs-target="#adminManagement" aria-expanded="false">
                    <i class="fa-solid fa-user-tie me-1"></i>
                    Tracer Management
                </a>
                <div class="">
                    <div class="collapse show" id="adminManagement">
                        <a type="button" href="{{ route("superAdmin.getTracerManagement") }}" class="sidebar-button w-100 text-start py-2 @yield('active-tracerManagement')">
                            <span class="ms-4">Tracer Questions</span>
                        </a>
                    </div>
                </div>
                <!-- End: Tracer Management -->
            </div>
        </div>
    </div>
</div>
