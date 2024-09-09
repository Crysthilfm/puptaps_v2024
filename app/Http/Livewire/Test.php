<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Alumni;
use App\Models\ReminderHistory;
use App\Models\ReminderRecipients;
use Illuminate\Support\Carbon;

class Test extends Component
{
    public $tracerRecipient;
    private $tracerRecipient1;

    public function updatesLastWeek(){
        $this->tracerRecipient1 = ReminderHistory::join('reminder_recipients','reminder_recipients.rh_id', '=', 'reminder_histories.rh_id')
        ->leftjoin('tbl_alumni', 'tbl_alumni.email', '=', 'reminder_recipients.recipientEmail')
        ->select('reminder_histories.rh_id as rh_id','tbl_alumni.last_name as Surname','reminder_recipients.recipientEmail as Email', 'tbl_alumni.tracer_updated_at as LastTracer')
        ->where('LastTracer', '>', Carbon::now()->subDays(6))
        ->get();
        $this->tracerRecipient = $this->tracerRecipient1;
    }

    public function render()
    {
        $this->tracerRecipient1 = ReminderHistory::join('reminder_recipients','reminder_recipients.rh_id', '=', 'reminder_histories.rh_id')
        ->leftjoin('tbl_alumni', 'tbl_alumni.email', '=', 'reminder_recipients.recipientEmail')
        ->select('reminder_histories.rh_id as rh_id','tbl_alumni.last_name as Surname','reminder_recipients.recipientEmail as Email', 'tbl_alumni.tracer_updated_at as LastTracer')
        ->get();
        $this->tracerRecipient = $this->tracerRecipient1;
        // dd($tracerRecipient);

        return view('livewire.test');
    }
}
