<div>
        <div class="container-fluid box-content">
            <h3>Recipients</h3>
            <button wire:click.prevent="updatesLastWeek">Last Week</button>
            
            <table class="table tableDesign table-striped table-bordered table-sm table-wrapper-scroll-y my-custom-scrollbar" style="height:400px;" cellspacing="0" id="tracerTable">
                    <thead class="tbl-head">
                        <td>Reminder ID</td>
                        <td>Recipient Email</td>
                        <td>Last updated at</td>
                    </thead>
                    <tbody>
                        @if($tracerRecipient != null)
                            @foreach ($tracerRecipient as $a)
                            <tr>
                                <td>{{$a->rh_id}}</td>
                                <td>{{$a->Email}}</td>
                                <td>{{$a->LastTracer}}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
            </table>
                        
        </div>
</div>
