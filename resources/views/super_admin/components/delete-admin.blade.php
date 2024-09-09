<div class="modal fade" id="deleteAdmin{{ $admin->admin_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert">
                    Are you sure you want to delete admin: <span class="fw-bold">{{ $admin->first_name }} {{ $admin->last_name }}</span>
                </div>
            </div>
            <form action="{{ route("superAdmin.deleteAdmin") }}" method="post">
            @csrf
                <div class="modal-footer flex-nowrap p-0">
                    <input type="hidden" value="{{ $admin->admin_id }}" name="admin_id">
                    <button type="submit" class="py-2 btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end text-danger">Delete</button>
                    <button type="button" class="py-2 btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 text-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
