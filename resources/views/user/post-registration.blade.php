@extends('layouts.user')
@section('page-title', 'Forms')
@section('content')

    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5">
        <div class="container-fluid form-module-body">

            <div class="row justify-content-center">
                <div class="col-11 col-sm-9 col-md-9 col-lg-9 col-xl-9 container-box animate__animated animate__fadeInUp animate__fast">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <h5 class="card-header">
                                    Required
                                    <i class="fa-solid fa-circle-info text-primary"></i>
                                </h5>
                                <div class="card-body">
                                    <p class="card-text">
                                        You need to answer these forms to complete the registration process.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Table --}}
                    <div class="row align-items-center mt-4">
                        <div class="col-8 col-sm-8 col-md-9 col-lg-9 col-xl-10">
                            <p class="fw-bold mb-2">Forms</p>
                        </div>
                        <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                            <p class="fw-bold text-center mb-2">Action</p>
                        </div>
                    </div>
                    <hr class="mt-0 mb-2">
                    <div class="row align-items-center">
                        <div class="col-8 col-sm-8 col-md-9 col-lg-9 col-xl-10">
                            <p class="fw-bold mb-2">Personal Information - (Profile)</p>
                        </div>
                        <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                            <p class="text-center mb-2">
                                @if ($profile_setup == 'Incomplete')
                                    <a href="{{ route('userProfile.getProfileSetup') }}" class="btn btn-primary fs-7">Answer</a>
                                @else
                                    <button class="btn btn-success disabled fs-7">Done</button>
                                @endif
                            </p>
                        </div>
                    </div>
                    <hr class="mt-0 mb-2">
                    <div class="row align-items-center">
                        <div class="col-8 col-sm-8 col-md-9 col-lg-9 col-xl-10">
                            <p class="fw-bold mb-2">Alumni Tracer</p>
                        </div>
                        <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                            <p class="text-center mb-2">
                                @if (count($tracer_answers) > 0)
                                    <button class="btn btn-success fs-7 disabled">Done</button>
                                @else
                                    <a href="{{ route('userTracer.getAnswerPage') }}" class="btn btn-primary fs-7">Answer</a>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
