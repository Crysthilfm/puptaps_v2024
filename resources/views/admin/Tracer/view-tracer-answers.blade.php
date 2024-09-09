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
                        <div class="sub-container-box p-3 h-100 d-flex justify-content-center">
                            <h1>List of Tracer Responses</h1>
                        </div>
                    </div>
                    {{-- Content --}}
                    <div class="col-12">
                        <div class="sub-container-box p-3 h-100">
                            <h3>Alumni Tracer</h3>
                            <div class="mb-4">
                                <table cellspacing="0" id="tracerTable1" class="table table-striped align-middle table-hover table-wrapper-scroll-y my-custom-scrollbar pt-2" style="height:500px; margin:auto;">
                                    <thead class="tbl-head">
                                        {{-- Input Head --}}
                                        <td><b>View Tracer</b></td>
                                        <td><b>Name</b></td>
                                        <td><b>Batch</b></td>
                                        <td><b>Course</b></td>
                                    </thead>
                                    <tbody>
                                        {{-- Input Body --}}
                                        @php
                                            $ctr=0;
                                        @endphp
                                        @foreach ($alumnitraceranswers as $item)
                                        <div>
                                            <tr>
                                                <td> 
                                                    @php
                                                        $alumniAnswer = App\Http\Controllers\Admin\TracerAnswers::getIndividualAnswers($item->alumni_id);    
                                                        //dd($alumniAnswer)  
                                                    @endphp
                                                    <a href="{{ route('user.show', $item->alumni_id)}}" class="btn @if ($alumniAnswer == null)
                                                        btn-warning
                                                        @else
                                                        btn-info
                                                    @endif"><i class="fa-solid fa-eye"></i></a>
                                                </td>
                                                <td>{{$item->last_name}} {{$item->first_name}} {{$item->middle_name}}</td>
                                                <td>{{$item->batch}}</td>
                                                <td>{{$item->course_id}}</td>
                                            </tr>
                                        </div>
                                        @php
                                            $ctr++;
                                        @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <hr class="mt-2 mb-3 hr-color opacity-100" style="width:100%;">
                        </div>
                    </div>
                </div>
            </div> 
        </div>

        @include("sweetalert::alert")
    </div>
    
</section>
@endsection