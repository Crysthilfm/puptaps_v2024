@extends('layouts.admin')
@section('page-title', 'Alumni List')
@section('active-alumni-list', 'active')
@section('page-name', 'User Management')

@section('content')

    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
        <div class="container-fluid">

            <livewire:components.alert-status-message :message="session()->get('success')" />

            <div class="row justify-content-center g-0">
                <div class="col-11">

                    <div class="row sub-container-box mb-3 pt-3 pb-0 px-2">
                        <div class="col-6">
                            <form class="row g-3" method="post" enctype="multipart/form-data" action="{{ route('adminUserManagement.addAlumniList') }}">
                            @csrf
                                <div class="col-12 input-group">
                                    <input class="form-control" type="file" id="formFile" name="excel_file">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-arrow-up-from-bracket me-1"></i>
                                        <span>Upload</span>
                                    </button>
                                </div>
                                <div class="col-12">
                                    @error('excel_file')
                                        <p class="text-danger mt-0">{{ $message }}</p>
                                    @enderror
                                </div>
                            </form>
                        </div>
                        <div class="col-6">
                            <form class="row g-3">
                                @csrf
                                <div class="input-group">
                                    <select class="form-select" name="batch">
                                        <option hidden value="">Select Batch</option>
                                        <option value="">All</option>
                                        @for ($i = 2022; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <button class="btn btn-primary" type="submit" id="button-addon2">
                                        <i class="fa-solid fa-magnifying-glass me-1"></i>
                                        <span>Search</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <a href="{{ route("adminUserManagement.downloadListTemplate") }}" class="btn btn-success">Download Template<i class="fa-solid fa-file-csv ms-2"></i></a>
                        </div>
                    </div>

                    <div class="row sub-container-box pb-0 pt-4 px-3">
                        <div class="col-12">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                    <th scope="col">Student Number</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Course</th>
                                    <th scope="col">Batch</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($alumni))
                                    @foreach ($alumni as $alum)
                                        <tr>
                                            <th scope="row">{{ $alum->stud_number }}</th>
                                            <td>{{ $alum->last_name }}</td>
                                            <td>{{ $alum->first_name }}</td>
                                            <td>{{ $alum->course_id }}</td>
                                            <td>{{ $alum->batch }}</td>
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8">No Data Found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-5">
                                {!! $alumni->links() !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>


@endsection
