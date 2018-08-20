<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class reminder extends Mailable
{
    use Queueable, SerializesModels;


    public $rmDatas;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($rm_datas)
    {
         $this->rmDatas = $rm_datas;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->rmDatas['reminder_title'])->view('emails.send_reminder');
    }
}
