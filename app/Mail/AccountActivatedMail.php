<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountActivatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $user;
    public $passwordTemporary;
    public $changePasswordUrl;
    
    public function __construct($user, $passwordTemporary, $changePasswordUrl)
    {
        $this->user = $user;
        $this->passwordTemporary = $passwordTemporary;
        $this->changePasswordUrl = $changePasswordUrl;
    }

    public function build()
    {
        return $this->subject('Akun Anda Telah Aktif')
                    ->view('emails.account_activated');
    }
}