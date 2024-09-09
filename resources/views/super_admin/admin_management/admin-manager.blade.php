@extends('layouts.super-admin')
@section('page-title', 'Admin Manager')
@section('active-admin-manager', 'active')
@section('page-name', 'Admin Management')

@section('content')

<section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5">
    <div class="container-fluid">

        <livewire:components.alert-status-message :message="session()->get('success')" />

        <div class="row justify-content-center g-0">
            <div class="col-11 sub-container-box pt-4 pb-3">
                <div class="row">
                    <div class="col-12">
                        <h3>List of Admins</h3>
                    </div>
                </div>

                <div class="px-3 mt-3">
                    <table class="table align-middle table-striped">
                        <thead class="tbl-head">
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Name</th>
                                <th scope="col">Role</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $admin)
                                @if ($admin->user_role == 'Admin')
                                    <tr>
                                        <th>{{ $admin->username }}</th>
                                        <td><b>{{ strtoupper($admin->last_name) }}</b>, {{ $admin->first_name }}</td>
                                        <td>{{ $admin->user_role }}</td>
                                        <td class="text-center"><button class="btn btn-danger fs-7" data-bs-toggle="modal" data-bs-target="#deleteAdmin{{ $admin->admin_id }}"><i class="fa-solid fa-trash-can"></i></button></td>
                                        @include("super_admin.components.delete-admin")
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
