<!-- Modal -->
<div class="modal fade" id="deleteAnnouncement{{$announcement->announcement_id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="swal2-icon swal2-warning mb-3" style="display: flex;">
                    <i class="fa-solid fa-exclamation" style="font-size: 72px; font-weight: lighter;"></i>
                </div>
                <h3 class="text-center mt-3 mb-2" style="color: #f8bb86;">Warning!</h3>
                <p class="text-center fs-7 mb-1 mt-2" role="alert">Are you sure you want to delete this post?</p>
            </div>
            <form action="{{ route("superAdmin.deleteAnnouncement") }}" method="post">
                @csrf
                <div class="modal-footer flex-nowrap p-0">
                    <input type="hidden" value="{{$announcement->announcement_id}}" name="announcement_id">
                    <button type="button" class="py-2 btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end text-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="py-2 btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 text-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
