@extends('layouts.admin')
@section('page-title', 'Careers')
@section('active-career-approval', 'active')
@section('page-name', 'Careers Management')

@section('content')

    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
        <div class="container-fluid box-content">

            <!-- Alert Status -->
            <livewire:components.alert-status-message :message="session()->get('success')" />

            {{-- <livewire:admin.page-title :title="$title"/> --}}

            <div class="row justify-content-center g-0">
                <div class="col-11">
                    <div class="row">
                        <div class="col-md-12 sub-container-box pt-4 px-4">
                            <table class="table align-middle">
                                <thead class="tbl-head">
                                  <tr>
                                    <th scope="col">Posted By</th>
                                    <th scope="col">Date Created</th>
                                    <th scope="col" class="text-center">Category</th>
                                    <th scope="col" class="text-center">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @if (count($careers) > 0)
                                    @foreach ($careers as $career)
                                        @if (($career->approval) == 0)
                                            <tr>
                                                <th scope="row">
                                                    @foreach ($users as $user)
                                                        @if (($user->alumni_id) == ($career->alumni_id))
                                                            <p class="mt-3">{{ $user->first_name }} {{ $user->last_name }}</p>
                                                        @endif
                                                    @endforeach
                                                </th>
                                                <td>{{ date('F d, Y', strtotime($career->created_at)) }}</td>
                                                <td class="text-center">{{ $career->category }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="fs-7 btn btn-primary my-1" data-bs-toggle="modal" data-bs-target="#viewCareer{{ $career->career_id }}">View</button>
                                                    <livewire:admin.view-career-approval :career="$career" :users="$users"/>
                                                    <button type="button" class="fs-7 btn btn-success my-1" data-bs-toggle="modal" data-bs-target="#approveCareer{{ $career->career_id }}">Approve</button>
                                                    <livewire:admin.approve-career-approval :career="$career" :users="$users"/>
                                                    <button type="button" class="fs-7 btn btn-danger my-1" data-bs-toggle="modal" data-bs-target="#rejectCareer{{ $career->career_id }}">Reject</button>
                                                    <livewire:admin.reject-career-approval :career="$career" :users="$users"/>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4">
                                                <div class="alert alert-danger mb-0 fs-7" role="alert">
                                                    <i class="fa-solid fa-triangle-exclamation me-1 fs-6"></i>There is no available data.
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
