@extends('layouts.admin')
@section('page-title', 'Admin Dashboard')
@section('active-homepage', 'active')
@section('page-name', 'Dashboard')
@section('content')

    <livewire:admin.notification />
    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
        <div class="container-fluid box-content">

            <div class="row justify-content-center">
                <div class="col-11">
                    <div class="row g-3">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="sub-container-box p-4 h-100" onclick="location.href='{{route('admin.view-tracer-answers')}}'">
                                <h5>Total Tracer Answers</h5>
                                <div class="d-flex justify-content-center align-items-center h-100">
                                    {{-- Input ID of Script Chart in canva --}}
                                    <h1 style="font-size:500%;">{{ $totalTracerAnswers}}</h1>
                                </div>
                            </div>
                        </div>

{{-- ====================================================================================================================== --}}

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="sub-container-box p-4 h-100" onclick="location.href='{{route('admin.view-tracer-answers')}}'">
                                <h5>Highest Salary</h5>
                                <div class="d-flex justify-content-center align-items-center h-100">
                                    <table class="table responsive">
                                        <thead class="thead-dark">
                                          <tr class="bg-danger">
                                            <th scope="col" style="color:white;"># of Alumni</th>
                                            <th scope="col" style="color:white;">Salaries</th>
                                          </tr>
                                        </thead>
                                        <tbody>              
                                          @foreach ($salaryRank as $sr)
                                          <tr>
                                            <th>{{ $sr->alumniCount}}</th>
                                            <th>{{ $sr->Salary}}</th>
                                          </tr>
                                          @endforeach
                                        </tbody>
                                      </table>
                                </div>
                            </div>
                        </div>

{{-- ====================================================================================================================== --}}

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="sub-container-box p-4 h-100" onclick="location.href='{{route('admin.view-tracer-answers')}}'">
                                <h5>Total Tracer By Course</h5>
                                <div>
                                    <canvas id="tracer-per-course"></canvas>
                                </div>
                                <script>
                                    var tracerPerCourse = @json($tracerPerCourse);
                                </script>
                            </div>
                        </div>

{{-- ====================================================================================================================== --}}

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="sub-container-box p-4 h-100" onclick="location.href='{{route('admin.view-tracer-answers')}}'">
                                <h5>Employed Alumni</h5>
                                <div>
                                    <canvas id="employed-alumni"></canvas>
                                </div>
                                <script>
                                    var employedAlumni = @json($employedAlumni);
                                </script>
                            </div>
                        </div>

{{-- ====================================================================================================================== --}}

                        <div class="col-12 col-md-12 col-lg-8">
                            <div class="sub-container-box p-4 h-100" onclick="location.href='{{route('admin.view-tracer-answers')}}'">
                                <h5>Employment Type of Alumni</h5>
                                <div>
                                    {{-- Input ID of Script Chart in canva --}}
                                    <canvas id="alumni-employment-type"></canvas>
                                </div>
                                <script>
                                    var alumniEmploymentType = @json($alumniEmploymentType);
                                </script>
                            </div>
                        </div>

{{-- ======================================================================================================================== --}}

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="sub-container-box p-4 h-100" onclick="location.href='{{route('admin.view-tracer-answers')}}'">
                                <h5>Inline Jobs</h5>
                                <div>
                                    <canvas id="job-inlined-course-alumni"></canvas>
                                </div>
                                <script>
                                    var inlineWithCourse = @json($inlineWithCourse);
                                </script>
                            </div>
                        </div>

{{-- ====================================================================================================================== --}}
                        
                        <div class="col-12 col-md-12 col-lg-8">
                            <div class="sub-container-box p-4" onclick="location.href='{{route('admin.view-tracer-answers')}}'">
                                <h5>Boards/Licensure Exam Passers</h5>
                                <div>
                                    <canvas id="alumni-per-exam"></canvas>
                                    <div style="float-right">
                                    <p class="smallText">CPABE = Certified Public Accountant Board Exam <br>
                                    EELE = Electronics Engineer Licensure Examination <br>
                                    LET = Licensure Examination for Teachers<br>
                                    PME = Professional Mechanical Engineer</p>
                                    </div>
                                </div>
                                <script>
                                    var perBoardExam = @json($perBoardExam);
                                </script>
                            </div>
                        </div>

{{-- ====================================================================================================================== --}}
                        
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="sub-container-box p-4 h-100" onclick="location.href='{{route('admin.view-tracer-answers')}}'">
                                <h5>Civil Service Exam Passers</h5>
                                <div>
                                    <canvas id="alumni-per-civil"></canvas>
                                </div>
                                <script>
                                    var perCivilService = @json($perCivilService);
                                </script>
                            </div>
                        </div>

{{-- ====================================================================================================================== --}}
                        
                        <div class="col-12 col-md-12 col-lg-8">
                            <div class="sub-container-box p-4 h-100">
                                <h5>Latest Career Posting</h5>
                                @if ($career == null)
                                    <h4 class="text-center">There no post yet.</h4>
                                @else
                                    @include('admin.components.last-career')
                                @endif
                            </div>
                        </div>

                        
                    </div>
                </div>
                
            </div>
        </div>
        
    </section>

@endsection
