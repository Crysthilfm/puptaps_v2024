@extends('layouts.auth')
@section('page-title', 'Reset Password')
@section('content')

    <section class="mt-4">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-12 col-sm-12 col-md-7 col-lg-6 col-xl-5">
                    <div class="card-login-title @if(!($errors->any())) animate__animated animate__fadeInDown animate__fast @endif">
                        <div class="row gx-1 gy-0 align-items-center">
                            <div class="col-3 text-center">
                                <a href="{{ route('landingPage') }}">
                                    <img src="{{ asset('img/pupLogo.png') }}" style="height: 70px;" class="">
                                </a>
                            </div>
                            <div class="col-9 text-start">
                                <h4 class="mt-3">PUPT - Alumni Portal System</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-login @if(!($errors->any())) animate__animated animate__fadeInUp animate__fast @endif">
                        <div class="card-body px-4">

                            <form action="{{ route('login.changingPassword') }}" method="post">
                            @if(Session::has('fail'))
                                <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                            @endif
                            @csrf

                            <div class="mb-md-5 mt-4">
                                <div class="mb-4 px-3">
                                    <h5>Reset your password</h5>
                                    <p><i class="fa-solid fa-circle-info text-primary"></i> <span class="text-secondary">Please enter your new password. Just a reminder that remeber your password.</span></p>
                                </div>
                                <input type="hidden" value="{{ $email }}" name="email">
                                <div class="mb-4 px-3">
                                    <p class="form-label">New Password<span class="text-danger">*</span></p>
                                    <div class="input-group @error('password') animate__animated animate__shakeX @enderror">
                                        <input type="password" name="password" id="password" class="form-control @error('password') border border-danger border-3 @enderror">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i id="eye_icon" class="fa-solid fa-eye" ></i>
                                        </button>
                                    </div>
                                    <span class="text-danger error-message">@error('password') {{$message}} @enderror</span>
                                </div>
                                <div class="mb-4 px-3">
                                    <p class="form-label">Confirm Password <span class="text-danger">*</span></p>
                                    <div class="input-group @error('password') animate__animated animate__shakeX @enderror">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password') border border-danger border-3 @enderror">
                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                            <i id="eye_icon_confirm" class="fa-solid fa-eye" ></i>
                                        </button>
                                    </div>
                                    <span class="text-danger error-message">@error('password') {{$message}} @enderror</span>
                                </div>
                                <div class="text-center my-0">
                                    <button class="btn btn-primary px-4 fs-7" type="submit">Change Password</button>
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="mt-5 mb-3">Don't have an account yet? <a href="{{ route('register') }}" class="text-primary fw-bold">Sign Up here.</a>
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
