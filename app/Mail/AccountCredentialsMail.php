<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

class AccountCredentialsMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public string $name;
    public string $email;
    public string $tempPassword;
    public string $subjectLine;

    public function __construct($name, $email, $tempPassword, $subjectLine)
    {
        $this->name = $name;
        $this->email = $email;
        $this->tempPassword = $tempPassword;
        $this->subjectLine = $subjectLine;

        $this->delay(now()->addSeconds(2));
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.account_credentials_plain');
    }
    
}