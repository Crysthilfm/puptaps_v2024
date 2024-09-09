<div>
    <form wire:submit.prevent="updateAnswer">
        @foreach ($categories as $category)
            @if (($currentPage == $category->category_id) && ($currentPage == $category->category_id))

            <div class="row justify-content-center">
                <div class="col-12 form-box-title pb-2">
                    <div class="row g-0">
                        <div class="col-12 mt-1">
                            <div class="progress progress-style">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="{{ $progressBar }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progressBar }}%">{{ $progressBar }}%</div>
                            </div>
                        </div>
                        <div class="col-12 text-center mt-3">
                            <h5>Page {{ $currentPage }} of {{ $totalPage }} - {{ $category->category_name }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-12 form-box-content">
                    <div class="row">
                        @if ($currentPage == 1)
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:click="board_value()" @if($no_board_exam == 'NO_BOARD_EXAM')checked @endif>
                                    <label class="form-check-label">
                                        My course doesn't have a Board Exam
                                    </label>
                                </div>
                            </div>

                            @foreach ($questions as $key => $value)
                            @if (($value->category_id) == ($category->category_id))
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-3">
                                    <div class="form-group">
                                        @if (($value->question_type == "text") || ($value->question_type == "date"))
                                            <div>
                                                <label class="form-label">{{ $value->question_text}} <span class="text-danger">*</span></label>
                                                <input type="{{ $value->question_type }}" class="form-control @if ($arrayAnswers[$key]['answer'] == null) @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror @endif" wire:model="arrayAnswers.{{ $key }}.answer" @if ($no_board_exam == 'NO_BOARD_EXAM')disabled @endif>
                                                <span class="text-danger error-message">
                                                    @if ($arrayAnswers[$key]['answer'] == null)
                                                        @error('arrayAnswers.' . $key . '.answer')
                                                        <i class="fa-solid fa-circle-exclamation ml-5"></i>
                                                        {{ $message }}
                                                        @enderror
                                                    @endif
                                                </span>
                                            </div>
                                        @elseif ($value->question_type == "select")
                                            <div>
                                                <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                                <select class="form-select @if ($arrayAnswers[$key]['answer'] == null) @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror @endif" wire:model="arrayAnswers.{{ $key }}.answer" @if ($no_board_exam == 'NO_BOARD_EXAM')disabled @endif>
                                                    <option selected hidden>Please select one...</option>
                                                    <option value="Electronics Engineer Licensure Examination">Electronics Engineer Licensure Examination</option>
                                                    <option value="Licensure Examination for Teachers">Licensure Examination for Teachers</option>
                                                    <option value="Certified Public Accountant Board Exam">Certified Public Accountant Board Exam</option>
                                                    <option value="Professional Mechanical Engineer">Professional Mechanical Engineer</option>
                                                    <option value="N/A">Not Applicable</option>
                                                </select>
                                                <span class="text-danger error-message">
                                                    @if ($arrayAnswers[$key]['answer'] == null)
                                                        @error('arrayAnswers.' . $key . '.answer')
                                                        <i class="fa-solid fa-circle-exclamation ml-5"></i>
                                                        {{ $message }}
                                                        @enderror
                                                    @endif
                                                </span>
                                            </div>
                                        @elseif ($value->question_type == "radio")
                                            @if ($value->question_id != 5)
                                                <div>
                                                    <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                                    <div class="my-0"></div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" name="{{ $value->question_id }}" type="radio" value="Yes" wire:model="arrayAnswers.{{ $key }}.answer" @if ($no_board_exam == 'NO_BOARD_EXAM')disabled @endif>
                                                        <label class="form-check-label">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" name="{{ $value->question_id }}" type="radio" value="No" wire:model="arrayAnswers.{{ $key }}.answer" @if ($no_board_exam == 'NO_BOARD_EXAM')disabled @endif>
                                                        <label class="form-check-label">No</label>
                                                    </div>
                                                </div>
                                            @else
                                                <div>
                                                    <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                                    <div class="my-0"></div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" name="{{ $value->question_id }}" type="radio" value="Yes" wire:model="arrayAnswers.{{ $key }}.answer">
                                                        <label class="form-check-label">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" name="{{ $value->question_id }}" type="radio" value="No" wire:model="arrayAnswers.{{ $key }}.answer">
                                                        <label class="form-check-label">No</label>
                                                    </div>
                                                </div>
                                            @endif
                                            <span class="text-danger error-message">
                                                @if ($arrayAnswers[$key]['answer'] == null)
                                                    @error('arrayAnswers.' . $key . '.answer')
                                                    <i class="fa-solid fa-circle-exclamation ml-5"></i>
                                                    {{ $message }}
                                                    @enderror
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @endforeach
                        @endif
                        @if ($currentPage == 2)
                            @foreach ($questions as $key => $value)
                            @if (($value->category_id) == ($category->category_id))
                                @if ($key == 13)
                                <div class="col-12 mb-3">
                                @else
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-3">
                                @endif
                                    <div class="form-group">
                                        @if (($value->question_type == "text") || ($value->question_type == "date"))
                                            <div>
                                                <label class="form-label">{{ $value->question_text}} <span class="text-danger">*</span></label>
                                                <input type="{{ $value->question_type }}" class="form-control @if ($arrayAnswers[$key]['answer'] == null) @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror @endif" wire:model="arrayAnswers.{{ $key }}.answer" @if($currently_unemployed == 'CURRENTLY_UNEMPLOYED')disabled @endif>
                                                <span class="text-danger error-message">
                                                    @if ($arrayAnswers[$key]['answer'] == null)
                                                        @error('arrayAnswers.' . $key . '.answer')
                                                        <i class="fa-solid fa-circle-exclamation ml-5"></i>
                                                        {{ $message }}
                                                        @enderror
                                                    @endif
                                                </span>
                                            </div>
                                        @elseif ($value->question_type == "select")
                                            <div>
                                                <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                                @if ($value->question_text == "Type of Employment")
                                                    <select class="form-select @if ($arrayAnswers[$key]['answer'] == null) @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror @endif" wire:model="arrayAnswers.{{ $key }}.answer" @if($currently_unemployed == 'CURRENTLY_UNEMPLOYED')disabled @endif>
                                                        <option selected hidden>Please select one...</option>
                                                        <option value="Probitionary">Probitionary</option>
                                                        <option value="Regular">Regular</option>
                                                        <option value="Contractual Basis">Contractual Basis</option>
                                                        <option value="Others">Others</option>
                                                        <option value="Not Applicable">Not Applicable</option>
                                                    </select>
                                                @elseif ($value->question_text == "Monthly Income")
                                                    <select class="form-select @if ($arrayAnswers[$key]['answer'] == null) @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror @endif" wire:model="arrayAnswers.{{ $key }}.answer" @if($currently_unemployed == 'CURRENTLY_UNEMPLOYED')disabled @endif>
                                                        <option selected hidden>Please select one...</option>
                                                        <option value="₱10,000 - ₱15,000">₱10,000 - ₱15,000</option>
                                                        <option value="₱16,000 - ₱20,000">₱16,000 - ₱20,000</option>
                                                        <option value="₱21,000 - ₱25,000">₱21,000 - ₱25,000</option>
                                                        <option value="₱26,000 - ₱30,000">₱26,000 - ₱30,000</option>
                                                        <option value="₱31,000 - ₱35,000">₱31,000 - ₱35,000</option>
                                                        <option value="₱36,000 - ₱40,000">₱36,000 - ₱40,000</option>
                                                        <option value="₱41,000 - ₱45,000">₱41,000 - ₱45,000</option>
                                                        <option value="₱46,000 and above">₱46,000 and above</option>
                                                        <option value="Not Applicable">Not Applicable</option>
                                                    </select>
                                                @endif
                                                <span class="text-danger error-message">
                                                    @if ($arrayAnswers[$key]['answer'] == null)
                                                        @error('arrayAnswers.' . $key . '.answer')
                                                        <i class="fa-solid fa-circle-exclamation ml-5"></i>
                                                        {{ $message }}
                                                        @enderror
                                                    @endif
                                                </span>
                                            </div>
                                        @elseif ($value->question_type == "radio")
                                            <div>
                                                <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                                <div class="my-0"></div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" name="{{ $value->question_id }}" type="radio" value="Yes" wire:model="arrayAnswers.{{ $key }}.answer" @if($currently_unemployed == 'CURRENTLY_UNEMPLOYED')disabled @endif>
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" name="{{ $value->question_id }}" type="radio" value="No" wire:model="arrayAnswers.{{ $key }}.answer" @if($currently_unemployed == 'CURRENTLY_UNEMPLOYED')disabled @endif>
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            </div>
                                            <span class="text-danger error-message">
                                                @if ($arrayAnswers[$key]['answer'] == null)
                                                    @error('arrayAnswers.' . $key . '.answer')
                                                    <i class="fa-solid fa-circle-exclamation ml-5"></i>
                                                    {{ $message }}
                                                    @enderror
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @endforeach
                        @endif
                        <div class="col-12 mt-0">
                            <div class="row">
                                @if ($currentPage == 1)
                                    <div class="col-6 text-start">
                                        <button class="btn btn-secondary px-3 fs-7" wire:click.prevent="previousPage()" disabled><i class="fa-solid fa-caret-left"></i> Back</button>
                                    </div>
                                @endif

                                @if ($currentPage <= $totalPage && $currentPage != 1)
                                    <div class="col-6 text-start">
                                        <button class="btn btn-secondary px-3 fs-7" wire:click.prevent="previousPage()"><i class="fa-solid fa-caret-left"></i> Back</button>
                                    </div>
                                @endif

                                @if ($currentPage < $totalPage && $currentPage != $totalPage)
                                    <div class="col-6 text-end">
                                        <button class="btn btn-primary px-3 fs-7" wire:click.prevent="nextPage()">Next <i class="fa-solid fa-caret-right"></i></button>
                                    </div>
                                @endif

                                @if ($currentPage == $totalPage)
                                    <div class="col-6 text-end">
                                        <button class="btn btn-success px-3 fs-7" type="submit">Submit <i class="fa-solid fa-file-export"></i></button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @endif
        @endforeach
    </form>
</div>
