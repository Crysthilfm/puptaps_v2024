<div>
    <section class="mt-4 mt-sm-4 mt-md-4 mt-lg-5 mt-xl-5 mb-5">
        <div class="container-fluid box-content">
    
            <div class="row justify-content-center">
                <div class="col-11">
                    <div class="row g-3">
                        {{-- Table history --}}
                        
                        <div class="col-4 sub-container-box mx-3 justify-content-center">
                            <h3>Tracer Reminders Sent</h3>
                            
                            <div class="table-wrapper-scroll-y my-custom-scrollbar" style="height:330px;">
                                <table class="table tableDesign table-striped table-bordered table-sm" cellspacing="0" id="recipientTable">
                                    
                                    <thead class="tbl-head">
                                        <td>Reminder History ID</td>
                                        <td>Date Sent</td>
                                        {{-- <td>Total Recipients</td> --}}
                                    </thead>
                                    <tbody>
                                        @foreach ($tracerHistory as $a)
                                        <tr>
                                            <td value="{{$a->RecordID}}" id="{{$a->RecordID}}" onclick="setRhId(this)">{{$a->RecordID}}</td>
                                            <td>{{$a->DateSent}}</td>
                                            {{-- <td>{{$a->TotalRecipients}}</td> --}}
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
    
                        {{-- Content Other --}}
                        <div class="col-7 sub-container-box mx-3">
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <script>
            function setRhId(){
                var rows = document.getElementById("recipientTable").rows;
                var currentRow = 0;
                for(var i = 0, ceiling = rows.length; i < ceiling; i++) {
                        rows[i].onclick = function() {
                            // console.log(this.cells[0].innerHTML);
                            // @this.rh_id = parseInt(this.cells[0].innerHTML);
                            currentRow = parseInt(this.cells[0].innerHTML);
                            console.log(@this.rh_id);
                            
                        }
                    }
            }
        </script>
        
    </section>
</div>
