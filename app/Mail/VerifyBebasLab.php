<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyBebasLab extends Mailable
{
    use Queueable, SerializesModels;

    public $url;
    public $pengajuan;

    public function __construct($url, $pengajuan)
    {
        $this->url = $url;
        $this->pengajuan = $pengajuan;
    }

    public function build()
    {
        return $this->subject('Verifikasi Email Bebas Lab - Lab SISKOM')
                    ->markdown('emails.verify-bebas-lab');
    }
}