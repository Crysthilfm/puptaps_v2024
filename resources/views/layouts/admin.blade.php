<!DOCTYPE html>
<html lang="en">
<head>
    {{-- Headings --}}
    <livewire:components.header />

    <title>@yield('page-title')</title>
    @livewireStyles
</head>
<body class="admin-body" id="admin-back-to-top">
    <main>
        <div class="px-0 py-0 container-fluid">
            <div class="row g-0" style="height: 100vh;">
                <div class="col-0 col-sm-0 col-md-0 col-lg-0 col-xl-2 d-none d-sm-none d-md-none d-lg-none d-xl-block">
                    <livewire:admin.admin-sidebar />
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-10">
                    <livewire:admin.admin-sidebar-offcanvas />
                    <div class="row g-0 w-100">
                        <div class="col-12">
                            <livewire:admin.admin-navbar />
                        </div>
                        <div class="col-12" style="margin-top: 40px;">
                            
                            @yield('content')
                        </div>
                    </div>
                </div>
                <div class="col-12 text-end">
                    <a type="button" href="#admin-back-to-top" class="btn btn-danger admin-back-to-top"><i class="fa-solid fa-angle-up"></i></a>
                </div>
            </div>
        </div>
    </main>



    <script>
        if (history.scrollRestoration) {
            history.scrollRestoration = 'manual';
        } else {
            window.onbeforeunload = function () {
                window.scrollTo(0, 0);
            }
        }
    </script>
    {{-- JS --}}
    <livewire:components.scripts />
    @livewireScripts

</body>
</html>
