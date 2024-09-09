<div>

    <div class="col-12 row justify-content-center">
        <form wire:submit.prevent="submit">
        {{-- Loop through each category --}}
        @foreach ($categories as $category)
            <div class="row col-12 form-box-content form-box-content">
                <h4>{{ $category->category_name}}</h4>

                {{-- Loop through each question per category --}}
                @foreach ($questions as $question)
                    @if($category->category_id == $question->category_id)
                        {{-- Sets the appropriate type for each question --}}
                            {{-- Text and Date Questions --}}
                        @if($question->question_type == "text" || $question->question_type == "date")
                            <div class="form-group col-6">
                                <label class="form-label"> {{$question->question_text}}</label>
                                <input class="form-control" type="{{$question->question_type}}" name="{{ $question->question_id}}" wire:model="arrayAnswers.{{$question->question_id}}.answer"> 
                            </div>
                            {{-- Select Questions --}}
                        @elseif($question->question_type == "select")
                            <div class="form-group col-6">
                                <label class="form-label"> {{$question->question_text}}</label>
                                <select class="form-select" name="{{ $question->question_id}}" wire:model="arrayAnswers.{{$question->question_id}}.answer">
                                    <option selected hidden>Please select one...</option>
                                    {{-- Loop through each option --}}
                                    @foreach ($options as $option)
                                        @if($option->question_id == $question->question_id)
                                        <option value="{{$option->option_text}}"> {{$option->option_text}} </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endif 
                    @endif
                @endforeach
                
                {{-- TEST --}}


                {{-- TEST END --}}
            </div>
        @endforeach
            <button class="col-3 btn btn-success">Submit</button>
        </form>
    </div>
</div>
