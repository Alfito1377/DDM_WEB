<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewReturnNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $returData;

    /**
     * Create a new message instance.
     */
    public function __construct($returData)
    {
        $this->returData = $returData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Notifikasi Sistem: Pengajuan Retur Benih Baru')
                    ->view('emails.retur-baru');
    }
}