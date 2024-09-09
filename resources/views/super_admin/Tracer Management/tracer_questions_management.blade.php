@extends('layouts.super-admin')
@section('page-title', 'Tracer Management')
@section('active-tracerManagement', 'active')
@section('page-name', 'Tracer Management')

@section('content')

@include('sweetalert::alert')

<section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5">
    <div class="container-fluid">
        <?php
            $clickedQuestion = 0;
        ?>

        <div class="row justify-content-center g-0">     
            <div class="col-11 sub-container-box pt-4 pb-3">
                
                <div class="row justify-content-center">
                    <div class="col-2 text-center">
                        <form action="{{ route('superAdmin.getVersion')}}">            
                            <label class="form-label"> Version</label>
                            <div class="d-flex justify-content-center">
                                <div class="input-group w-auto">
                                    <select class="form-select" name="versionID" id="versionID">
                                        @foreach ($version as $v)
                                            <option value="{{$v->tracer_version_id}}" @if ($current_version == $v->tracer_version_id) selected @endif> {{$v->tracer_version_id}}x </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-info"><i class="fa-solid fa-magnifying-glass"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-8 text-center">
                        <h3>Current Tracer Questionnaire</h3>
                    </div>
                    <div class="col-1 text-center">
                        <a class="btn btn-success" href="{{route('superAdmin.getAddQuestion')}}"> <i class="fa-solid fa-circle-plus"></i></a> <br>
                    </div>
                <hr class="mt-2 mb-2 hr-color opacity-100">
                    {{-- Start Boards Question Category --}}
                    <div class="col-11">
                        <h4>Boards/Exams Tracer Questionnaire</h4>
                        <table class="table tableDesign table-striped table-bordered table-sm" cellspacing="0">
                            <thead class="tbl-head">
                                <td>Manage</td>
                                <td>Question</td>
                                <td>Answer Type</td>
                            </thead>
                            <tbody>
                                @foreach ($tracerQuestionsBoards as $a)
                                <tr>
                                    <td>
                                        <a class="btn btn-success btn-sm" href="{{route('superAdmin.getEditQuestion', ['question_id'=>$a->question_id])}}"><i class="fas fa-edit"></i> </a>
                                        
                                        {{-- <a class="btn btn-danger btn-sm" href="{{route('superAdmin.deleteQuestion', ['question_id'=>$a->question_id])}}" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fa fa-trash" aria-hidden="true"></i></a> --}}
                                        <button type="button "class="btn btn-danger btn-sm deleteQuestionBtn" value="{{$a->question_id}}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </td>
                                    <td>{{$a->question_text}}</td>
                                    <td>{{$a->question_type}}</td>
                                </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Start Boards Question Category --}}
                <hr class="mt-2 mb-2 hr-color opacity-100">
                    {{-- Start Current Job Question Category --}}
                    <div class="col-11">
                        <h4>Current Job Tracer Questionnaire</h4>
                        <table class="table tableDesign table-striped table-bordered table-sm" cellspacing="0">
                            <thead class="tbl-head">
                                <td>Manage</td>
                                <td>Question</td>
                                <td>Answer Type</td>
                            </thead>
                            <tbody>
                                @foreach ($tracerQuestionsCurrentJob as $a)
                                <tr>
                                    <td>
                                        <a class="btn btn-success btn-sm" href="{{route('superAdmin.getEditQuestion', ['question_id'=>$a->question_id])}}"><i class="fas fa-edit"></i> </a>
                                        <button type="button "class="btn btn-danger btn-sm deleteQuestionBtn" value="{{$a->question_id}}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </td>
                                    <td>{{$a->question_text}}</td>
                                    <td>{{$a->question_type}}</td>
                                </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>

                    {{-- End Current Job Question Category --}}
                <hr class="mt-2 mb-2 hr-color opacity-100">
                    {{-- Start First Job Question Category --}}
                    <div class="col-11">
                        <h4>First Job Tracer Questionnaire</h4>
                        <table class="table tableDesign table-striped table-bordered table-sm" cellspacing="0">
                            <thead class="tbl-head">
                                <td>Manage</td>
                                <td>Question</td>
                                <td>Answer Type</td>
                            </thead>
                            <tbody>
                                @foreach ($tracerQuestionsFirstJob as $a)
                                <tr>
                                    <td>
                                        <a class="btn btn-success btn-sm" href="{{route('superAdmin.getEditQuestion', ['question_id'=>$a->question_id])}}"><i class="fas fa-edit"></i> </a>
                                        <button type="button "class="btn btn-danger btn-sm deleteQuestionBtn" value="{{$a->question_id}}"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                    </td>
                                    <td>{{$a->question_text}}</td>
                                    <td>{{$a->question_type}}</td>
                                </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                    {{-- End First Job Question Category --}}
                <hr class="mt-2 mb-2 hr-color opacity-100">

                </div>

            </div>
            

            <!-- Delete Modal -->
            <div class="modal fade" id="questionDelete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Are you sure you want to delete this question?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('superAdmin.deleteQuestion')}}" method="GET">
                        <div class="form-group">
                            <label>Question #</label>
                            <input type="text" name="question_id" id="question_id" style="text-align:center; width:25px;" readonly>
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
        </div>
    </div>
</section>

@endsection