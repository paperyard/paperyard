<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;

class sendNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $emailDatas;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($se_data)
    {
         $this->emailDatas = $se_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->emailDatas['subject'])->view('emails.send_notification');
    }
}
