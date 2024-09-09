@extends('layouts.user')
@section('page-title', 'Contacts')
@section('content')

<section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
    <div class="container-fluid">
        <div class="row justify-content-center animate__animated animate__fadeInUp animate__fast">
            <!-- Start: Body -->
            <div class="col-11 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                <div class="row g-2 g-sm-2 g-md-2 g-lg-3 g-xl-3">
                    <div class="col-12">
                        <div class="sub-container-box pt-3 pb-4">
                            <h4 class="color-pup mb-4">FACULTY IN CHARGE</h4>
                            <div class="row gy-4">
                                <div class="col-12 col-lg-6">
                                    <div class="row align-items-center justify-content-center gy-2">
                                        <div class="col-12 col-md-3 text-center text-md-end">
                                            <img src="{{ asset('img/maam-liway.jpg') }}" alt="" class="pup-faculty">
                                        </div>
                                        <div class="col-12 col-md-9 text-center text-md-start">
                                            <h5 class="mb-1">Ms. Liwanag L. Maliksi</h5>
                                            <p class="my-1"><i>Admission & Guidance Council Office</i></p>
                                            <p class="my-1"><b>Head of Admission & Guidance Counseling Office</b></p>
                                            <p class="my-0"><span class="fw-bold">Email: </span>liwaymaliksi@yahoo.com</p>
                                            <p class="my-0"><span class="fw-bold">Tel. No.: </span>0919 591 1234</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <div class="row align-items-center justify-content-center gy-2">
                                        <div class="col-12 col-md-3 text-center text-md-end">
                                            <img src="{{ asset('Uploads/Profiles/user-no-profile.png') }}" alt="" class="pup-faculty">
                                        </div>
                                        <div class="col-12 col-md-9 text-center text-md-start">
                                            <h5 class="mb-1">Asst. Prof Bernadette T. Canlas</h5>
                                            <p class="my-1"><i>Alumni Office & Student Publication</i></p>
                                            <p class="my-1"><b>Head of Student Servicess</b></p>
                                            <p class="my-0"><span class="fw-bold">Email: </span></p>
                                            <p class="my-0"><span class="fw-bold">Tel. No.: </span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sub-container-box pt-3 pb-4">
                            <h4 class="color-pup mb-4">System Admin (Student Assistant)</h4>
                            <div class="row gy-4">
                                <div class="col-12 col-lg-6">
                                    <div class="row align-items-center justify-content-center gy-2">
                                        <div class="col-12 col-md-3 text-center text-md-end">
                                            <img src="{{ asset('img/ricamata_admin.jpg') }}" alt="" class="pup-faculty">
                                        </div>
                                        <div class="col-12 col-md-9 text-center text-md-start">
                                            <h5 class="mb-1">Michael Rei Ricamata</h5>
                                            <p class="my-1"><i>BS Information Technology</i></p>
                                            <p class="my-0"><span class="fw-bold">Email: </span>michaellangpogi@gmail.com</p>
                                            <p class="my-0"><span class="fw-bold">Facebook: </span>facebook.com/Crysthilfm</p>
                                            <p class="my-0"><span class="fw-bold">Tel. No.: </span> 0966 861 2025</p>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sub-container-box pt-3 pb-4">
                            <h4 class="color-pup mb-4">PUP - Taguig Contacts</h4>
                            <div class="row gy-5">
                                <div class="col-12 col-lg-4 text-center">
                                    <i class="fa-solid fa-map-location-dot fs-1"></i>
                                    <p class="fs-6 mt-2">General Santos Avenue, Lower Bicutan, Taguig City</p>
                                </div>
                                <div class="col-12 col-lg-4 text-center">
                                    <i class="fa-solid fa-at fs-1"></i>
                                    <p class="fs-6 mt-2">puptaguig.alumniportal@gmail.com</p>
                                </div>
                                <div class="col-12 col-lg-4 text-center">
                                    <i class="fa-solid fa-phone fs-1"></i>
                                    <p class="fs-6 mt-2">(632) 5335-1787 or 5335-1777</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End: Body -->
        </div>
    </div>
</section>

@endsection
