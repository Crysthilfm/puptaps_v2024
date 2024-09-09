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
                            <input class="form-check-input" type="checkbox" value="Agree" wire:model="arrayAnswers.{{ $key }}.answer" />
                            <label class="form-check-label">Agree <span class="text-danger">*</span></label>
                        </div>
                    <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                    @endif
                    @endforeach
                    <div class="row mt-3">
                        @if ($currentPage == 1)
                            <div class="col-6 text-start">
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
                                <label class="form-label">Age</label>
                                <input type="text" class="form-control" value="{{ $user->age }}">
                            </div>
                        </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Civil Status</label>
                                    <input type="text" class="form-control" value="{{ $user->civil_status }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label class="form-label">Telephone/Mobile No.</label>
                                <input type="text" class="form-control" value="{{ $user->number }}">
                            </div>
                        </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Student Number</label>
                                    <input type="text" class="form-control" value="{{ $user->stud_number }}">
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
                            @endforeach
                        </div>
                        <div class="col-md-12 mb-2">
                            <hr style="border: 2px solid black;">
                        </div>
                    </fieldset>

                    <div class="row">
                        @foreach ($questions as $key => $value)
                        @if (($value->category_id) == ($category->category_id))
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <div>
                                        <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                        @if ($value->question_type == "text")
                                            <input type="text" class="form-control @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror" wire:model="arrayAnswers.{{ $key }}.answer">
                                            <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                                        @else
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="{{ $value->question_id }}" value="Graduation" wire:model="arrayAnswers.{{ $key }}.answer">
                                                <label class="form-check-label">Graduation</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="{{ $value->question_id }}" value="Personal" wire:model="arrayAnswers.{{ $key }}.answer">
                                                <label class="form-check-label">Personal</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="{{ $value->question_id }}" value="Family" wire:model="arrayAnswers.{{ $key }}.answer">
                                                <label class="form-check-label">Family</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="{{ $value->question_id }}" value="Academic" wire:model="arrayAnswers.{{ $key }}.answer">
                                                <label class="form-check-label">Academic</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="{{ $value->question_id }}" value="Financial" wire:model="arrayAnswers.{{ $key }}.answer">
                                                <label class="form-check-label">Financial</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="{{ $value->question_id }}" value="Work-related" wire:model="arrayAnswers.{{ $key }}.answer">
                                                <label class="form-check-label">Work-related</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="{{ $value->question_id }}" value="Others" wire:model="arrayAnswers.{{ $key }}.answer">
                                                <label class="form-check-label">Others</label>
                                            </div>
                                            <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                                        @endif
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

                {{-- Pages 3 to 16 --}}
                @elseif (($currentPage != 1) && ($currentPage != 2) && ($currentPage != 17) && ($currentPage == $category->category_id))
                <div class="col-12 form-box-content ps-3 pe-3 px-md-4 px-lg-4 px-xl-4 pt-3">
                    <div class="text-end mb-2 d-block d-sm-block d-md-block d-lg-none d-xl-none ">
                        <button class="btn btn-primary fs-7 " type="button" data-bs-toggle="collapse" data-bs-target="#collapseScaleEIF" aria-expanded="false">Scale <i class="fs-7 fa-solid fa-caret-down"></i></button>
                    </div>
                    <div class="card text-center mb-3 collapse d-lg-block" id="collapseScaleEIF">
                        <div class="card-body d-block d-sm-block d-md-block d-lg-flex d-xl-flex justify-content-between align-items-center">
                            <p class="my-0 py-0 me-2">Scale: </p>
                            <p class="my-0 py-0 mx-3 fw-bold">5 - Outstanding</p>
                            <p class="my-0 py-0 mx-3 fw-bold">4 - Very Satisfactory</p>
                            <p class="my-0 py-0 mx-3 fw-bold">3 - Satisfactory</p>
                            <p class="my-0 py-0 mx-3 fw-bold">2 - Fair</p>
                            <p class="my-0 py-0 mx-3 fw-bold">1 - Poor</p>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table">
                            <thead class="thead-sticky table-dark">
                                <tr>
                                    <th scope="col" style="width: 550px">Questions</th>
                                    <th scope="col" style="width: 50px">5</th>
                                    <th scope="col" style="width: 50px">4</th>
                                    <th scope="col" style="width: 50px">3</th>
                                    <th scope="col" style="width: 50px">2</th>
                                    <th scope="col" style="width: 50px">1</th>
                                </tr>
                            </thead>
                            <tbody>

                                    @foreach ($questions as $key => $value)
                                    @if (($value->category_id) == ($category->category_id) && ($value->question_type) != 'textarea')
                                    <tr>
                                        <td>
                                            {{ $value->question_text }} <span class="text-danger">*</span>
                                            <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" name="{{ $value->question_id }}" type="radio" value="5" wire:model="arrayAnswers.{{ $key }}.answer">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" name="{{ $value->question_id }}" type="radio" value="4" wire:model="arrayAnswers.{{ $key }}.answer">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" name="{{ $value->question_id }}" type="radio" value="3" wire:model="arrayAnswers.{{ $key }}.answer">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" name="{{ $value->question_id }}" type="radio" value="2" wire:model="arrayAnswers.{{ $key }}.answer">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" name="{{ $value->question_id }}" type="radio" value="1" wire:model="arrayAnswers.{{ $key }}.answer">
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                            </tbody>
                        </table>
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

                {{-- Page 17 --}}
                @elseif (($currentPage == 17) && ($currentPage == $category->category_id))
                <div class="col-12 form-box-content">
                    <div class="row justify-content-center">
                        @foreach ($questions as $key => $value)
                        @if (($value->category_id) == ($category->category_id))
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <div>
                                        @if ($value->question_type != 'textarea')
                                            <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                            <input type="{{ $value->question_type }}" class="form-control @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror" wire:model="arrayAnswers.{{ $key }}.answer" placeholder="{{ $value->question_placeholder }}">
                                            <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                                        @endif
                                        @if ($value->question_type == 'textarea')
                                            <label class="form-label">{{ $value->question_text }} <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('arrayAnswers.' . $key . '.answer') is-invalid @enderror" wire:model="arrayAnswers.{{ $key }}.answer"></textarea>
                                            <span class="text-danger error-message">@error('arrayAnswers.' . $key . '.answer'){{ $message }}@enderror</span>
                                        @endif
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
