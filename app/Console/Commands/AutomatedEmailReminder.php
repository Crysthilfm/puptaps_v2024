<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Alumni;
use App\Models\ReminderHistory;
use App\Models\ReminderRecipients;
use Carbon\Carbon;
use Exception;

class AutomatedEmailReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:AutomatedEmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send automated email reminders every 6 months';

    /**
     * Execute the console command.
     *
     * @return int
     */

    // ============= Automate Email Reminder ==================
    public function handle()
    {
        $currentDate = now()->toDateTimeString();
        info('Automated Email Reminder ran at '.$currentDate);
        // Email Set up
        $alumni = Alumni::limit(200)->get();

        // Check if last reminder is 6 months ago
        $reminderHistory = ReminderHistory::orderBy('rh_id', 'DESC')->first();
        $currentDate = Carbon::parse(now()->format('Y-m-d'));
        $latestReminder = Carbon::parse($reminderHistory->date_sent);
        
        $MonthDifference = $latestReminder->diffInMonths($currentDate);

        // Send Emails
        // Changed only to sample
        // Change to only send and record no need to check 6 month point
        // Change cron to per 6 months
        
        if($MonthDifference >= 6){      
            // Get Current rh_id and date
            $date = now()->format('Y-m-d');
            $current_rh = ReminderHistory::where('date_sent', '=', $date)
                        ->value('rh_id');  

            // ========= START Send Email To Recipients =========
            
            foreach($alumni as $a){
                    // ========= START Record Tracer Reminder to History =========
                    // Records Email sending today, skip if there is already a recorded one for today
                    $checkifduplicateday = ReminderHistory::where('rh_id', '=', $current_rh)
                    ->value('date_sent');
                    if($date != $checkifduplicateday){
                        $saveReminder = ReminderHistory::insert([
                            'date_sent'=>$date,
                        ]);
                    }   
                    // Records Recipients
                    if($current_rh == null){
                        $current_rh = ReminderHistory::where('date_sent', '=', $date)
                                ->value('rh_id');
                    }
                    
                    $saveRecipients = ReminderRecipients::insert([
                        'recipientEmail'=>$a->email,
                        'rh_id'=> $current_rh,
                    ]);
                    
                // ========= END Record Tracer Reminder to History =========             
                // ========= START EMAIL SENDING ========== 
                $mailData['email'] = $a->email;
                $mailData['subject'] = "Tracer Reminder";
                try {
                    Mail::send('mail.email-reminder-tracer', $mailData, function($message) use($mailData) {
                        $message->to($mailData['email'])
                            ->subject($mailData['subject']);
                    });   
                    info('Email sent to: '.$a->last_name);
                } catch (Exception $e) {
                    info('Error: Sending Automated Email Failed for '.$a->email);
                }
            }
            // ========= END Send Email To Recipients =========
        } else {
            info('It has not been 6 months since the last email tracer reminder');
        }

        return Command::SUCCESS;
    }
}
