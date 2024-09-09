<!-- Landing Page Navbar -->
<nav class="navbar navbar-expand-lg fixed-top py-1" id="navbar">
    <div class="container-fluid navbar-inner animate__animated animate__fadeInLeftBig">
        <a class="navbar-brand animate__animated animate__slideInDown" href="{{ route('landingPage') }}">
            <img src="{{ asset('img/pupLogo.png') }}" style="height: 40px">
            <span class="d-none d-sm-inline ms-2 fw-bold fs-5 text-shadow" id="navbar-title">PUPT-APS</span>
        </a>

        <div class="d-flex">
            <a href="{{ route('login') }}" type="button" class="text-decoration-none cssbuttons-io-button fs-7 me-1 animate__animated animate__zoomIn animate__delay-1s"> <span class="fs-7">Login</span>
                <div class="icon"><i class="fa-solid fa-arrow-right-to-bracket fs-7 text-light"></i>
                </div>
            </a>
            <a href="{{ route('register') }}" type="button" class="text-decoration-none cssbuttons-io-button fs-7 animate__animated animate__zoomIn animate__delay-2s"> <span class="fs-7">Sign Up</span>
                <div class="icon"><i class="fa-solid fa-user-plus fs-7 text-light"></i>
                </div>
            </a>
        </div>
    </div>
</nav>


<script>
    window.onscroll = function() {scrollFunction()};

    $( document ).ready(function() {
        document.getElementById("navbar").style.background = "none";
        document.querySelectorAll(".nav-link").forEach(el => el.style.color = "#ffffff");
        document.getElementById("navbar-title").style.color = "#ffffff";
        document.getElementById("navbar").style.boxShadow = "none";
        });

        function scrollFunction() {
        if (document.body.scrollTop > 5 || document.documentElement.scrollTop > 5) {
            document.getElementById("navbar").style.background = "#7B241C";
            document.getElementById("navbar-title").style.color = "#ffffff";
            document.querySelectorAll(".nav-link").forEach(el => el.style.color = "#000000");
            document.getElementById("navbar").style.boxShadow = "rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px";
        } else {
            document.getElementById("navbar").style.background = "none";
            document.querySelectorAll(".nav-link").forEach(el => el.style.color = "#ffffff");
            document.getElementById("navbar-title").style.color = "#ffffff";
            document.getElementById("navbar").style.boxShadow = "none";
        }
    }
</script>
