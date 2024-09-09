@extends('layouts.super-admin')
@section('page-title', 'Homepage')
@section('active-homepage', 'active')
@section('page-name', 'Homepage')

@section('content')

<section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5">
    <div class="container-fluid my-3">
        <div class="row justify-content-center g-0">
            <div class="col-11 animate__animated animate__fadeInUp">
                <div class="row gy-3 gx-3">
                    <div class="col-12 col-lg-6">
                        <div class="sub-container-box pt-3 pb-2">
                            <h4 class="color-pup">PUP VISION</h4>
                            <h5 class="text-center">PUP: The National Polytechnic University</h5>
                        </div>
                        <div class="sub-container-box pt-3 pb-5 mt-3">
                            <h4 class="color-pup">THE PUP PHILOSOPHY</h4>
                            <p class="text-justify" style="text-align: justify; text-justify: inter-word;">As a state university, the Polytechnic University of the Philippines believes that:</p>

                            <ul class="fs-7 pup-mission">
                                <li>Education is an instrument for the development of the citizenry and for the enhancement of nation building; and</li>
                                <li>That meaningful growth and transformation of the country are best achieved in an atmosphere of brotherhood, peace, freedom, justice and nationalist-oriented education imbued with the spirit of humanist internationalism.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="sub-container-box pt-3 pb-2">
                            <h4 class="color-pup">PUP MISSION</h4>
                            <p class="text-justify" style="text-align: justify; text-justify: inter-word;">Ensuring inclusive and equitable quality education and promoting lifelong learning opportunities through a re-engineered polytechnic university by committing to:</p>

                            <ul class="fs-7 pup-mission">
                                <li>provide democratized access to educational opportunities for the holistic development of individuals with global perspective</li>
                                <li>offer industry-oriented curricula that produce highly-skilled professionals with managerial and technical capabilities and a strong sense of public service for nation building</li>
                                <li>embed a culture of research and innovation</li>
                                <li>continuously develop faculty and employees with the highest level of professionalism</li>
                                <li>engage public and private institutions and other stakeholders for the attainment of social development goal</li>
                                <li>establish a strong presence and impact in the international academic community</li>
                            </ul>

                        </div>
                    </div>
                    <div class="col-12">
                        <div class="sub-container-box pt-3 pb-3">
                            <h4 class="color-pup">PUP TEN PILLARS</h4>
                            <div class="row justify-content-center">
                                <div class="col-5">
                                    <p class="my-2"><b>Pillar 1: </b>Dynamic, Transformational, and Responsible Leadership</p>
                                    <p class="my-2"><b>Pillar 2: </b>Responsive and Innovative Curricula and Instruction</p>
                                    <p class="my-2"><b>Pillar 3: </b>Enabling and Productive Learning Environment</p>
                                    <p class="my-2"><b>Pillar 4: </b>Holistic Student Development and Engagement</p>
                                    <p class="my-2"><b>Pillar 5: </b>Empowered Faculty Members and Employees</p>
                                </div>
                                <div class="col-6">
                                    <p class="my-2"><b>Pillar 6: </b>Vigorous Research Production and Utilization</p>
                                    <p class="my-2"><b>Pillar 7: </b>Global Academic Standards and Excellence</p>
                                    <p class="my-2"><b>Pillar 8: </b>Synergistic, Productive, Strategic Networks and Partnerships</p>
                                    <p class="my-2"><b>Pillar 9: </b>Active and Sustained Stakeholders' Engagement</p>
                                    <p class="my-2"><b>Pillar 10: </b>Sustainable Social Development Programs and Projects</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                            <p class="my-0"><span class="fw-bold">Email: </span></p>
                                            <p class="my-0"><span class="fw-bold">Tel. No.: </span></p>
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
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
