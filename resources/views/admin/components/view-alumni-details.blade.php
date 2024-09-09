<!-- Modal View-->
<div class="modal fade" id="viewAlumniDetails{{ $alum->alumni_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header align-items-center">
        <p class="modal-title fs-6 fw-bold">{{ $alum->stud_number }}</p>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-start mt-3 mb-1 mx-3">
        <div class="row align-items-center g-0 mt-0 p-0">
          <div class="col-2">
            @if ($alum->user_pfp == null)
              <img src="{{ asset('Uploads/Profiles/user-no-profile.png') }}" class="user-profile-picture">
            @else
              <img src="/Uploads/Profiles/{{ $alum->user_pfp }}" class="user-profile-picture">
            @endif
          </div>
          <div class="col-10 text-break">
            <h3 class="fw-bold mb-0">{{ strtoupper($alum->last_name) }}, {{ $alum->first_name }} {{ $alum->middle_name }} {{ $alum->suffix }}</h3>
            <p class="mt-0 mb-0"><b>Email Address: </b>{{ $alum->email }}</p>
            @foreach ($courses as $course)
                @if ($course->course_id == $alum->course_id)
                    <p class="mb-0 mt-0"><b>Course:</b> {{ $course->course_desc }}</p>
                @endif
            @endforeach
            <p class="mb-0 mt-0"><b>Year Graduated: </b>{{ $alum->batch }}</p>
          </div>
          <div class="col-12 mt-2">
            <hr class="mb-2">
          </div>
        </div>

        <div class="row my-1">
          <div class="col-6">
            <p class="my-1"><b>Date of Birth: </b>{{ date('F d, Y', strtotime($alum->birthday)) }}</p>
          </div>
          <div class="col-6">
            <p class="my-1"><b>Age: </b>{{ $alum->age }}</p>
          </div>
          <div class="col-6">
            <p class="my-1"><b>Sex: </b>{{ $alum->sex }}</p>
          </div>
          <div class="col-6">
            <p class="my-1"><b>Religion: </b>{{ $alum->religion }}</p>
          </div>
          <div class="col-12">
            <p class="my-1"><b>City Address: </b>{{ $alum->city_address }}</p>
          </div>
          <div class="col-12">
            <p class="my-1"><b>Provincial Address: </b>{{ $alum->provincial_address }}</p>
          </div>
          <div class="col-12">
            <p class="my-1"><b>Contact No.: </b>{{ $alum->number }}</p>
          </div>
          <div class="col-12">
            <hr class="my-2">
          </div>
        </div>

        <div class="row my-2">
          <div class="col-12">
              <h4 class="mb-2">Form Status</h4>
          </div>

          <div class="col-12">
            <div class="row">
              <p class="mb-1 col-4 fw-bold">Form</p>
              <p class="mb-1 col-4 text-center fw-bold">Status</p>
              <p class="mb-1 col-4 text-center fw-bold">Action</p>
            </div>

            <hr class="mt-1 mb-2 fw-bold">
            <div class="row">
              <p class="my-2 col-4 fw-bold">Personal Data Sheet</p>
              <p class="my-2 col-4 text-center">
                  @if ($PDS->contains('alumni_id', $alum->alumni_id))
                    <i class="fa-regular fa-circle-check me-1 text-success"></i><span class="text-success">Completed</span>
                  @else
                    <i class="fa-regular fa-circle-xmark me-1 text-danger"></i><span class="text-danger">No Record</span>
                  @endif
              </p>
              <div class="col-4 text-center">
                <form action="{{ route('adminUserManagement.resetPds') }}" method="post">
                    @csrf
                    <input type="hidden" name="alumni_id" value="{{ $alum->alumni_id }}">
                    <button class="btn btn-primary fs-7" type="submit" @if (!($PDS->contains('alumni_id', $alum->alumni_id))) disabled @endif>Reset Form</button>
                </form>
              </div>
            </div>

            <hr class="mt-2 mb-2">
            <div class="row">
              <p class="my-2 col-4 fw-bold">Exit Interview Form</p>
              <p class="my-2 col-4 text-center">
                  @if ($EIF->contains('alumni_id', $alum->alumni_id))
                    <i class="fa-regular fa-circle-check me-1 text-success"></i><span class="text-success">Completed</span>
                  @else
                    <i class="fa-regular fa-circle-xmark me-1 text-danger"></i><span class="text-danger">No Record</span>
                  @endif
              </p>
              <div class="col-4 text-center">
                <form action="{{ route('adminUserManagement.resetEif') }}" method="post">
                    @csrf
                    <input type="hidden" name="alumni_id" value="{{ $alum->alumni_id }}">
                    <button class="btn btn-primary fs-7" type="submit" @if (!($EIF->contains('alumni_id', $alum->alumni_id))) disabled @endif>Reset Form</button>
                </form>
              </div>
            </div>

            <hr class="mt-2 mb-2">
            <div class="row">
              <p class="my-2 col-4 fw-bold">Student Affairs and Services Form</p>
              <p class="my-2 col-4 text-center">
                  @if ($SAS->contains('alumni_id', $alum->alumni_id))
                    <i class="fa-regular fa-circle-check me-1 text-success"></i><span class="text-success">Completed</span>
                  @else
                    <i class="fa-regular fa-circle-xmark me-1 text-danger"></i><span class="text-danger">No Record</span>
                  @endif
              </p>
              <div class="col-4 text-center">
                <form action="{{ route('adminUserManagement.resetSas') }}" method="post">
                    @csrf
                    <input type="hidden" name="alumni_id" value="{{ $alum->alumni_id }}">
                    <button class="btn btn-primary fs-7" type="submit" @if (!($SAS->contains('alumni_id', $alum->alumni_id))) disabled @endif>Reset Form</button>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

