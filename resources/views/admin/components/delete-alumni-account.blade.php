<!-- Modal -->
<div class="modal fade" id="deleteAlumni{{ $alum->alumni_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('adminUserManagement.deleteAlumniProfile') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $alum->alumni_id }}" name="alumni_id">
                <div class="modal-body">
                    <div class="swal2-icon swal2-warning mb-3" style="display: flex;">
                        <i class="fa-solid fa-exclamation" style="font-size: 72px; font-weight: lighter;"></i>
                    </div>
                    <h3 class="text-center mt-3 mb-2" style="color: #f8bb86;">Warning!</h3>
                    <p class="text-center fs-7 mb-1 mt-2" role="alert">Are you sure you want to delete the account and details of <br> <span class="fs-6"><b>{{ strtoupper($alum->last_name) }}</b>, {{ $alum->first_name }} - <b>[{{ $alum->stud_number }}]</b></span></p>
                </div>
                <div class="modal-footer p-0">
                    <button type="button" class="col-6 m-0 p-2 btn btn-link text-decoration-none border-end rounded-0 text-dark" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="col-6 m-0 p-2 btn btn-link text-decoration-none rounded-0 text-danger">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
