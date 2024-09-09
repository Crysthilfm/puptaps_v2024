<?php

namespace App\Http\Livewire\Admin;

use App\Models\Alumni;
use App\Models\ReminderHistory;
use App\Models\ReminderRecipients;
use App\Mail\EmailTracerReminder;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert as SweetAlert;
use Exception;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Support\Facades\Log;

class TracerReminder extends Component
{
    public $recipient;

    public function sendmail() {
        // Get current User
        $user = auth()->user();
        // Get Current rh_id and date
        $date = now()->format('Y-m-d');
        // $date = '2023-10-01';
        $current_rh = ReminderHistory::where('date_sent', '=', $date)
                    ->value('rh_id');

        try{
        // Records Email sending today, skip if there is already a recorded one for today
        $checkifduplicateday = ReminderHistory::where('rh_id', '=', $current_rh)
        ->value('date_sent');
        if($date != $checkifduplicateday){
            $saveReminder = ReminderHistory::insert([
                'date_sent'=>$date,
            ]);
        }   

        // Gets todays rh_id
        if($current_rh == null){
            $current_rh = ReminderHistory::where('date_sent', '=', $date)
                    ->value('rh_id');
        }
        foreach($this->recipient as $a){
        // Sends the Email
            $mailData['email'] = $a;
            $mailData['subject'] = "Tracer Reminder";
                    
            Mail::send('mail.email-reminder-tracer', $mailData, function($message) use($mailData) {
                $message->to($mailData['email'])
                    ->subject($mailData['subject']);
                });
        // Records Recipients
            $saveRecipients = ReminderRecipients::insert([
                'recipientEmail'=>$a,
                'rh_id'=> $current_rh,
            ]);
        }
            // Resets the collection and send success alert
            unset($recipient);
            $this->dispatchBrowserEvent('success-alert');
            session()->flash('success','Reminder Emails sent successful'); 

            // Logging for audits
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Sent tracer emails to ".count($this->recipient)." total alumni");
        } catch(Exception $e){
            // Sends failure alert
            $this->dispatchBrowserEvent('error-alert');
            session()->flash('fail','Something went wrong with sending emails');
        }
    }


    public function render()
    {
        sleep(1);
        $alumni = Alumni::where('tbl_alumni.email', '!=', '')->get();
        
        return view('livewire.admin.tracer-reminder', ['alumni' => $alumni]);
    }
}