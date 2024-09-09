<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectCareerPost extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $reason)
    {
        $this->email = $email;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.reject-career-post');
    }
}
