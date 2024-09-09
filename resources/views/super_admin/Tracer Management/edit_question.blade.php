@extends('layouts.super-admin')
@section('content')

<section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5">
    <div class="container-fluid">
        <div class="row justify-content-center g-0">
            @if(session('status'))
                <div class="col-11 sub-container-box pt-4 pb-3 alert alert-success">
                    {{session('status')}}
                </div>
            @endif
            <div class="row col-10 sub-container-box pt-4 pb-3 justify-content-center">
                <h4>Edit a question</h4>
                <div class="col-5">
                <form action="{{route('superAdmin.saveEditQuestion')}}" method="GET">
                    @csrf
                    <input type="hidden" value="{{$question->question_id}}" name="question_id">
                    <div class="form-group">
                        <label for="">Question Category</label>
                        {{-- <input class="form-control" type="number" value="{{$question->category_id}}" name="category_id"> --}}
                        <select class="form-control" name="category_id">
                            <option @if ($question->category_id == 1) selected @endif value='1'>Boards and Exams</option>
                            <option @if ($question->category_id == 2) selected @endif value='2'>Current Job</option>
                            <option @if ($question->category_id == 3) selected @endif value='3'>First Job</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Question Text</label>
                        @error ('question_text')
                            <span style="color:#fa0202; font-size:11px;">
                            <i class="fa-solid fa-exclamation" style="color: #fa0202;"></i> This field is required
                            </span>
                        @enderror 
                        <input class="form-control" type="text" value="{{$question->question_text}}" name="question_text">
                    </div>
                    <div class="form-group">
                        <label for="">Question Type</label>
                        <select class="form-control" id="type" name="question_type">
                            <option value="text" @if($question->question_type == "text") selected @endif>Text</option>
                            <option value="radio" @if($question->question_type == "radio") selected @endif>Radio</option>
                            <option value="select" @if($question->question_type == "select") selected @endif>Select</option>
                            <option value="date" @if($question->question_type == "date") selected @endif>Date</option>
                          </select>
                    </div>
                    <div class="col-6 text-end form-group mt-3">
                        <button class="btn btn-success px-3 fs-7" type="submit">Submit <i class="fa-solid fa-file-export"></i></button>
                    </div>
                </form>
                </div>
                <div class="col-6">
                    @if($question->question_type == "select" || $question->question_type == "radio")
                    <table class="table tableDesign table-striped table-bordered table-sm" cellspacing="0">
                        <thead class="tbl-head">
                            <td>Action</td>
                            <td>Option text</td>
                        </thead>
                        <tbody>
                            @foreach ($options as $option)
                            <tr>
                                <td>
                                    <button type="button "class="btn btn-success btn-sm editOptionBtn" value="{{$option->option_id}}"><i class="fas fa-edit"></i> </button>
                                    <button type="button "class="btn btn-danger btn-sm deleteOptionBtn" value="{{$option->option_id}}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </td>
                                <td>{{$option->option_text}}</td>
                            </tr>
                            @endforeach 
                        </tbody>
                    </table>
                    <form class="form" action="{{route('superAdmin.addOption')}}" method="GET">
                        
                        <label for="">Insert a new option here:</label>
                        @error ('option_text')
                            <span style="color:#fa0202; font-size:11px;">
                            <i class="fa-solid fa-exclamation" style="color: #fa0202;"></i> This field is required
                            </span>
                        @enderror  
                        <input type="text" name="question_id" value="{{$question->question_id}}" placeholder="{{$question->question_id}}" style="display:none;">
                        <input class="form-control" style="width:70%;" type="text" name="option_text">
                        <button class="btn btn-success btn-sm" type="submit">Add Option</button>
                    </form>
                @endif
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="optionDelete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Are you sure you want to delete this question?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('superAdmin.deleteOption')}}" method="GET">
                        <div class="form-group">
                            <label>Option #</label>
                            <input type="text" name="option_id" id="option_id" style="text-align:center;    width:25px;" readonly>
                            will be deleted permanently
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Understood</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>

        <!-- Edit Option Modal -->
        <div class="modal fade" id="optionEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Option</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('superAdmin.editOption')}}" method="GET">
                        <div class="form-group">
                            <div class="form-group">
                                <label>Option: </label>
                                <input type="text" name="option_id" id="option_id_edit" style="text-align:center; width:60px" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Change text content to:</label>
                                @error ('option_text')
                                    <span style="color:#fa0202; font-size:11px;">
                                    <i class="fa-solid fa-exclamation" style="color: #fa0202;"></i> This field is required
                                    </span>
                                @enderror  
                                <input type="text" name="option_change" id="option_change" style="text-align:center;">
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
        
    </div>
</section>
@endsection