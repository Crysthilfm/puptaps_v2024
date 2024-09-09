<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\ReminderHistory;
use App\Models\ReminderRecipients;
use App\Models\Alumni;

class ViewTracerHistory extends Component
{
    public $tracerHistory;
    public $tracerRecipients;
    private $currentRow;

    public function add(){
        $this->currentRow++;
    }


    public function render()
    {
        $this->tracerHistory = ReminderHistory::join('reminder_recipients', 'reminder_recipients.rh_id', '=', 'reminder_histories.rh_id')
        ->select('reminder_histories.rh_id as RecordID', 'reminder_histories.date_sent as DateSent')
        ->distinct('tbl_tracer_answers.alumni_id')
        ->get();
        
        return view('livewire.admin.view-tracer-history');
    }
}
