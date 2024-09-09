<!-- Submenu for screen size xs - md -->
<div class="row d-none">
    <div class="col-12 text-end d-flex justify-content-between align-items-center">
        <h4>Categories</h4>
        <button class="btn btn-outline-secondary fs-7" type="button" data-bs-toggle="collapse" data-bs-target="#careerSubmenu" aria-expanded="false" aria-controls="careerSubmenu">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</div>

<div class="collapse d-lg-block d-xl-block sub-container-box mb-4" id="careerSubmenu">
    <div class="row pt-3">
        <div class="col-12">
            <h5 class="d-none d-sm-none d-md-none d-lg-block d-xl-block">Categories</h5>
        </div>
        <div class="col-12">
            <form>
                <ul class="list-unstyled">
                    <li>
                        <button type="submit" class="career-submenu-button fs-7 btn btn-light w-100 text-start mb-1
                            @if ($subquery == null)
                                active-career
                            @endif
                        " value="" name="subquery">All</button>
                    </li>
                    @foreach ($careerCategories as $category)
                        <li>
                            <button type="submit" class="career-submenu-button fs-7 btn btn-light w-100 text-start my-1
                                @if ($subquery == $category->career_category)
                                    active-career
                                @endif
                            " value="{{ $category->career_category }}" name="subquery">{{ $category->career_category }}</button>
                        </li>
                    @endforeach
                </ul>
            </form>
        </div>
    </div>
</div>
