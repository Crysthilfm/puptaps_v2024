<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Alumni;
use DB;

class Notification extends Component
{
    public function render()
    {
        // Get number of updated tracer the last 6 days
        $tracerRecipient = Alumni::select('tbl_alumni.middle_name as Middle', 'tbl_alumni.first_name as Given','tbl_alumni.last_name as Surname','tbl_alumni.email as Email', 'tbl_alumni.tracer_updated_at as LastTracer', DB::raw('DATEDIFF("'.date('Y-m-d').'", tbl_alumni.tracer_updated_at) as dateDiff'))
        ->whereRaw('DATEDIFF("'.date('Y-m-d').'", tbl_alumni.tracer_updated_at) < 6')
        ->get();

        $totalTracerThisWeek = count($tracerRecipient);
        
        return view('livewire.admin.notification', compact(['totalTracerThisWeek']));
    }
}
