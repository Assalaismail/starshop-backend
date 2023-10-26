<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $emailContent = "New Contact Form Submission\n\n";
        $emailContent .= "Name: " . $this->data['name'] . "\n";
        $emailContent .= "Email: " . $this->data['email'] . "\n";
        $emailContent .= "Message: " . $this->data['message'];

        return $this->subject('New Contact Form Submission')
            ->text([], compact('emailContent'));
    }
}
