@extends('layouts.admin')
@section('page-title', 'Tracer Reports')
@section('active-tracer-reports', 'active')
@section('page-name', 'Reports')

@section('content')

<section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
    <div class="container-fluid">

        <div class="row mb-3 justify-content-center">
            <div class="col-11 px-0">
                <div class="row justify-content-between px-0">
                    <div class="col-4">
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
                    <div class="col-4">
                        <div class="py-0 card" style="background: #CB4335; color: #ffffff">
                            <div class="card-body align-items-center row">
                                <div class="col-8">
                                    <h2 class="card-title mb-1">{{ $totalTracer }}</h2>
                                    <p class="card-text mb-0 mt-0 fw-bold">Number of Respondents</p>
                                </div>
                                <div class="col-4 text-center p-0 m-0">
                                    <i class="bi bi-file-earmark-check p-0 m-0" style="font-size: 70px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="py-0 card" style="background: #1ABC9C; color: #ffffff">
                            <div class="card-body align-items-center row">
                                <div class="col-8">
                                    <h2 class="card-title mb-1">{{ $totalPassers }}</h2>
                                    <p class="card-text mb-0 mt-0 fw-bold">Licensed Board Passers</p>
                                </div>
                                <div class="col-4 text-center p-0 m-0">
                                    <i class="bi bi-credit-card" style="font-size: 70px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-11">
                <form action="{{ route('adminReports.tracerReportToPdf') }}" method="post" target="_blank">
                    @csrf
                        <div class="row sub-container-box py-4 px-3">
                            <h3 class="mb-4 mt-3">Tracer Reports</h3>
                            <div class="col-4 mb-3">
                                <label class="form-label">Report Type</label>
                                <select class="form-select" name="report_type">
                                    <option value="1">Summary Report</option>
                                    <option value="2">Status Report</option>
                                    <option value="3">Generate Alumni Profile</option>
                                    <option value="4">Generate List of Board Passers</option>
                                </select>
                            </div>
                            <div class="col-4 mb-3">
                                <label class="form-label">From (Batch)</label>
                                <select class="form-select" name="batch_from">
                                    @for ($i = 2022; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-4 mb-3">
                                <label class="form-label">To (Batch)</label>
                                <select class="form-select" name="batch_to">  
                                    {{-- Edited out for 2024 data --}}
                                    {{-- @for ($i = 2022; $i <= date('Y'); $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor --}}
                                    @for ($i = 2022; $i <= 2024; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <button type="submit" class="btn btn-primary fs-7">Generate Report <i class="fa-solid fa-file-lines ms-1"></i></button>
                            </div>
                        </div>
                    </form>
                
            </div>
            {{-- ================= Studies Generator ==================== --}}
            <div class="col-11">
                <form action="{{ route('tracerstudies.getDataFromForm') }}" method="post" target="_blank">
                @csrf
                    <div class="row sub-container-box mt-3 py-4 px-3">
                        <h3 class="mb-4 mt-3">Tracer Studies<i class="fa-solid fa-file-lines ms-1"></i></h3>
                        <div class="col-4 mb-3">
                            <label class="form-label">Course</label>
                            <select class="form-select" name="course">
                                <option value="all" >All</option>
                                @foreach ($courses as $a)
                                <option value="{{ $a->course_id }}">{{ $a->course_id }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">From (Batch)</label>
                            <select class="form-select" name="batch_from">
                                @for ($i = 2015; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-4 mb-3">
                            <label class="form-label">To (Batch)</label>
                            <select class="form-select" name="batch_to">  
                                {{-- Edited out for 2024 data --}}s
                                @for ($i = 2015; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                                {{-- @for ($i = 2015; $i <= 2024; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                @endfor --}}
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <button type="submit" class="btn btn-primary fs-7">Generate Report <i class="fa-solid fa-file-lines ms-1"></i></button>
                        </div>
                        
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</section>


@endsection
