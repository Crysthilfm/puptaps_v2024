<div class="sub-container-box">
    <div class="row">
        <div class="col-8 col-sm-9 col-md-9 col-lg-6 col-xl-6">
            <form>
            @csrf
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Job Position or Title..." name="query" value="{{ $query }}">
                    <a type="button" href="{{ route($return_home) }}" class="d-none d-sm-none d-md-none d-lg-block d-xl-block btn btn-secondary fs-7">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span class="d-none d-sm-none d-md-none d-lg-inline d-xl-inline"></span>
                    </a>
                    <button class="btn btn-primary fs-7" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span class="d-none d-sm-none d-md-none d-lg-inline d-xl-inline"></span>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-4 col-sm-3 col-md-3 col-lg-6 col-xl-6">
            <div class="dropdown text-end">
                <div class="btn-group" role="group">
                    <button class="btn btn-primary dropdown fs-7" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-plus d-inline d-sm-inline d-md-inline d-lg-none d-xl-none"></i>
                        <span class="d-none d-sm-none d-md-none d-lg-inline d-xl-inline">Post a Job</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-md-end fs-7">
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addCareerImage">Add as Image</button>
                        </li>
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addCareerText">Add as Text</button>
                        </li>
                    </ul>
                    <button type="button" class="btn btn-secondary px-2 fs-7" data-bs-toggle="modal" data-bs-target="#showGuidelines"><i class="fa-solid fa-circle-question"></i></button>
                </div>
                <livewire:career.add-career-image />
                <livewire:career.add-career-text />
            </div>
        </div>

        <div class="modal fade" id="showGuidelines" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <h6 class="fw-bold mt-2">Here are some guidelines to request a job advertisement to post <i class="fa-solid fa-circle-info text-primary fs-5 ms-1"></i></h6>
                        <hr class="mt-3 mb-3">
                        <p class="fs-7 pb-0 px-0 mb-2" style="text-align: justify;
                        text-justify: inter-word;">
                            <i class="fa-solid fa-circle-check text-success"></i> <span class="fw-bold">Job Title and Description:</span> <span class="fst-italic">Clearly state the job title and provide a detailed and comprehensive description of the job responsibilities and requirements.</span>
                        </p>
                        <p class="fs-7 pb-0 px-0 mb-2" style="text-align: justify;
                        text-justify: inter-word;">
                            <i class="fa-solid fa-circle-check text-success"></i> <span class="fw-bold">Required Skills and Qualifications:</span> <span class="fst-italic">List the specific skills and qualifications required for the position, including education, experience, and certifications.</span>
                        </p>
                        <p class="fs-7 pb-0 px-0 mb-2" style="text-align: justify;
                        text-justify: inter-word;">
                            <i class="fa-solid fa-circle-check text-success"></i> <span class="fw-bold">Benefits and Perks:</span> <span class="fst-italic">Highlight the benefits and perks of working for your company, such as health insurance, paid time off, and opportunities for advancement.</span>
                        </p>
                        <p class="fs-7 pb-0 px-0 mb-2" style="text-align: justify;
                        text-justify: inter-word;">
                            <i class="fa-solid fa-circle-check text-success"></i> <span class="fw-bold">Application Information:</span> <span class="fst-italic">Provide clear instructions on how to apply, including the application deadline and contact information.</span>
                        </p>
                        <p class="fs-7 pb-0 px-0 mb-2" style="text-align: justify;
                        text-justify: inter-word;">
                            <i class="fa-solid fa-circle-check text-success"></i> <span class="fw-bold">Job Location:</span> <span class="fst-italic">Indicate the job location and whether remote work is an option.</span>
                        </p>
                        <p class="fs-7 pb-0 px-0 mb-2" style="text-align: justify;
                        text-justify: inter-word;">
                            <i class="fa-solid fa-circle-check text-success"></i> <span class="fw-bold">Salary and Hours:</span> <span class="fst-italic">Provide information of work hours, and any other compensation or benefits. Salary disclose is optional.</span>
                        </p>
                    </div>
                    <div class="modal-footer py-1">
                        <button type="button" class="btn btn-secondary fs-7" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
