@extends('layouts.user')
@section('page-title', 'Alumni Tracer')
@section('tracer-active', 'user-active')

@section('content')
    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
        <div class="container-fluid my-3">

            <livewire:tracer.answer-modal/>
            

        </div>
    </section>
@endsection