{{-- Modal Update --}}
<div class="modal fade" id="updateAlumni{{ $alum->alumni_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('adminUserManagement.updateAlumniInfo') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header align-items-center">
                    <p class="modal-title fs-6 fw-bold">Update Alumni Information</p>
                    <button type="submit" class="btn btn-success text-decoration-none fs-7" data-bs-toggle="modal" data-bs-target="#updateAlumni">Save <i class="fa-regular fa-floppy-disk"></i></button>
                </div>
                <div class="modal-body text-start mt-1 mb-1 mx-3">
                    <h4>Student Information</h4>
                    <input type="hidden" value="{{ $alum->alumni_id }}" name="alumni_id">
                    <div class="row align-items-center mt-0 p-0">
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0">Last Name</label>
                            <input type="text" name="last_name" value="{{ $alum->last_name }}" class="form-control @error('last_name') border border-danger border-3 @enderror">
                            <span class="text-danger error-message">@error('last_name') {{$message}} @enderror</span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0">First Name</label>
                            <input type="text" value="{{ $alum->first_name }}" name="first_name" class="form-control @error('first_name') border border-danger border-3 @enderror">
                            <span class="text-danger error-message">@error('first_name') {{$message}} @enderror</span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0">Middle Name</label>
                            <input type="text" value="{{ $alum->middle_name }}" name="middle_name" class="form-control @error('middle_name') border border-danger border-3 @enderror">
                            <span class="text-danger error-message">@error('middle_name') {{$message}} @enderror</span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0">Suffix</label>
                            <input type="text" value="{{ $alum->suffix }}" name="suffix" class="form-control @error('suffix') border border-danger border-3 @enderror">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0">Student Number</label>
                            <input type="text" value="{{ $alum->stud_number }}" name="stud_number" class="form-control @error('stud_number') border border-danger border-3 @enderror">
                            <span class="text-danger error-message">@error('stud_number') {{$message}} @enderror</span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0">Year Graduated</label>
                            <select class="form-select @error('batch') is-invalid @enderror" name="batch">
                                @for ($i = 2022; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" @if($i == $alum->batch) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                            <span class="text-danger error-message">@error('batch') {{$message}} @enderror</span>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label mb-0">Course</label>
                            <select class="form-select @error('course_id') border border-danger border-3 @enderror" name="course_id">
                                @foreach ($courses as $course)
                                    <option value="{{ $course->course_id }}">{{ $course->course_desc }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-message">@error('course_id') {{$message}} @enderror</span>
                        </div>
                    </div>

                    <hr class="mt-2 mb-3">

                    <h4>Personal Information</h4>
                    <div class="row align-items-center mt-0 p-0">
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0">Email Address</label>
                            <input type="text" value="{{ $alum->email }}" name="email" class="form-control @error('email') border border-danger border-3 @enderror">
                            <span class="text-danger error-message">@error('email') {{$message}} @enderror</span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0">Contact Number</label>
                            <input type="text" value="{{ $alum->number }}" name="number" class="form-control @error('number') border border-danger border-3 @enderror">
                            <span class="text-danger error-message">@error('number') {{$message}} @enderror</span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0">Date of Birth</label>
                            <input type="date" value="{{ $alum->birthday }}" name="birthday" class="form-control @error('birthday') border border-danger border-3 @enderror">
                            <span class="text-danger error-message">@error('birthday') {{$message}} @enderror</span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0">Age</label>
                            <input type="text" value="{{ $alum->age }}" name="age" class="form-control @error('age') border border-danger border-3 @enderror">
                            <span class="text-danger error-message">@error('age') {{$message}} @enderror</span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0">Sex</label>
                            <select class="form-select @error('sex') border border-danger border-3 @enderror" name="sex">
                                <option value="Male" @if($alum->sex == "Male") selected @endif>Male</option>
                                <option value="Female" @if($alum->sex == "Female") selected @endif>Female</option>
                            </select>
                            <span class="text-danger error-message">@error('sex') {{$message}} @enderror</span>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label mb-0">Religion</label>
                            <input type="text" value="{{ $alum->religion }}" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label mb-0">City Address</label>
                            <input type="text" value="{{ $alum->city_address }}" name="city_address" class="form-control @error('city_address') border border-danger border-3 @enderror">
                            <span class="text-danger error-message">@error('city_address') {{$message}} @enderror</span>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label mb-0">Provincial Address</label>
                            <input type="text" value="{{ $alum->provincial_address }}" name="provincial_address" class="form-control @error('provincial_address') border border-danger border-3 @enderror">
                            <span class="text-danger error-message">@error('provincial_address') {{$message}} @enderror</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


