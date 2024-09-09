@extends('layouts.auth')
@section('page-title', 'Reset Password')
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

                        <form action="{{ route('login.sendResetPassword') }}" method="post">
                        @if(Session::has('fail'))
                            <div class="alert alert-danger fs-7">{{ Session::get('fail') }}</div>
                        @elseif(Session::has('success'))
                            <div class="alert alert-success fs-7">{{ Session::get('success') }}</div>
                        @endif
                        @csrf

                        <div class="mb-3 mt-4">
                            <div class="mb-4 px-3">
                                <h5>Reset Password</h5>
                                <p><i class="fa-solid fa-circle-info text-primary"></i> <span class="text-secondary">Please enter your Email address associated with your account.</span></p>
                            </div>
                            <div class="mb-4 px-3">
                                <p class="form-label" >Email<span class="text-danger">*</span></p>
                                <input type="email" class="fs-7 form-control @error('email') border border-danger border-3 animate__animated animate__shakeX @enderror" name="email" value="{{ old("email") }}" required placeholder="sample@email.com"/>
                                <span class="text-danger error-message">@error('email') {{$message}} @enderror</span>
                            </div>
                            <div class="d-flex justify-content-center my-0">
                                {{-- <button class="btn btn-primary px-3" type="submit">Send Reset Password Link</button> --}}
                                <button type="submit" class="auth-btn fs-7"> <span class="ms-1 fs-7">Submit</span>
                                    <div class="icon"><i class="fa-regular fa-paper-plane fs-7"></i></i>
                                    </div>
                                </button>
                            </div>
                            <div class="text-center mt-5">
                                <p>Return to Login page? <a href="{{ route('login') }}" class="text-primary fw-bold">Click here.</a>
                                </p>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

