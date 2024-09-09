{{-- NavBar --}}
<nav class="navbar navbar-expand-md sticky-top navbar-user navbar-dark">
    <div class="container-fluid nav-user-collapse">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('user.homepage') }}">
            <img src="{{ asset('img/pupLogo.png') }}" style="height: 40px" class="me-2"><div class="fw-bold">PUPT - APS</div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars nav-toggle-icon text-light"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0" >
                <li class="nav-item nav-user-modules hover-2">
                    <a class="nav-link @yield('home-active')" aria-current="page" href="{{ route('user.homepage') }}">Home</a>
                </li>
                <li class="nav-item nav-user-modules">
                    <a class="nav-link @yield('career-active')" aria-current="page" href="{{ route('userCareer.index') }}">Careers</a>
                </li>
                <li class="nav-item nav-user-modules">
                    <a class="nav-link @yield('form-active')" aria-current="page" href="{{ route('userForm.index') }}">Forms</a>
                </li>
                <li class="nav-item nav-user-modules">
                    <a class="nav-link @yield('tracer-active')" aria-current="page" href="{{ route('userTracer.getTracerIndex') }}">Tracer</a>
                </li>
                <li class="nav-item nav-user-modules">
                    <a class="nav-link @yield('profile-active')" aria-current="page" href="{{ route('userProfile.index') }}">Profile</a>
                </li>
            </ul>
            <div class="d-flex align-items-center">
                <div class="dropdown pb-3 pb-lg-0">
                    @foreach ($users as $user)
                    @if ($user->user_pfp == null)
                        <img src="{{ asset('Uploads/Profiles/user-no-profile.png') }}" class="user-profile-button" data-bs-toggle="dropdown" aria-expanded="false">
                    @else
                        <img src="{{ asset('Uploads/Profiles/' . $user->user_pfp) }}" class="user-profile-button" data-bs-toggle="dropdown" aria-expanded="false">
                    @endif
                    @endforeach
                    <ul class="dropdown-menu dropdown-menu-start dropdown-menu-sm-start dropdown-menu-md-end">
                        <li><a class="dropdown-item fs-7" href="{{ route('user.getAboutPage') }}">About</a></li>
                        <li><a class="dropdown-item fs-7" href="{{ route('user.getContactsPage') }}">Contacts</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item fs-7">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
{{-- End NavBar --}}
