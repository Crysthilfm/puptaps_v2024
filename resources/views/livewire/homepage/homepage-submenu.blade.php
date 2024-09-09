<!-- Submenu for screen size xs - md -->
<div class="row d-inline d-sm-inline d-md-inline d-lg-none d-xl-none">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2>Homepage</h2>
        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#careerSubmenu" aria-expanded="false" aria-controls="careerSubmenu">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</div>

<div class="collapse d-lg-block d-xl-block sub-container-box mb-3" id="careerSubmenu">
    <div class="row pt-3">
        <div class="col-12">
            <h5>Categories</h5>
        </div>
        <div class="col-12">
            <form>
                <ul class="list-unstyled">
                    <li>
                        <button type="submit" class="homepage-button fs-7 btn btn-light w-100 text-start mb-1
                            @if ($query == 'All')
                                homepage-button-active
                            @endif
                        " value="All" name="query">All</button>
                    </li>
                    <li>
                        <button type="submit" class="homepage-button fs-7 btn btn-light w-100 text-start mb-1
                            @if ($query == 'Announcements')
                                homepage-button-active
                            @endif
                        " value="Announcements" name="query">Announcements</button>
                    </li>
                    <li>
                        <button type="submit" class="homepage-button fs-7 btn btn-light w-100 text-start mb-1
                            @if ($query == 'News')
                                homepage-button-active
                            @endif
                        " value="News" name="query">News</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>

<div class="collapse d-lg-block d-xl-block sub-container-box mb-3" id="careerSubmenu">
    <div class="row pt-3">
        <div class="col-12">
            <h5>Important Links</h5>
        </div>
        <div class="col-12">
            <form>
                <ul class="list-unstyled">
                    <li>
                        <a type="button" href="https://www.facebook.com/YourCentralStudentCouncil" class="fs-7 btn btn-light w-100 text-start mb-1 homepage-button" target="_blank"><i class="fa-brands fa-facebook fs-5 me-1"></i>PUPT - CSC</a>
                        <a type="button" href="https://www.facebook.com/PUPalumniofficial/" class="fs-7 btn btn-light w-100 text-start mb-1 homepage-button" target="_blank"><i class="fa-brands fa-facebook fs-5 me-1"></i>PUP Alumni</a>
                        <a type="button" href="https://www.facebook.com/PUPTOFFICIAL" class="fs-7 btn btn-light w-100 text-start mb-1 homepage-button" target="_blank"><i class="fa-brands fa-facebook fs-5 me-1"></i>PUP Taguig Branch</a>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>

