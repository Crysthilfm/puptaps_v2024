@extends('layouts.admin')
@section('page-title', 'Form Reports')
@section('active-form-reports-charts', 'active')
@section('page-name', 'Reports')

@section('content')

    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
        <div class="container-fluid box-content">
            <livewire:components.alert-status-message :message="session()->get('success')" />

            <div class="row mb-3 justify-content-center">
                <div class="col-11 px-0">
                    <div class="row justify-content-between px-0">
                        <div class="col-3">
                            <div class="py-0 card" style="background: #3498DB; color: #ffffff">
                                <div class="card-body align-items-center row">
                                    <div class="col-8">
                                        <h2 class="card-title mb-1">{{ $totalAlumni }}</h2>
                                        <p class="card-text mb-0 mt-0 fw-bold">Number of Alumni</p>
                                    </div>
                                    <div class="col-4 text-center p-0 m-0">
                                        <i class="bi bi-mortarboard p-0 m-0" style="font-size: 70px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="py-0 card" style="background: #CB4335; color: #ffffff">
                                <div class="card-body align-items-center row">
                                    <div class="col-8">
                                        <h2 class="card-title mb-1">{{ $totalPds }}</h2>
                                        <p class="card-text mb-0 mt-0 fw-bold">Respondents in PDS</p>
                                    </div>
                                    <div class="col-4 text-center p-0 m-0">
                                        <i class="bi bi-person-lines-fill" style="font-size: 70px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="py-3 card" style="background: #1ABC9C; color: #ffffff">
                                <div class="card-body align-items-center row">
                                    <div class="col-8">
                                        <h2 class="card-title mb-1">{{ $totalEif }}</h2>
                                        <p class="card-text mb-0 mt-0 fw-bold">Respondents in EIF</p>
                                    </div>
                                    <div class="col-4 text-center p-0 m-0">
                                        <i class="fa-solid fa-file-export" style="font-size: 70px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="py-0 card" style="background: #F1C40F; color: #ffffff">
                                <div class="card-body align-items-center row">
                                    <div class="col-8">
                                        <h2 class="card-title mb-1">{{ $totalSas }}</h2>
                                        <p class="card-text mb-0 mt-0 fw-bold">Respondents in SAS</p>
                                    </div>
                                    <div class="col-4 text-center p-0 m-0">
                                        <i class="bi bi-file-earmark-text" style="font-size: 70px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-11">
                    <form action="{{ route('adminReports.generateFormReport') }}" method="post" target="_blank">
                    @csrf
                        <div class="row sub-container-box py-4 px-3">
                            <h3 class="mb-4 mt-3">Form Reports - Charts</h3>
                            <div class="col-6 mb-3">
                                <label class="form-label">Forms</label>
                                <select id="form_name" class="form-select @error('form') is-invalid @enderror" name="form" required>
                                    <option value="" hidden selected>Please select one...</option>
                                    @foreach ($forms as $form)
                                        <option value="{{ $form->form_id }}">{{ $form->form_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-message">@error('form') {{$message}} @enderror</span>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Report Type</label>
                                <select id="form_type" class="form-select @error('type') is-invalid @enderror" name="type" required>
                                    <option value="" hidden selected>Please select one...</option>
                                    <option value="1">Detailed Report</option>
                                    <option value="2">Summary Report</option>
                                    <option value="3">Status Report</option>
                                </select>
                                <span class="text-danger error-message">@error('type') {{$message}} @enderror</span>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Course</label>
                                <select class="form-select" name="course_id">
                                    <option value="">All</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->course_id }}">{{ $course->course_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Sex</label>
                                <select class="form-select" name="sex">
                                    <option value="">All</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Batch From:</label>
                                <select class="form-select @error('batch_from') is-invalid @enderror" name="batch_from">
                                    @for ($i = 2022; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Batch To:</label>
                                <select class="form-select @error('batch_to') is-invalid @enderror" name="batch_to">
                                    @for ($i = 2022; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-12 mb-3 mt-2">
                                <button type="submit" class="btn btn-primary fs-7">Generate Report <i class="fa-solid fa-file-lines ms-1"></i></button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <script>
        // get references to the select elements
        const form_select = document.getElementById("form_name");
        const type_select = document.getElementById("form_type");

        // add an event listener to the first select element
        form_select.addEventListener("click", function() {
            // get the selected option value
            const selectedValue = form_select.value;

            if (selectedValue == 1) {
                form_type.options[2].style.display = "none";
            } else {
                // otherwise, show all options
                form_type.options[2].style.display = "";
            }
        });
    </script>
@endsection
