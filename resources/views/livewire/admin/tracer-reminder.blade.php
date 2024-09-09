<div>
    {{-- Loading a Request --}}
    <div class="alert alert-secondary" style="width:100%;height:100%;position:absolute;left:0;top:0;background-color: rgba(0, 0, 0, 0.6);margin:auto;" wire:loading>
        <div style="display:flex;justify-content:center;align-items:center;width:100%;height:100%;">
            <div style="width:200px;height:200px;background-color:rgb(100, 27, 27); padding:60px;border-radius:10px;">
                <h1 class="loading-text"><i class="fas fa-spinner fa-spin" style="font-size: 80px"></i></h1>
            </div>
        </div>
    </div> 

    {{-- Modal Help --}}
    <div class="modal fade" id="helpTracerReminder" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h6 class="fw-bold mt-2">Tracer Email Reminder Sending <i class="fa-solid fa-circle-info text-primary fs-5 ms-1"></i></h6>
                    <hr class="mt-3 mb-3">
                    <p class="fs-7 pb-0 px-0 mb-2" style="text-align: justify;
                    text-justify: inter-word;">
                    <ol>
                        <li><span class="fw-bold">Filter the recipient by either search their name, course, or batch</span></li>
                        <li><span class="fw-bold">Select the recipients by clicking the checkbox at the top-left to select 
                            all the currently displayed recipients or individually by clicking the checkbox on the left</span><br>
                            Recommended show entries is 50, anymore and it may take too long to send emails.
                        </li>
                        
                        <li><span class="fw-bold">Confirm the selection and hit ok</span></li>
                        <li><span class="fw-bold">Wait for the loading, and it should display 'Email Sent successfully'</span></li>
                    </ol>
                    <br><br>
                    <u>!! Reminder !! - Our gmail account can only send 500 emails per day</u>
                    </p>
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-secondary fs-7" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- End Help --}}


    <div class="row container sub-container-box mb-2 mt-4 px-3" style="max-width: 2000px; min-width: 100%;">
        
        <div class="d-flex justify-content-between my-4">
            <div><h3>Recipients</h3></div>
            <div>
                <button type="button" class="btn btn-primary helpTracerReminder">
                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                </button>
                <a class="btn btn-warning fs-7" href="{{ route('admin.view-tracer-history') }}">View Tracer History</a>
            </div>
        </div>
        {{-- =========================================================================== aa =========================================================================== --}}

        {{-- <table class="table table-striped align-middle table-hover">
            <thead class="tbl-head">
                <tr>
                    <td name="checkboxColumn" data-orderable="false"><input id="selectAll" type="checkbox"></td>
                    <td name="studNum">Student No.</td>
                    <td name="name">Name</td>
                    <td name="course">Course</td>
                    <td name="email">Email</td>
                    <td name="contact">Contact No.</td>
                    <td name="batch">Batch</td>
                    <td name="lastUpdated">Last Updated</td>
                </tr>
            </thead>
            <tbody>
                @if(!empty($alumni))
                @foreach ($alumni as $a)
                <tr wire:key>
                    <td wire:key><input class="checkbox_ids" type="checkbox" value="{{ $a->email }}"></td>
                    <th wire:key>{{ $a->stud_number }}</th>
                    <td wire:key><b>{{ strtoupper($a->last_name) }}</b> {{ $a->first_name }} {{ $a->middle_name }}</td>
                    <td wire:key>{{ $a->course_id }}</td> 
                    <td wire:key>{{ $a->email }}</td> 
                    <td wire:key>{{ $a->number }}</td>
                    <td wire:key>{{ $a->batch }}</td> 
                    <td wire:key>{{ $a->tracer_updated_at }}</td> 
                </tr wire:key>
                @endforeach
                @endif
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-5">
            {!! $alumni->links() !!}
        </div> --}}

        {{-- =========================================================================== aa =========================================================================== --}}

        <div wire:ignore class="container">
            <table class="table tableDesign table-striped table-sm table-wrapper-scroll-y my-custom-scrollbar" style="min-height:600px; margin:auto; width:100%;" cellspacing="0" id="tracerTable">
                <thead class="tbl-head">
                    <td name="checkboxColumn" data-orderable="false"><input id="selectAll" type="checkbox"></td>
                    <td style="min-width: 130px !important;" name="studNum">Student No.</td>
                    <td style="min-width: 320px !important;"name="name">Name</td>
                    <td style="min-width:80px; !important;" name="course">Course</td>
                    <td name="batch">Batch</td>
                    <td style="min-width:285px; !important;" name="email">Email</td>
                    <td style="min-width: 125px !important;" name="contact" data-orderable="false">Contact No.</td>
                    <td name="lastUpdated">Updated</td>
                </thead>
                <tbody>
            @if(!empty($alumni))
            @foreach ($alumni as $a)
            <tr wire:key>
                <td wire:key><input class="checkbox_ids" type="checkbox" value="{{ $a->email }}"></td>
                <th wire:key>{{ $a->stud_number }}</th>
                <td wire:key><b>{{ strtoupper($a->last_name) }}</b>, {{ $a->first_name }} {{ $a->middle_name }}</td>
                <td wire:key>{{ $a->course_id }}</td> 
                <td wire:key>{{ $a->batch }}</td> 
                <td wire:key>{{ $a->email }}</td> 
                <td wire:key>{{ $a->number }}</td>
                <td wire:key>{{ $a->tracer_updated_at }}</td> 
            </tr wire:key>
            @endforeach
            @endif
                </tbody>
            </table>
        </div>
        <div>
            <button class="btn btn-success btn-sm" onclick="getEmail()"> Confirm Recipients</button>
            @if($recipient!=null)
                <button class="btn btn-success btn-sm" wire:click='sendmail' style="float:right;"> Send Email to all recipients</button>
            @endif
        </div>
        
        
        <script>
            function getEmail() {
                var ctrEmail = 0;
                let checkboxs = document.getElementsByClassName('checkbox_ids');
                var emails = new Array();
                
                for(let i = 0; i < checkboxs.length ; i++) { 
                    if(checkboxs[i].checked) { 
                        if(checkboxs[i].value != '') {
                            emails[ctrEmail] = checkboxs[i].value;
                            ctrEmail++;
                        }
                    }
                }
                if(emails[0]==null) { alert('No recipients selected');}
                else { @this.recipient = emails;
                    alert("Recipients: " + emails);}      
            }
        </script>
    </div>
</div>
