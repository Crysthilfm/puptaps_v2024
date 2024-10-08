@extends('layouts.user')
@section('page-title', 'Alumni Tracer')
@section('tracer-active', 'user-active')

@section('content')

    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
        <div class="container-fluid my-3">

            <div class="row justify-content-center g-0">
                <div class="animate__animated animate__fadeInUp text-center mb-2">
                    <h1> Update Tracer </h1><br>
                    <p>{{ $versionName->tracer_version_name}}</p>
                </div>
                <div class="col-11 col-sm-9 col-md-9 col-lg-9 col-xl-9 animate__animated animate__fadeInUp">
                    {{-- <livewire:tracer.update /> --}}
                    
                    {{-- New tracer Update --}}

                    <div>
                        <div class="col-12 row justify-content-center">
                            <form action="{{route('userTracer.saveUpdate')}}" method="POST">
                                @csrf
                            {{-- Loop through each category --}}
                            @foreach ($categories as $category)                                
                                <div class="row col-12 form-box-content form-box-content">
                                    
                                    <h4>{{ $category->category_name}}</h4>

                                    {{-- Category 1 and 2 special selection --}}
                                    @if ($category->category_id == 2)
                                        <div class="form-group">
                                            <input type="checkbox" name="unemployed"> <b>Check if you are still unemployed</b>
                                        </div>
                                    @elseif ($category->category_id == 3)
                                        <div class="form-group">
                                            <input type="checkbox" name="sameCurrent"> <b>Same with current job</b>
                                        </div>
                                    @endif
                    
                                    {{-- Loop through each question per category --}}
                                    @foreach ($questions as $question)
                                        @if($category->category_id == $question->category_id)
                                            {{-- Sets the appropriate type for each question --}}
                                                {{-- Text and Date Questions --}}
                                            @if($question->question_type == "text" || $question->question_type == "date" || $question->question_type == "number")
                                                <div class="form-group col-6 my-2">
                                                    <label class="form-label"> {{$question->question_text}}</label>
                                                    {{-- Check Errors --}}
                                                        @error('q'.$question->question_id)
                                                            <span style="color:#fa0202; font-size:11px;">
                                                            <i class="fa-solid fa-exclamation" style="color: #fa0202;"></i> Required
                                                            </span>
                                                        @enderror
                                                    <input class="form-control" type="{{$question->question_type}}" name="{{ 'q'.$question->question_id}}" value="{{$question->answer}}">
                                                </div>
                                                {{-- Select Questions --}}
                                            @elseif($question->question_type == "select")
                                                <div class="form-group col-6 my-2">                
                                                    <label class="form-label"> {{$question->question_text}}</label>
                                                    {{-- Check Errors --}}
                                                        @error('q'.$question->question_id)
                                                        <span style="color:#fa0202; font-size:11px;">
                                                        <i class="fa-solid fa-exclamation" style="color: #fa0202;"></i> Required
                                                        </span>
                                                        @enderror
    
                                                    <select class="form-select" name="{{ 'q'.$question->question_id}}">
                                                        @if($category->category_id == 1)
                                                            @if ($question->question_id == 3)
                                                                @if ($noBoards == true)
                                                                    <option value="No Boards" selected>No Boards</option>
                                                                @else  
                                                                    @foreach ($options as $option)
                                                                    @if($option->question_id == $question->question_id)
                                                                            <option value="{{$option->option_text}}" name="{{ $option->option_id }}" @if ($question->answer == $option->option_text) selected @endif> {{$option->option_text}} </option>
                                                                    @endif
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        @else
                                                            {{-- Loop through each option --}}
                                                            @foreach ($options as $option)
                                                            @if($option->question_id == $question->question_id)
                                                                    <option value="{{$option->option_text}}" name="{{ $option->option_id }}" @if ($question->answer == $option->option_text) selected @endif> {{$option->option_text}}</option>
                                                            @endif
                                                            @endforeach
                                                        @endif

                                                        
                                                    </select>
                                                </div>
                                                {{-- Radio Questions --}}
                                            @elseif ($question->question_type == "radio")
                                                <div class="form-group col-6 my-2">
                                                    <div class="">
                                                    <label class="form-label"> {{$question->question_text}}</label>
                                                        {{-- Check Errors --}}
                                                        @error('q'.$question->question_id)
                                                            <span style="color:#fa0202; font-size:11px;">
                                                            <i class="fa-solid fa-exclamation" style="color: #fa0202;"></i> Required
                                                            </span>
                                                        @enderror
                                                       
                                                    </div>
                                                    @foreach ($options as $option)
                                                        @if($option->question_id == $question->question_id)
                                                            <div class="form-check form-check-inline">
                                                                @if($category->category_id == 1)
                                                                    @if($question->question_id == 1 || $question->question_id == 2)
                                                                        @if ($noBoards==true)
                                                                            @if ($option->option_text == "Yes")
                                                                                <input class="form-check-input"  type="{{$question->question_type}}" name="{{ 'q'.$question->question_id}}" value="Yes" onclick="javascript: return false;">
                                                                                <label class="form-check-label">Yes</label>
                                                                            @else
                                                                                <input class="form-check-input"  type="{{$question->question_type}}" name="{{ 'q'.$question->question_id}}" value="No" checked>
                                                                                <label class="form-check-label">No</label>
                                                                            @endif
                                                                        @else
                                                                            <input class="form-check-input"  type="{{$question->question_type}}" name="{{ 'q'.$question->question_id}}" value="{{$option->option_text}}" @if ($question->answer == $option->option_text) checked @endif>
                                                                            <label class="form-check-label">{{$option->option_text}}</label>
                                                                        @endif
                                                                    @else
                                                                        <input class="form-check-input"  type="{{$question->question_type}}" name="{{ 'q'.$question->question_id}}" value="{{$option->option_text}}" @if ($question->answer == $option->option_text) checked @endif>
                                                                        <label class="form-check-label">{{$option->option_text}}</label>
                                                                    @endif

                                                                    @else
                                                                        <input class="form-check-input"  type="{{$question->question_type}}" name="{{ 'q'.$question->question_id}}" value="{{$option->option_text}}" @if ($question->answer == $option->option_text) checked @endif>
                                                                        <label class="form-check-label">{{$option->option_text}}</label>
                                                                @endif
                                                                
                                                            </div>
                                                        @endif  
                                                    @endforeach
                                                    
                                                </div>
                                            @endif 
                                        @endif
                                    @endforeach
                                    
                                </div>
                            @endforeach
                                <button class="col-3 btn btn-success">Submit</button>
                            </form>
                        </div>
                    </div>

                    {{-- END NEW UPDATE --}}
                </div>
            </div>
        </div>
    </section>

@endsection
