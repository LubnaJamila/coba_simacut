<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAccountRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $karyawan;

    /**
     * Create a new message instance.
     */
    public function __construct( $karyawan)
    {
        $this->karyawan = $karyawan;
    }

    public function build()
    {
        return $this->subject('Pengajuan Akun Baru Menunggu Persetujuan')
            ->view('emails.new_account_request');
    }
}