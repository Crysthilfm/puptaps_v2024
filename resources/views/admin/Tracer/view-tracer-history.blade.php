@extends('layouts.admin')
@section('page-title', 'Admin Dashboard')
@section('active-tracerreminder', 'active')
@section('page-name', 'Dashboard')
@section('content')

{{-- TO BE CONTINUED --}}

    {{-- <livewire:admin.view-tracer-history/> --}}
    
    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
        
        <div class="container-fluid box-content">
            
            <div class="row justify-content-center">
                <div class="col-11">
                    <div class="row g-3">
                        
                        {{-- Search Recipients --}}
                        <div class="col-12 sub-container-box mx-3 align-center justify-content-center">
                            <div class="d-flex justify-content-between my-2">
                                <div><h2>Recipients</h2></div>
                                <div><a class="btn btn-warning fs-7" href="{{ route('admin.tracerreminder') }}">Back to Tracer Reminder</a></div>
                            </div>
                            
                            <div class="mb-3">
                                <b>Total tracers updated: ({{count($tracerRecipient)}})</b><br>
                                <label for="">Search Within:</label>
                                <a class="btn btn-sm btn-success" href="{{route('admin.updatesInAWeek')}}">1 Week</a>
                                <a class="btn btn-sm btn-success" href="{{route('admin.updatesInAMonth')}}">1 Month</a>
                                <a class="btn btn-sm btn-success" href="{{route('admin.updatesIn6Months')}}">6 Months</a>   
                                <a class="btn btn-sm btn-success" href="{{route('admin.view-tracer-history')}}">All time</a>   
                            </div>
                            
                            {{-- <livewire:test/> --}}
                            <table class="table tableDesign table-striped table-bordered table-sm table-wrapper-scroll-y my-custom-scrollbar" style="height:300px; width:100%;" cellspacing="0" id="tracerTable1">
                                <thead class="tbl-head">
                                    <td>Name</td>
                                    <td>Recipient Email</td>
                                    <td>Contact#</td>
                                    <td>Last updated at</td>
                                    <td>Days Ago</td>
                                </thead>
                                <tbody>
                                    @if($tracerRecipient != null)
                                        @foreach ($tracerRecipient as $a)
                                        <tr>
                                            <td>{{$a->Surname}}, {{$a->Given}}, {{$a->Middle}}</td>
                                            <td>{{$a->Email}}</td>
                                            <td>{{$a->Contact}}</td>
                                            <td>{{Carbon\Carbon::parse($a->LastTracer)->toFormattedDateString();}}</td>
                                            <td>{{$a->dateDiff}}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                        </table>
                        </div>
                        {{-- Table history --}}
                        <div class="col-4 sub-container-box mx-3">
                            <h3>Tracer Reminders Sent</h3>
                            <div class="table-wrapper-scroll-y my-custom-scrollbar" style="height:330px;">
                                <table class="table tableDesign table-striped table-bordered table-sm align-middle" cellspacing="0" id="recipientTable">
                                    <thead class="tbl-head">
                                        <td>Reminder ID</td>
                                        <td>Date Sent</td>
                                        <td>Total Recipients</td>
                                    </thead>
                                    <tbody>
                                        @foreach ($tracerHistory as $a)
                                        <tr>
                                            <td value="{{$a->RecordID}}" id="{{$a->RecordID}}" onclick="setRhId(this)">{{$a->RecordID}}</td>
                                            <td>{{$a->DateSent}}</td>
                                            <td>{{$a->TotalRecipients}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>                       
                    </div>
                </div>
                
            </div>
        </div>
        
    </section>

@endsection