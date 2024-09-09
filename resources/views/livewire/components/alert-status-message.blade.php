<div class="row justify-content-center">
    <div class="col-md-12">
        @if(Session::has('success'))
            <div id="myModal" class="modal fade" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-body">
                        <div class="swal2-icon swal2-success swal2-animate-success-icon mb-4" style="display: flex;">
                            <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                            <span class="swal2-success-line-tip"></span>
                            <span class="swal2-success-line-long"></span>
                            <div class="swal2-success-ring"></div>
                            <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                            <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                        </div>
                        <h3 class="text-success text-center mt-4 mb-0">Success!</h3>
                        <p class="text-center fs-7 mb-1 mt-0" role="alert">
                            {{ $message }}
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light text-center w-100 text-primary" data-bs-dismiss="modal">Continue</button>
                    </div>
                    </div>
                </div>
            </div>
        @elseif($errors->any() || Session::has('fail'))
            <div id="myModal" class="modal fade" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    <div class="modal-body">
                        <div class="swal2-icon swal2-error swal2-animate-error-icon mb-3" style="display: flex;">
                            <span class="swal2-x-mark">
                                <span class="swal2-x-mark-line-left"></span>
                                <span class="swal2-x-mark-line-right"></span>
                            </span>
                        </div>
                        <h3 class="text-danger text-center mt-3 mb-0">Error!</h3>
                        <p class="text-center fs-7 mb-1 mt-0" role="alert">
                            @if (Session::has('fail'))
                                <span>{{ Session::get('fail') }}</span>
                            @elseif ($message == null)
                                <span>Fields with (<span class="text-danger">*</span>) are required. It should not be empty.</span>
                            @else
                                {{ $message }}
                            @endif
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light text-center w-100 text-primary" data-bs-dismiss="modal">Continue</button>
                    </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
