@extends('layouts.user')
@section('page-title', 'Profile')
@section('profile-active', 'user-active')

@section('content')

    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
        <div class="container-fluid my-3">

            <div class="row justify-content-center g-0">
                <div class="col-11 col-sm-9 col-md-9 col-lg-9 col-xl-9 container-box @if(!($errors->any())) animate__animated animate__fadeInUpBig animate__slow @endif">
                    @foreach ($users as $user)
                    <form action="{{ route('userProfile.updateProfile') }}" method="post">
                    @csrf

                            <div class="col-12 my-3 d-flex justify-content-between">
                                <div>
                                    <h3>Personal Information</h3>
                                    <p><i class="fa-solid fa-circle-info text-warning me-1"></i><span class="text-secondary">Before you proceed you need to finish setting up your account.</span></p>
                                </div>
                                <div>
                                    <a href="{{ route('userProfile.set-up') }}" class="btn btn-secondary fs-7"><i class="fa-solid fa-caret-left"></i> Back</a>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-2">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}" disabled>
                                <span class="text-danger">@error('last_name') {{$message}} @enderror</span>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-2">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}" disabled>
                                <span class="text-danger">@error('first_name') {{$message}} @enderror</span>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-2">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" name="middle_name" value="{{ $user->middle_name }}" disabled>
                                <span class="text-danger">@error('middle_name') {{$message}} @enderror</span>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-2">
                                <label class="form-label">Suffix</label>
                                <input type="text" class="form-control" name="suffix" value="{{ $user->suffix }}" disabled>
                                <span class="text-danger">@error('suffix') {{$message}} @enderror</span>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Sex: <span class="text-danger">*</span></label>
                                <div class="text-center border rounded py-1 @error('sex') animate__animated animate__shakeX border border-danger @enderror">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sex" value="Male">
                                        <label class="form-check-label">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sex" value="Female">
                                        <label class="form-check-label">Female</label>
                                    </div>
                                </div>
                                <span class="text-danger error-message">@error('sex') {{$message}} @enderror</span>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" id="birthday" class="form-control @error('birthday') border border-danger animate__animated animate__shakeX @enderror" name="birthday" value="{{ old('birthday') }}">
                                <span class="text-danger error-message">@error('birthday') {{$message}} @enderror</span>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Age</label>
                                <input type="number" id="age" class="fs-7 form-control @error('age') border border-danger @enderror" name="age" value="{{ old('age') }}" readonly style="background: #EAECEE;">
                                <span class="text-danger error-message">@error('age') {{$message}} @enderror</span>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('number') animate__animated animate__shakeX border border-danger @enderror" name="number" value="{{ old('number') }}" onkeypress="return onlyNumber(event)" maxlength="20">
                                <span class="text-danger error-message">@error('number') {{$message}} @enderror</span>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Religion</label>
                                <input type="text" class="form-control" name="religion" value="{{ old('religion') }}" onkeypress="return onlyAlphabets(event)" maxlength="50">
                                <span class="text-danger">@error('religion') {{$message}} @enderror</span>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Civil Status</label>
                                <select class="form-select" name="civil_status">
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Student Number</label>
                                <input type="text" class="form-control" name="stud_number" value="{{ $user->stud_number }}" disabled>
                                <span class="text-danger">@error('stud_number') {{$message}} @enderror</span>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Course</label>
                                <select class="form-select" name="course_id" disabled>
                                    <option value="{{ $user->course_id }}">{{ $user->course_id }}</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 my-2">
                                <label class="form-label">Year Graduated</label>
                                <select class="form-select" name="batch" disabled>
                                    <option value="{{ $user->batch }}">{{ $user->batch }}</option>
                                </select>
                            </div>
                            <div class="col-12 my-2">
                                <label class="form-label">City Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city_address') animate__animated animate__shakeX border border-danger @enderror" name="city_address" id="cityAddress" value="{{ old('city_address') }}">
                                <span class="text-danger error-message">@error('city_address') {{$message}} @enderror</span>
                            </div>

                            <div class="col-12 my-2">
                                <label class="form-label">Provincial Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('provincial_address') animate__animated animate__shakeX border border-danger @enderror" placeholder="Provincial Address" name="provincial_address" id="provincialAddress">
                                <span class="text-danger error-message">@error('provincial_address') {{$message}} @enderror</span>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sameAddress">
                                    <label class="form-check-label">
                                    Same as City Address
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12 text-center mt-5 mb-2">
                                <button type="submit" class="btn btn-primary" style="width: 200px">Save</button>
                            </div>
                        </div>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <script>
        const dobInput = document.getElementById('birthday');
        const ageInput = document.getElementById('age');

        dobInput.addEventListener('input', function() {
            const dob = Date.parse(this.value);
            const ageInMs = Date.now() - dob;
            const ageInYears = ageInMs / (1000 * 60 * 60 * 24 * 365.25);
            const age = Math.floor(ageInYears);

            ageInput.value  = age.toString();
        });
    </script>
@endsection
