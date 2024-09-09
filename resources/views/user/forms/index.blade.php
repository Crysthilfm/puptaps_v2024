@extends('layouts.user')
@section('page-title', 'Forms')
@section('form-active', 'user-active')
@section('content')

    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5">
        <div class="container-fluid form-module-body">

            <div class="row justify-content-center">
                <div class="col-11 col-sm-9 col-md-9 col-lg-9 col-xl-9 container-box animate__animated animate__fadeInDown animate__fast">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <h5 class="card-header">
                                    Why you need to answer these forms
                                    <i class="fa-solid fa-circle-info text-primary"></i>
                                </h5>
                                <div class="card-body">
                                    <p class="card-text">
                                        These are the necessary forms needed by the <b>Office of the Vice President</b> for <b>Student Services.</b> Additionally, before the University releases the documents that you've requested, the forms must have a status of <b>Completed <i class="fa-solid fa-circle-check" style="color: #27AE60;"></i></b>. If there is an error in your form, please reach out to the <a href="{{ route('user.getContactsPage') }}" class="fw-bold">admins</a> for support.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Table --}}
                    <div class="row align-items-center">
                        <div class="col-4 col-sm-4 col-md-6 col-lg-6 col-xl-8">
                            <p class="fw-bold mb-2">Form Name</p>
                        </div>
                        <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                            <p class="fw-bold text-center mb-2">Status</p>
                        </div>
                        <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                            <p class="fw-bold text-center mb-2">Action</p>
                        </div>
                    </div>

                    @foreach ($forms as $form)
                    <!-- PDS -->
                    @if (($form->form_name) == "Personal Data Sheet")
                        <div class="row align-items-center border-top py-2">
                            <div class="col-4 col-sm-4 col-md-6 col-lg-6 col-xl-8">
                                <div class="fw-bold fs-7">Personal Data Sheet</div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                                <div class="text-center fs-7">
                                    @if (count($pdsAnswer) > 0)
                                        Completed <i class="fa-solid fa-circle-check" style="color: #27AE60;"></i>
                                    @else
                                        Pending <i class="fa-solid fa-circle-exclamation" style="color: #E74C3C;"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                                <div class="text-center">
                                    @if (count($pdsAnswer) > 0)
                                        <form action="{{ route('userForm.PDS_to_PDF') }}" method="post" target="__blank">
                                            @csrf
                                            <input type="hidden" value="{{ Auth::user()->alumni_id }}" name="alumni_id">
                                            <button type="submit" class="btn btn-success fs-7">Print</button>
                                        </form>
                                    @else
                                        <a href="{{ route('userForm.getPDS') }}" type="button" class="btn btn-primary fs-7">Answer</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                    <!-- EIF -->
                    @elseif (($form->form_name) == "Exit Interview Form")
                        <div class="row align-items-center border-top py-2">
                            <div class="col-4 col-sm-4 col-md-6 col-lg-6 col-xl-8">
                                <div class="fw-bold fs-7">Exit Interview Form</div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                                <div class="text-center fs-7">
                                    @if (count($eifAnswer) > 0)
                                        Completed <i class="fa-solid fa-circle-check" style="color: #27AE60;"></i>
                                    @else
                                        Pending <i class="fa-solid fa-circle-exclamation" style="color: #E74C3C;"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                                <div class="text-center">
                                    @if (count($eifAnswer) > 0)
                                        <form action="{{ route('userForm.EIF_TO_PDF') }}" method="post" target="__blank">
                                            @csrf
                                            <input type="hidden" value="{{ Auth::user()->alumni_id }}" name="alumni_id">
                                            <button type="submit" class="btn btn-success fs-7">Print</button>
                                        </form>
                                    @else
                                        <a href="{{ route('userForm.getExiteInterview') }}" type="button" class="btn btn-primary fs-7">Answer</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                    <!-- SAS -->
                    @elseif (($form->form_name) == "Student Affairs and Services Form")
                        <div class="row align-items-center border-top py-2">
                            <div class="col-4 col-sm-4 col-md-6 col-lg-6 col-xl-8">
                                <div class="fw-bold fs-7">Student Affairs and Services Form</div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                                <div class="text-center fs-7">
                                    @if (count($sasAnswer) > 0)
                                        Completed <i class="fa-solid fa-circle-check" style="color: #27AE60;"></i>
                                    @else
                                        Pending <i class="fa-solid fa-circle-exclamation" style="color: #E74C3C;"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                                <div class="text-center">
                                    @if (count($sasAnswer) > 0)
                                        <form action="{{ route('userForm.SAS_TO_PDF') }}" method="post" target="__blank">
                                            @csrf
                                            <input type="hidden" value="{{ Auth::user()->alumni_id }}" name="alumni_id">
                                            <button type="submit" class="btn btn-success fs-7">Print</button>
                                        </form>
                                    @else
                                        <a href="{{ route('userForm.getSAS') }}" type="button" class="btn btn-primary fs-7">Answer</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
