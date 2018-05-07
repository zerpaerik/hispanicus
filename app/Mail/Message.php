<?php

namespace hispanicus\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Message extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $msg;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($xemail, $xmsg)
    {
        $this->email = $xemail;
        $this->msg = $xmsg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.message')
        ->with(["user" => $this->email, "msg" => $this->msg]);
    }
}
