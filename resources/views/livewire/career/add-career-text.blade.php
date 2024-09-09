{{-- Modal --}}
<div class="modal fade" id="addCareerText" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Job Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        @if (Auth::user()->user_role == "Admin")
            <form action="{{ route('adminCareer.addTextCareer') }}" method="post">
        @else
            <form action="{{ route('userCareer.addTextCareer') }}" method="post">
        @endif
        @csrf
            <div class="modal-body text-start fs-7">
                <div class="row">
                    <div class="mb-3 col-12">
                        <label class="form-label">Job Name/Job Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('job_name') is-invalid animate__animated animate__shakeX @enderror" name="job_name" value="{{ old('job_name') }}">
                        <span class="error-message text-danger">@error('job_name') {{$message}} @enderror</span>
                    </div>
                    <div class="mb-3 col-12">
                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('company') is-invalid animate__animated animate__shakeX @enderror" name="company" value="{{ old('company') }}">
                        <span class="error-message text-danger">@error('company') {{$message}} @enderror</span>
                    </div>
                    <div class="mb-3 col-12">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" rows="5" name="description" style="white-space: pre-wrap" id="rte_description" hidden value="{{ old('rte_description') }}"></textarea>
                        <trix-editor input="rte_description" class="trix-content fs-7 @error('rte_description') is-invalid animate__animated animate__shakeX @enderror" value="{{ old('rte_description') }}"></trix-editor>
                        <span class="error-message text-danger">@error('description') {{$message}} @enderror</span>
                    </div>
                    <div class="mb-3 col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid animate__animated animate__shakeX @enderror" aria-label="Default select example" name="category">
                                <option value="" select hidden>Please select one...</option>
                            @foreach ($careerCategories as $category)
                                <option value="{{ $category->career_category }}">{{ $category->career_category }}</option>
                            @endforeach
                        </select>
                        <span class="error-message text-danger">@error('category') {{$message}} @enderror</span>
                    </div>
                    <div class="mb-3 col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <label class="form-label">Salary <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('salary') is-invalid animate__animated animate__shakeX @enderror" name="salary" value="{{ old('salary') }}">
                        <span class="error-message text-danger">@error('salary') {{$message}} @enderror</span>
                    </div>
                    <div class="mb-3 col-12">
                        <label class="form-label">Company Email <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('email') is-invalid animate__animated animate__shakeX @enderror" name="email" value="{{ old('email') }}">
                        <span class="error-message text-danger">@error('email') {{$message}} @enderror</span>
                    </div>
                    <div class="mb-3 col-12">
                        <label class="form-label">Company Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('number') is-invalid animate__animated animate__shakeX @enderror" name="number" value="{{ old('number') }}">
                        <span class="error-message text-danger">@error('number') {{$message}} @enderror</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary fs-7" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary fs-7">Submit</button>
            </div>
        </form>
      </div>
    </div>
</div>
