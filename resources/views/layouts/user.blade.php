<!DOCTYPE html>
<html lang="en">
<head>
    {{-- Headings --}}
    <livewire:components.header />
    <title>@yield('page-title')</title>
    @livewireStyles
</head>
<body id="go-to-top" class="user-body">
    {{-- oncontextmenu="return false;" it will disable right click --}}
    {{-- NavBar --}}
    <livewire:components.user-navbar :users="$users"/>


    @yield('content')
    <div class="text-end">
        <a type="button" href="#go-to-top" class="btn btn-danger user-back-to-top"><i class="fa-solid fa-angle-up"></i></a>
    </div>

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
