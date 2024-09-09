@extends('layouts.admin')
@section('page-title', 'Admin Dashboard')
@section('active-tracerview', 'active')
@section('page-name', 'Dashboard')
@section('content')

<section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
    <div class="container-fluid box-content">

        <div class="row justify-content-center">

            <div class="col-11">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="col-12">
                            <div class="sub-container-box p-3 h-100 row">
                                <div class="col-12" style="background-color: rgb(138, 54, 54); border-radius:5px; color:white; padding:12px;">
                                    <h2>{{$alumniProfile->last_name}}, {{$alumniProfile->first_name}} {{$alumniProfile->middle_name}}</h2>
                                    <h5>Batch: {{$alumniProfile->batch}} &emsp; &emsp; Course: {{$alumniProfile->course_id}} &emsp; &emsp; Last Tracer Update: {{$alumniProfile->tracer_update_at}} </h5> 
                                    <div class="icon">
                                        <a href="{{route('admin.view-tracer-answers')}}" class="btn btn-large" style="color:azure; float:right; font-weight:bolder;">
                                        <i class="fa-solid fa-arrow-right-to-bracket fs-7 text-light"></i>
                                        Go Back
                                        </a>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-align-center mt-2"><h5>Boards, Licensure, and Civil Exams</h5></div>
                                    <hr>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>Board/Licensure Exams Taken:</b> <br><u>{{$alumniTracerData[2]['answer']}} </u></h6>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>Date Taken:</b> <br><u>{{$alumniTracerData[3]['answer']}}</u> </h6>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>Civil Exam:</b> <br><u>
                                                @if ($alumniTracerData[4]['answer'] == 'No')
                                                    Non-Passer
                                                @else
                                                    Passer
                                                @endif </u></h6>
                                        </div>
                                    <div class="col-12 text-align-center mt-2"><h5>Current Job Details</h5></div>
                                    <hr>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>Current Job Position:</b> <br><u>{{$alumniTracerData[5]['answer']}}</u> </h6>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>Current Company:</b> <br><u>{{$alumniTracerData[6]['answer']}}</u></h6>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>Date of employment:</b> <br><u>{{$alumniTracerData[7]['answer']}}</u> </h6>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>Nature of work:</b><br> <u>{{$alumniTracerData[8]['answer']}}</u> </h6>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>Employment type:</b> <br><u>{{$alumniTracerData[9]['answer']}}</u> </h6>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>Current Income:</b> <br><u>{{$alumniTracerData[10]['answer']}}</u> </h6>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>Jobs Relation to Course:</b> <br><u>{{$alumniTracerData[13]['answer']}}</u> </h6>
                                        </div>
                                    <div class="col-12 text-align-center mt-2"><h5>First Job Details</h5></div>
                                    <hr>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>First Job Position:</b> <br><u>{{$alumniTracerData[14]['answer']}}</u> </h6>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>First Company:</b> <br><u>{{$alumniTracerData[15]['answer']}}</u> </h6>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>Date of employment:</b> <br><u>{{$alumniTracerData[16]['answer']}}</u> </h6>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="h6-view"><b>Nature of Work:</b> <br><u>{{$alumniTracerData[17]['answer']}}</u> </h6>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection