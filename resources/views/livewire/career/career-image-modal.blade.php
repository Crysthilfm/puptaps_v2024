<div class="sub-container-box mb-3 py-4" data-aos="fade-down">
    @if ($career->alumni_id != Null)
        @foreach ($alumni as $alum)
            @if (($alum->alumni_id) == ($career->alumni_id))
                <div class="row align-items-center m-0">
                    <div class="col-12 mb-1">
                        <div class="row justify-content-between">
                            <div class="col-1">
                                @if ($alum->user_pfp == null)
                                    <img src="{{ asset('Uploads/Profiles/user-no-profile.png') }}" class="career-post-profile">
                                @else
                                    <img src="{{ asset('Uploads/Profiles/' . $alum->user_pfp) }}" class="career-post-profile">
                                @endif
                            </div>
                            <div class="col-10 col-sm-10 col-md-11 col-lg-11 col-xl-11 d-flex align-items-center justify-content-between">
                                <div class="">
                                    @foreach ($username as $user_name)
                                        @if ($user_name->alumni_id == $alum->alumni_id)
                                            <p class="fs-7 mb-0 fw-bold">{{ $user_name->username }}</p>
                                        @endif
                                    @endforeach
                                    <p class="mb-0 career-date">{{ date('F d, Y', strtotime($career->created_at)) }}</p>
                                </div>
                                <div>
                                    @if (Auth::user()->alumni_id == $career->alumni_id || Auth::user()->user_role == "Admin")
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCareer{{ $career->career_id }}">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center text-start m-0">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-11 offset-lg-1 col-xl-11 offset-xl-1 mt-2 mt-sm-2 mt-md-2 mt-lg-1 mt-xl-0">
                        <!-- Button trigger modal -->
                        <a type="button" class="w-100" data-bs-toggle="modal" data-bs-target="#careerModalImage{{ $career->career_id }}">
                            <div class="career-post-image-box">
                                <img src="{{ asset('Uploads/Career/' . $career->job_ad_image) }}" alt="" class="career-post-image">
                            </div>
                        </a>
                    </div>
                </div>
            @endif
        @endforeach
    @else
        @foreach ($admin as $admins)
            @if ($admins->admin_id == $career->admin_id)
                <div class="row align-items-center m-0">
                    <div class="col-12 mb-1">
                        <div class="row justify-content-between">
                            <div class="col-1">
                                <img src="{{ asset('Uploads/Profiles/user-no-profile.png') }}" class="career-post-profile">
                            </div>
                            <div class="col-10 col-sm-10 col-md-11 col-lg-11 col-xl-11 d-flex align-items-center justify-content-between">
                                <div class="">
                                    @foreach ($username as $user_name)
                                        @if ($user_name->admin_id == $admins->admin_id)
                                            <p class="mb-0 fw-bold">{{ $user_name->username }}</p>
                                        @endif
                                    @endforeach
                                    <p class="mb-0 career-date">{{ date('F d, Y', strtotime($career->created_at)) }}</p>
                                </div>
                                <div>
                                    @if (Auth::user()->alumni_id == $career->alumni_id || Auth::user()->user_role == "Admin")
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCareer{{ $career->career_id }}">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center text-start m-0">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-11 offset-lg-1 col-xl-11 offset-xl-1 mt-2 mt-sm-2 mt-md-2 mt-lg-1 mt-xl-0">
                        <!-- Button trigger modal -->
                        <a type="button" class="w-100" data-bs-toggle="modal" data-bs-target="#careerModalImage{{ $career->career_id }}">
                            <div class="career-post-image-box">
                                <img src="{{ asset('Uploads/Career/' . $career->job_ad_image) }}" alt="" class="career-post-image">
                            </div>
                        </a>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>
<livewire:career.delete-career-post :career_id="$career->career_id"/>
    <!-- Modal -->
    <div class="modal fade" id="careerModalImage{{ $career->career_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <button type="button" class="btn btn-dark fw-bold float-end mb-2 px-3" data-bs-dismiss="modal">x</button>
                    </div>
                    <div class="col-12">
                        <img src="{{ asset('Uploads/Career/' . $career->job_ad_image) }}" alt="Job Image" class="career-img-full">
                    </div>
                </div>
            </div>
        </div>
    </div>

