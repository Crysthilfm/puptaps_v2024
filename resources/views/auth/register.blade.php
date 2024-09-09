@extends('layouts.auth')
@section('page-title', 'Registration')
@section('content')

<section class="mt-4">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-sm-12 col-md-7 col-lg-6 col-xl-5">
                <div class="card-login-title @if(!($errors->any())) animate__animated animate__fadeInDown animate__fast @endif">
                    <div class="row gx-1 gy-0 align-items-center">
                        <div class="col-3 text-center">
                            <a href="{{ route('landingPage') }}">
                                <img src="{{ asset('img/pupLogo.png') }}" style="height: 70px;" class="">
                            </a>
                        </div>
                        <div class="col-9 text-start">
                            <h4 class="">PUPT - Alumni Portal System</h4>
                        </div>
                    </div>
                </div>
                <div class="card-login @if(!($errors->any())) animate__animated animate__fadeInUp animate__fast @endif">
                    <div class="card-body px-4">

                        <form action="{{ route('register') }}" method="post">
                        @if(Session::has('fail'))
                            <div class="alert alert-danger fs-7">{{ Session::get('fail') }}</div>
                        @endif
                        @csrf

                        <div class="mb-md-5 mt-4">
                            <div class="mb-4 px-3">
                                <h5>Registration</h5>
                                <p><i class="fa-solid fa-circle-info text-primary"></i> <span class="text-secondary">After entering your Email Address and Student Number, an email will be sent to you for your password confirmation.</span></p>
                            </div>
                            <div class="mb-4 px-3">
                                <p class="form-label">Email<span class="text-danger">*</span></p>
                                <input type="email" class="fs-7 form-control @error('email') border border-danger border-3 animate__animated animate__shakeX @enderror" name="email" value="{{ old("email") }}" placeholder="sample@email.com" required/>
                                <span class="text-danger error-message">@error('email') {{$message}} @enderror</span>
                            </div>
                            <div class="mb-4 px-3">
                                <p class="form-label">Student Number<span class="text-danger">*</span></p>
                                <input type="text" class="form-control @error('stud_number') border border-danger border-3 animate__animated animate__shakeX @enderror" name="stud_number" value="{{ old("stud_number") }}" placeholder="eg. 2019-00123-TG-0" required/>
                                <span class="text-danger error-message">@error('stud_number') {{$message}} @enderror</span>
                            </div>
                            <div class="d-flex justify-content-center my-0">
                                {{-- <button class="btn btn-primary px-5" type="submit">Register</button> --}}
                                <button type="submit" class="auth-btn fs-7"> <span class="fs-7">Register</span>
                                    <div class="icon"><i class="fa-solid fa-user-plus fs-7"></i>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <div class="text-center mb-4">
                            <p class="mt-5 mb-3">Already have an account? <a href="{{ route('login') }}" class="text-primary fw-bold">Login here.</a>
                            </p>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

