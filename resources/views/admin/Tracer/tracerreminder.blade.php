@extends('layouts.admin')
@section('page-title', 'Admin Dashboard')
@section('active-tracerreminder', 'active')
@section('page-name', 'Dashboard')
@section('content')

<section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
    <div class="container-fluid box-content">

        <div class="row justify-content-center">
            <div class="col-11">
                <div class="row g-3">
                    
                    <livewire:admin.tracer-reminder/>
                </div>
            </div>
            
        </div>
    </div>
    
</section>
@endsection