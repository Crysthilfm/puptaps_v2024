@foreach ($announcements as $announcement)
    <div class="sub-container-box mb-3 py-4" data-aos="fade-up">
        <div class="row align-items-center m-0">
            <div class="col-12 mb-1">
                <div class="row justify-content-between">
                    <div class="col-1">
                        <img src="{{ asset('Uploads/Profiles/user-no-profile.png') }}" class="career-post-profile">
                    </div>
                    <div class="col-10 col-sm-10 col-md-11 col-lg-11 col-xl-11">
                        <p class="fs-7 mb-0 fw-bold">Admin Announcement <i class="fa-solid fa-bullhorn me-2"></i></p>
                        <p class="mb-0 career-date">{{ date('F d, Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-11 offset-lg-1 col-xl-11 offset-xl-1 mt-2 mb-0 d-flex align-items-center justify-content-between">
                <h5 class="mb-1">{{ $announcement->announcement_title }}</h5>
                <a class="fs-7 text-decoration-none collapse show" id="showMoreAnnouncement{{ $announcement->announcement_id }}" type="button" data-bs-toggle="collapse" data-bs-target="#showMoreAnnouncement{{ $announcement->announcement_id }}">Read more</a>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-11 offset-lg-1 col-xl-11 offset-xl-1 mt-0 mb-1">
                <div class="collapse" id="showMoreAnnouncement{{ $announcement->announcement_id }}">
                    <div hidden>{{ $text = $announcement->announcement_text }}</div>
                    <div class="fs-7 text-break" style="white-space: pre-wrap; text-align:">@php echo $text @endphp</div>
                    <a class="fs-7 text-decoration-none mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#showMoreAnnouncement{{ $announcement->announcement_id }}">Read less</a>
                </div>
            </div>
        </div>
        <div class="row align-items-center text-start m-0">
            <div class="col-12 col-sm-12 col-md-12 col-lg-11 offset-lg-1 col-xl-11 offset-xl-1 mt-2 mt-sm-2 mt-md-2 mt-lg-1 mt-xl-0">
                <!-- Button trigger modal -->
                <a type="button" class="w-100" data-bs-toggle="modal" data-bs-target="#announcementMondal{{ $announcement->announcement_id }}">
                    <div class="career-post-image-box">
                        <img src="{{ asset('Uploads/NewsAnnouncements/' . $announcement->announcement_image) }}" alt="" class="career-post-image">
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="announcementMondal{{ $announcement->announcement_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content bg-transparent border-0">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <button type="button" class="btn btn-dark fw-bold mb-2 px-3" data-bs-dismiss="modal" style="position: fixed; top: 0; right: 0;">x</button>
                    </div>
                    <div class="col-12 text-center">
                        <img src="{{ asset('Uploads/NewsAnnouncements/' . $announcement->announcement_image) }}" alt="Job Image" class="career-img-full">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

