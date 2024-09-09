{{-- Modal --}}
<div class="modal fade" id="addCareerImage" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Select Image file to upload</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        @if (Auth::user()->user_role == "Admin")
            <form action="{{ route('adminCareer.addImageCareer') }}" method="post" enctype="multipart/form-data">
        @else
            <form action="{{ route('userCareer.addImageCareer') }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
            <div class="modal-body text-start">
                <div class="mb-3">
                  <label class="form-label fs-7">Job Advertisement <span class="text-danger">*</span></label>
                  <input type="file" class="form-control fs-7 @error('job_ad_image') is-invalid animate__animated animate__shakeX @enderror" name="job_ad_image">
                  <span class="error-message text-danger">@error('job_ad_image') {{$message}} @enderror</span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Job Name/Job Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('job_name_img') is-invalid animate__animated animate__shakeX @enderror" name="job_name_img" value="{{ old('job_name_img') }}">
                    <span class="error-message text-danger">@error('job_name_img') {{$message}} @enderror</span>
                </div>
                <div class="mb-3">
                    <label class="form-label fs-7">Category <span class="text-danger">*</span></label>
                    <select class="form-select fs-7 @error('category_img') is-invalid animate__animated animate__shakeX @enderror" aria-label="Default select example" name="category_img">
                        <option value="" hidden selected>Please select one...</option>
                        @foreach ($careerCategories as $category)
                            <option class="fs-7" value="{{ $category->career_category }}">{{ $category->career_category }}</option>
                        @endforeach
                    </select>
                    <span class="error-message text-danger">@error('category_img') {{$message}} @enderror</span>
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
