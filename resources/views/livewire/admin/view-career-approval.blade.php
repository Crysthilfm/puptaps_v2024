{{-- View --}}
@if (($career->job_ad_image) == null)
    <div class="modal fade" id="viewCareer{{ $career->career_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View Career</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <p><b>Job Name:</b> {{ $career->job_name }}</p>
                    <p><b>Copany Name:</b> {{ $career->company }}</p>
                    <p><b>Salary:</b> {{ $career->salary }}</p>
                    <div hidden>{{ $text = $career->description }}</div>
                    <p><b>Description:</b><div class="fs-7 mb-1" id="seeJobDetails{{ $career->career_id }}" style="white-space: pre-wrap;">@php echo $text @endphp</div></p>
                    <p><b>Category:</b> {{ $career->category }}</p>
                    <p><b>Email:</b> {{ $career->email }}</p>
                    <p><b>Number:</b> {{ $career->number }}</p>
                    @foreach ($users as $user)
                        @if (($user->alumni_id) == ($career->alumni_id))
                            <p><b>Posted by:</b> {{ $user->first_name }} {{ $user->last_name }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@else
    <div class="modal fade" id="viewCareer{{ $career->career_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View Career</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ asset('Uploads/Career/' . $career->job_ad_image) }}" alt="" style="width: 100%; height: 50%;">
                    @foreach ($users as $user)
                        @if (($user->alumni_id) == ($career->alumni_id))
                            <p class="mt-3"><b>Posted by:</b> {{ $user->first_name }} {{ $user->last_name }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

