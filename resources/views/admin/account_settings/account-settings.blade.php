@extends('layouts.admin')
@section('page-title', 'Settings')
@section('active-account-settings', 'active')
@section('page-name', 'Settings')

@section('content')

    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
        <div class="container-fluid box-content">
            <livewire:components.alert-status-message :message="$message" />

            <div class="row justify-content-center g-0">
                <div class="col-11">
                    <div class="row">
                        <div class="col-md-12 sub-container-box p-4">
                            <h4>Account Settings</h4>
                            <form action="{{ route("adminSettings.updateAccount") }}" method="post">
                            @csrf
                                <div class="row">
                                    @foreach ($adminAccount as $admin)
                                        <div class="col-6 mb-2 mt-2">
                                            <label class="form-label">Username</label>
                                            <input type="text" class="form-control" name="username" value="{{ $admin->username }}">
                                            <span class="text-danger error-message">@error('username') {{$message}} @enderror</span>
                                        </div>
                                        <div class="col-6 mb-2 mt-2">
                                            <label class="form-label">Email</label>
                                            <input type="text" class="form-control" name="email" value="{{ $admin->email }}">
                                            <span class="text-danger error-message">@error('email') {{$message}} @enderror</span>
                                        </div>
                                        <div class="col-6 mb-2 mt-2">
                                            <label class="form-label">New Password</label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                                <button class="fs-7 btn btn-outline-secondary" type="button" id="togglePassword">
                                                    <i id="eye_icon" class="fa-solid fa-eye" ></i>
                                                </button>
                                            </div>
                                            <span class="text-danger error-message">@error('password') {{$message}} @enderror</span>
                                        </div>
                                        <div class="col-6 mb-2 mt-2">
                                            <label class="form-label">Confirm New Password</label>
                                            <div class="input-group">
                                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password') is-invalid @enderror">
                                                <button class="fs-7 btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                                    <i id="eye_icon_confirm" class="fa-solid fa-eye" ></i>
                                                </button>
                                            </div>
                                            <span class="text-danger error-message">@error('password') {{$message}} @enderror</span>
                                        </div>
                                        <div class="col-12 mt-3 text-end">
                                            <button type="submit" class="btn btn-primary px-4 fs-7">Save</button>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
