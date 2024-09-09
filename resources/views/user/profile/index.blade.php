@extends('layouts.user')
@section('page-title', 'Profile')
@section('profile-active', 'user-active')

@section('content')

    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
        <div class="container-fluid my-3">
            <livewire:components.alert-status-message :message="session()->get('success')" />


            <div class="row justify-content-center g-0">
                @if($users == [])
                    <div class="col-12 mt-1 mb-4">
                        <h3>It appears that there is no available data</h3>
                        <p><i class="fa-solid fa-circle-info text-primary me-1"></i><span class="text-secondary">Please contact the developers for help</span></p>
                    </div>
                @else


                    <div class="col-11 col-sm-9 col-md-9 col-lg-9 col-xl-9 container-box animate__animated animate__fadeInDown animate__fast">
                    @foreach ($users as $user) 
                    <form action="{{ route('userProfile.updateUserAccount') }}" method="post" enctype="multipart/form-data">
                    @csrf
                        <div  iv class="row align-items-center px-0 px-sm-0 px-md-0 px-lg-3 px-xl-3">
                            <div class="col-12 mt-1 mb-4">
                                <h3>Profile Settings</h3>
                                <p><i class="fa-solid fa-circle-info text-primary me-1"></i><span class="text-secondary">Edit your username, password and profile picture here.</span></p>
                            </div>

                            <div class="col-12">
                                <div class="row align-items-center">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
                                        <div class="row mb-3">
                                            <div class="col-12 text-center mb-2">
                                                <label class="profilepic" for="uploadProfile">
                                                    <img class="profilepic__image" src="{{ asset('Uploads/Profiles/' . $user->user_pfp) }}" id="uploadPreview"/>
                                                    <div class="profilepic__content">
                                                    <span class="profilepic__icon"><i class="fas fa-camera"></i></span>
                                                    <span class="profilepic__text">Edit Profile</span>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="col-12 text-center">
                                                <input type="file" class="form-control" name="user_pfp" id="uploadProfile" onchange="PreviewImage();" hidden />

                                                <label class="btn btn-primary fs-7" for="uploadProfile">Change Profile Picture</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-2">
                                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ Auth::user()->username }}">
                                                <span class="error-message text-danger">@error('username') {{$message}} @enderror</span>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-2">
                                                <label class="form-label">Email</i></label>
                                                <input type="text" class="form-control" name="email" value="{{ $user->email }}">
                                                <span class="text-danger">@error('email') {{$message}} @enderror</span>
                                                {{-- Disabled Mode --}}
                                                {{-- <label class="form-label">Email <i class="fa-solid fa-lock"></i></label>
                                                <input type="text" class="form-control" name="email" value="{{ $user->email }}" disabled>
                                                <span class="text-danger">@error('email') {{$message}} @enderror</span> --}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-2">
                                                <label class="form-label">New Password <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                        <i id="eye_icon" class="fa-solid fa-eye" ></i>
                                                    </button>
                                                </div>
                                                <span class="text-danger error-message">@error('password') {{$message}} @enderror</span>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-2">
                                                <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password') is-invalid @enderror">
                                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                                        <i id="eye_icon_confirm" class="fa-solid fa-eye" ></i>
                                                    </button>
                                                </div>
                                                <span class="text-danger error-message">@error('password') {{$message}} @enderror</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-center mt-4 mb-0">
                            <button type="submit" class="btn btn-primary fs-7" style="width: 150px">Save Changes</button>
                        </div>
                    </form>
                    @endforeach
                </div>
            </div>

            <div class="row justify-content-center g-0 mt-4">
                <div class="col-11 col-sm-9 col-md-9 col-lg-9 col-xl-9 container-box animate__animated animate__fadeInUp animate__fast">
                    @foreach ($users as $user)
                    <fieldset disabled>
                        <div  iv class="row align-items-center px-0 px-sm-0 px-md-0 px-lg-3 px-xl-3">
                            <div class="col-12 my-3">
                                <h3>Personal Information</h3>
                                <p><i class="fa-solid fa-circle-info text-warning me-1"></i><span class="text-secondary">Your personal information cannot be edited. If there are incorrect fields please contact the admin via <a href="{{ route('user.getContactsPage') }}" class="fw-bold">email</a> to resolve the issue.</span></p>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-2">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" value="{{ $user->last_name }}">
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-2">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" value="{{ $user->first_name }}">
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-2">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" value="{{ $user->middle_name }}">
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-2">
                                <label class="form-label">Suffix</label>
                                <input type="text" class="form-control" value="{{ $user->suffix }}">
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Sex</label>
                                <div class="text-center border rounded py-1">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="Male" @if($user->sex == 'Male') checked @endif>
                                        <label class="form-check-label">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="Female" @if($user->sex == 'Female') checked @endif>
                                        <label class="form-check-label">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" value="{{ $user->birthday }}">
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Age</label>
                                <input type="number" class="fs-7 form-control" value="{{ $user->age }}">
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" class="form-control" value="{{ $user->number }}">
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Religion</label>
                                <input type="text" class="form-control" value="{{ $user->religion }}">
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Civil Status</label>
                                <select class="form-select">
                                    <option value="{{ $user->civil_status }}">{{ $user->civil_status }}</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Student Number</label>
                                <input type="text" class="form-control" value="{{ $user->stud_number }}">
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Course</label>
                                <select class="form-select">
                                    <option value="{{ $user->course_id }}">{{ $user->course_id }}</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Year Graduated</label>
                                <select class="form-select">
                                    <option value="{{ $user->batch }}">{{ $user->batch }}</option>
                                </select>
                            </div>
                            <div class="col-12 my-2">
                                <label class="form-label">City Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ $user->city_address }}">
                            </div>

                            <div class="col-12 my-2">
                                <label class="form-label">Provincial Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{ $user->provincial_address }}">
                            </div>
                        </div>
                    </fieldset>
                    @endforeach
                @endif
                </div>
            
            </div>
        </div>
    </section>

@endsection
