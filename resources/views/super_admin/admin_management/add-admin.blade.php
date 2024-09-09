@extends('layouts.super-admin')
@section('page-title', 'Add New Admin')
@section('active-add-new-admin', 'active')
@section('page-name', 'Admin Management')

@section('content')

<section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5">
    <div class="container-fluid my-3">

        <livewire:components.alert-status-message :message="session()->get('success')" />

        <div class="row justify-content-center g-0">
            <div class="col-11 sub-container-box pt-4 pb-3">
                <div class="row">
                    <div class="col-12">
                        <h3>Add New Admin</h3>
                    </div>
                </div>

                <form action="{{ route("superAdmin.saveNewAdmin") }}" method="POST">
                @csrf
                    <div class="row mt-3 fs-7">
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label"><span class="text-danger me-1">*</span>Last Name:</label>
                            <input type="text" class="form-control @error('last_name') border border-danger @enderror" name="last_name" placeholder="Last Name">
                            <span class="text-danger error-message">@error('last_name') {{$message}} @enderror</span>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label"><span class="text-danger me-1">*</span>First Name:</label>
                            <input type="text" class="form-control @error('first_name') border border-danger @enderror" name="first_name" placeholder="First Name">
                            <span class="text-danger error-message">@error('first_name') {{$message}} @enderror</span>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Middle Name:</label>
                            <input type="text" class="form-control" name="middle_name" placeholder="Middle Name">
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label">Suffix:</label>
                            <input type="text" class="form-control" name="suffix" placeholder="Extension Name">
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label"><span class="text-danger me-1">*</span>Email Address:</label>
                            <input type="text" class="form-control @error('email') border border-danger @enderror" name="email" placeholder="Email Address">
                            <span class="text-danger error-message">@error('email') {{$message}} @enderror</span>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label"><span class="text-danger me-1">*</span>Username:</label>
                            <input type="text" class="form-control @error('username') border border-danger @enderror" name="username" placeholder="Username">
                            <span class="text-danger error-message">@error('username') {{$message}} @enderror</span>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label"><span class="text-danger me-1">*</span>Password:</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i id="eye_icon" class="fa-solid fa-eye" ></i>
                                </button>
                            </div>
                            <span class="text-danger error-message">@error('password') {{$message}} @enderror</span>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label"><span class="text-danger me-1">*</span>Confirm Password:</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password') is-invalid @enderror">
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i id="eye_icon_confirm" class="fa-solid fa-eye" ></i>
                                </button>
                            </div>
                            <span class="text-danger error-message">@error('password') {{$message}} @enderror</span>
                        </div>

                        <div class="col-12 text-end mt-3">
                            <button class="btn btn-success fs-7" type="submit">Add <i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>

@endsection
