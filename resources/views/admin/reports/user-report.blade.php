@extends('layouts.admin')
@section('page-title', 'User Reports')
@section('active-user-reports', 'active')
@section('page-name', 'Reports')

@section('content')

    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
        <div class="container-fluid box-content">

            <div class="row justify-content-center mt-3 gx-3" style="margin: 0 35px;">
                <div class="col-8">
                    <div class="sub-container-box h-100 p-4">
                        <h5>Alumni Per Course</h5>
                        <div>
                            <canvas id="students-per-course"></canvas>
                        </div>
                        <script>
                            var studentsPerCourse = @json($studentsPerCourse);
                        </script>
                    </div>
                </div>
                <div class="col-4">
                    <div class="sub-container-box h-100 p-4">
                        <h5>Alumni Per Sex</h5>
                        <div>
                            <canvas id="students-per-sex"></canvas>
                        </div>
                        <script>
                            var studentsPersex = @json($studentsPersex);
                        </script>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-3">
                @if (count($alumni) == 0)
                    <div class="col-11 sub-container-box pt-4 pb-2">
                        <div class="row justify-content-center text-end mb-3 mt-1">
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <h3>Alumni Reports</h3>
                                <div class="d-flex justify-content-end">
                                    <form action="{{ route("adminReports.getUserReportPdf") }}" method="POST" target="_blank">
                                    @csrf
                                        <input type="hidden" value="{{ $sex }}" name="sex">
                                        <input type="hidden" value="{{ $course_id }}" name="course_id">

                                        @if ($batch_from == null)
                                            <input type="hidden" value="2022" name="batch_from">
                                        @else
                                            <input type="hidden" value="{{ $batch_from }}" name="batch_from">
                                        @endif

                                        @if ($batch_to == null)
                                            <input type="hidden" value="2022" name="batch_to">
                                        @else
                                            <input type="hidden" value="{{ $batch_to }}" name="batch_to">
                                        @endif

                                        <button type="submit" class="btn btn-danger me-2 fs-7">Download as PDF <i class="fa-solid fa-file-pdf ms-1"></i></button>
                                    </form>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button class="btn btn-primary fs-7" type="button" data-bs-toggle="collapse" data-bs-target="#showFilter">
                                            Filter <i class="fa-solid fa-filter me-0"></i>
                                        </button>
                                        <a href="{{ route('adminReports.getUserReports') }}" type="button" class="btn btn-secondary fs-7"><i class="fa-solid fa-rotate-left"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-danger fs-7" role="alert">
                            <i class="fa-solid fa-triangle-exclamation me-1 fs-6"></i>There is no avalable data for your query.
                        </div>
                        <div class="row justify-content-center mb-4 collapse mt-3" id="showFilter">
                            <div class="col-11 sub-container-box pt-3 mb-0 pb-2">
                                <form>
                                    @csrf
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Sex</label>
                                            <select class="form-select" name="sex">
                                                <option value="">All</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
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

                                        <div class="col-12 mb-2 text-end">
                                            <button class="btn btn-primary fs-7" type="submit">
                                                Filter
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-11 sub-container-box pt-4">
                        <div class="row justify-content-center text-end mb-3 mt-1">
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <h3>Alumni Reports</h3>
                                <div class="d-flex justify-content-end">
                                    <form action="{{ route("adminReports.getUserReportPdf") }}" method="POST" target="_blank">
                                    @csrf
                                        <input type="hidden" value="{{ $sex }}" name="sex">
                                        <input type="hidden" value="{{ $course_id }}" name="course_id">

                                        @if ($batch_from == null)
                                            <input type="hidden" value="2022" name="batch_from">
                                        @else
                                            <input type="hidden" value="{{ $batch_from }}" name="batch_from">
                                        @endif

                                        @if ($batch_to == null)
                                            <input type="hidden" value="2022" name="batch_to">
                                        @else
                                            <input type="hidden" value="{{ $batch_to }}" name="batch_to">
                                        @endif

                                        <button type="submit" class="btn btn-danger me-2 fs-7">Download as PDF <i class="fa-solid fa-file-pdf ms-1"></i></button>
                                    </form>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button class="btn btn-primary fs-7" type="button" data-bs-toggle="collapse" data-bs-target="#showFilter">
                                            Filter <i class="fa-solid fa-filter me-0"></i>
                                        </button>
                                        <a href="{{ route('adminReports.getUserReports') }}" type="button" class="btn btn-secondary fs-7"><i class="fa-solid fa-rotate-left"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center mb-4 collapse mt-3" id="showFilter">
                            <div class="col-11 sub-container-box pt-3 mb-0 pb-2">
                                <form>
                                    @csrf
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Sex</label>
                                            <select class="form-select" name="sex">
                                                <option value="">All</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
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

                                        <div class="col-12 mb-2 text-end">
                                            <button class="btn btn-primary fs-7" type="submit">
                                                Filter
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <table class="table table-striped align-middle mt-3">
                            <thead class="tbl-head">
                                <tr>
                                    <th scope="col" style="width: 20%;">Student No.</th>
                                    <th scope="col" style="width: 30%;">Full Name</th>
                                    <th scope="col" style="width: 10%;">Sex</th>
                                    <th scope="col" style="width: 10%;">Course</th>
                                    <th scope="col" style="width: 10%;">Batch</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alumni as $student)
                                    <tr>
                                        <th scope="row">{{ $student->stud_number }}</th>
                                        <td class="text-uppercase">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->suffix }} {{ $student->middle_name }}</td>
                                        <td>{{ $student->sex }}</td>
                                        <td>{{ $student->course_id }}</td>
                                        <td>{{ $student->batch }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-5">
                            {!! $alumni->links() !!}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>



@endsection
