@extends('layouts.super-admin')
@section('content')

<section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5">
    <div class="container-fluid">
        <div class="row justify-content-center g-0">
            <div class="row col-10 sub-container-box pt-4 pb-3 justify-content-center">
                <h4>Add a question</h4>
                <div class="col-8">
                <form action="{{route('superAdmin.saveQuestion')}}" method="GET">
                    @csrf
                    <div class="form-group">
                        <label for="">Input question content</label>
                        <input class="form-control" type="text" name="question_text" placeholder="Input question">
                        <span class="text-danger error-message">
                                @error ('question_text')
                                <i class="fa-solid fa-circle-exclamation ml-5"></i>
                                {{ $message }}
                                @enderror
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect2">Category Type</label>
                        <select class="form-control" id="exampleFormControlSelect2" name="category_id">
                          <option value="1">Boards/Exams</option>
                          <option value="2">Current Job</option>
                          <option value="3">First Job</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect2">Select answer type</label>
                        <select class="form-control" id="type" name="question_type" onchange="showOptions()">
                          <option value="text">Text</option>
                          <option value="radio">Radio</option>
                          <option value="select">Select</option>
                          <option value="date">Date</option>
                        </select>
                    </div>
                    <div class="form-group mt-2" id="options" style="display: none;">
                        <label for="exampleFormControlSelect2">Add Options</label>
                        <input class="form-control" type="text" placeholder="1 Option" name="option1">
                        <input class="form-control" type="text" placeholder="2 Option" name="option2">
                        <input class="form-control" type="text" placeholder="3 Option" name="option3">
                        <input class="form-control" type="text" placeholder="4 Option" name="option4">
                    </div>
                    <div class="col-6 text-end form-group mt-3">
                        <button class="btn btn-success px-3 fs-7" type="submit">Submit <i class="fa-solid fa-file-export"></i></button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showOptions(){
            console.log(document.getElementById('type').value);
            if(document.getElementById('type').value=="select" || document.getElementById('type').value=="radio"){
                document.getElementById('options').style.display = 'block';
            } else {
                document.getElementById('options').style.display = 'none';
            }
        }
    </script>
</section>
@endsection