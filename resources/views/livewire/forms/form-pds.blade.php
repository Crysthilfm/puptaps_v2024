<div>
    <form wire:submit.prevent="saveAnswer">
        <div class="row justify-content-center">
            @foreach ($categories as $category)
                @if (($currentPage == $category->category_id))
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
                @endif
                {{-- Page 1 --}}
                @if (($currentPage == 1) && ($currentPage == $category->category_id))
                <div class="col-12 form-box-content">
                    <livewire:forms.data-privacy />

                    @foreach ($questions as $key => $value)
                    @if (($value->category_id) == ($category->category_id))
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="Agree" wire:model.lazy="arrayAnswers.{{ $key }}.answer" />
                            <label class="form-check-label">Agree <span class="text-danger">*</span></label>
                        </div>
                    <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                    @endif
                    @endforeach
                    <div class="row mt-3">
                        @if ($currentPage == 1)
                            <div class="col-6 text-start">
                                <button href="#go-to-top" class="btn btn-secondary px-3 fs-7" type="button" wire:click="previousPage()" disabled><i class="fa-solid fa-caret-left"></i> Back</button>
                            </div>
                        @endif

                        @if ($currentPage < $totalPage && $currentPage != $totalPage)
                            <div class="col-6 text-end">
                                <a href="#go-to-top" class="btn btn-primary px-3 fs-7" type="button" wire:click="nextPage()">Next <i class="fa-solid fa-caret-right"></i></a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Page 2 --}}
                @elseif (($currentPage == 2) && ($currentPage == $category->category_id))
                <div class="col-12 form-box-content">
                    <fieldset disabled>
                        <div class="row">
                            @foreach ($users as $user)

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="text" class="form-control" value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" value="{{ $user->last_name }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" value="{{ $user->first_name }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" value="{{ $user->middle_name }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Suffix</label>
                                    <input type="text" class="form-control" value="{{ $user->suffix }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">sex</label>
                                    <select class="form-select">
                                        <option>{{ $user->sex }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" value="{{ $user->birthday }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Age</label>
                                    <input type="text" class="form-control" value="{{ $user->age }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Religion</label>
                                    <input type="text" class="form-control" value="{{ $user->religion }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Course</label>
                                    <select class="form-select">
                                        <option>{{ $user->course_id }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Year Graduated</label>
                                    <select class="form-select">
                                        <option>{{ $user->batch }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="form-label">City Address</label>
                                    <input type="text" class="form-control" value="{{ $user->city_address }}">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Provincial Address</label>
                                    <input type="text" class="form-control" value="{{ $user->provincial_address }}">
                                </div>
                            </div>
                            <div class="col-md-8 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Telephone/Mobile No.</label>
                                    <input type="text" class="form-control" value="{{ $user->number }}">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </fieldset>

                    <div class="row">
                        @foreach ($questions as $key => $value)
                        @if (($value->category_id) == ($category->category_id))
                            @if ($key == 2 || $key == 4)
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <div>
                                            <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror" wire:model.lazy="arrayAnswers.{{ $key }}.answer" placeholder="{{ $value->question_placeholder }}" onkeydown="return onlyNumber(event)" minlength="11" maxlength="20">
                                            <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <div>
                                            <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror" wire:model.lazy="arrayAnswers.{{ $key }}.answer" placeholder="{{ $value->question_placeholder }}" onkeypress="return onlyAlphabets(event)">
                                            <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @endforeach
                    </div>

                    <div class="row mt-3">
                        @if ($currentPage <= $totalPage && $currentPage != 1)
                            <div class="col-6 text-start">
                                <a href="#go-to-top" class="btn btn-secondary px-3 fs-7" type="button" wire:click="previousPage()"><i class="fa-solid fa-caret-left"></i> Back</a>
                            </div>
                        @endif

                        @if ($currentPage < $totalPage && $currentPage != $totalPage)
                            <div class="col-6 text-end">
                                <a href="#go-to-top" class="btn btn-primary px-3 fs-7" type="button" wire:click="nextPage()">Next <i class="fa-solid fa-caret-right"></i></a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Page 3 --}}
                @elseif (($currentPage == 3) && ($currentPage == $category->category_id))
                <div class="col-12 form-box-content">
                    <div class="row">
                        @foreach ($questions as $key => $value)
                        @if (($value->category_id) == ($category->category_id))
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <div>
                                        <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror" wire:model.lazy="arrayAnswers.{{ $key }}.answer" placeholder="{{ $value->question_placeholder }}">
                                        <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @endforeach
                    </div>
                    <div class="row mt-3">
                        @if ($currentPage <= $totalPage && $currentPage != 1)
                            <div class="col-6 text-start">
                                <a href="#go-to-top" class="btn btn-secondary px-3 fs-7" type="button" wire:click="previousPage()"><i class="fa-solid fa-caret-left"></i> Back</a>
                            </div>
                        @endif

                        @if ($currentPage < $totalPage && $currentPage != $totalPage)
                            <div class="col-6 text-end">
                                <a href="#go-to-top" class="btn btn-primary px-3 fs-7" type="button" wire:click="nextPage()">Next <i class="fa-solid fa-caret-right"></i></a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Page 4 --}}
                @elseif (($currentPage == 4) && ($currentPage == $category->category_id))
                <div class="col-12 form-box-content">
                    <div class="row">
                        @foreach ($questions as $key => $value)
                        @if (($value->category_id) == ($category->category_id))
                            @if ($value->question_id == 9 || $value->question_id == 10)
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <div>
                                            <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror" wire:model.lazy="arrayAnswers.{{ $key }}.answer" placeholder="{{ $value->question_placeholder }}">
                                            <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($value->question_id == 11)
                                <div class="col-12 text-end">
                                    <button type="button" class="fs-7 btn btn-success collapse {{ $showButton2 }}" data-bs-toggle="collapse" wire:click="changeToShow2()"><i class="fa-solid fa-plus"></i></button>
                                </div>
                            @endif

                            @if ($value->question_id == 11 || $value->question_id == 12)
                                <div class="col-md-6 mb-3 collapse {{ $showSeminar2 }}">
                                    <div class="form-group">
                                        <div>
                                            <label class="form-label">{{ $value->question_text }} (2<sup>nd</sup>) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror" wire:model.lazy="arrayAnswers.{{ $key }}.answer" placeholder="{{ $value->question_placeholder }}">
                                            <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($value->question_id == 13)
                                <div class="col-12 text-end">
                                    <button type="button" class="fs-7 btn btn-success collapse {{ $showButton3 }}" data-bs-toggle="collapse" wire:click="changeToShow3()"><i class="fa-solid fa-plus"></i></button>
                                </div>
                            @endif

                            @if ($value->question_id == 13 || $value->question_id == 14)
                                <div class="col-md-6 mb-3 collapse {{ $showSeminar3 }}">
                                    <div class="form-group">
                                        <div>
                                            <label class="form-label">{{ $value->question_text }} (3<sup>rd</sup>) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror" wire:model.lazy="arrayAnswers.{{ $key }}.answer" placeholder="{{ $value->question_placeholder }}">
                                            <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @endforeach
                    </div>
                    <div class="row mt-3">
                        @if ($currentPage <= $totalPage && $currentPage != 1)
                            <div class="col-6 text-start">
                                <a href="#go-to-top" class="btn btn-secondary px-3 fs-7" type="button" wire:click="previousPage()"><i class="fa-solid fa-caret-left"></i> Back</a>
                            </div>
                        @endif

                        @if ($currentPage < $totalPage && $currentPage != $totalPage)
                            <div class="col-6 text-end">
                                <a href="#go-to-top" class="btn btn-primary px-3 fs-7" type="button" wire:click="nextPageSeminars()">Next <i class="fa-solid fa-caret-right"></i></a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Page 5 --}}
                @elseif (($currentPage == 5) && ($currentPage == $category->category_id))
                <div class="col-12 form-box-content">
                    <div class="row justify-content-center">
                        <div class="col-11 my-3">
                            <p>
                                This is to signify that I am willing to be subjected to company calls for placement or employment purposes. This shall also authorize the Polytechnic University of The Philippines – Career Development and Placement Office (PUP-CDPO) to include my name and contact details in the directory of graduates.
                            </p>
                        </div>
                        @foreach ($questions as $key => $value)
                        @if (($value->category_id) == ($category->category_id))
                            @if ($key == 15)
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <div>
                                            <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                            <input type="{{ $value->question_type }}" class="form-control @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror" wire:model.lazy="arrayAnswers.{{ $key }}.answer" placeholder="{{ $value->question_placeholder }}">
                                            <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <div>
                                            <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                            <input type="{{ $value->question_type }}" class="form-control @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror" wire:model.lazy="arrayAnswers.{{ $key }}.answer" placeholder="{{ $value->question_placeholder }}">
                                            <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @endforeach
                    </div>
                    <div class="row mt-3">
                        @if ($currentPage <= $totalPage && $currentPage != 1)
                            <div class="col-6 text-start">
                                <a href="#go-to-top" class="btn btn-secondary px-3 fs-7" type="button" wire:click="previousPage()"><i class="fa-solid fa-caret-left"></i> Back</a>
                            </div>
                        @endif

                        @if ($currentPage == $totalPage)
                            <div class="col-6 text-end">
                                <button class="btn btn-success px-3 fs-7" type="submit">Submit <i class="fa-solid fa-file-export"></i></button>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </form>
</div>
