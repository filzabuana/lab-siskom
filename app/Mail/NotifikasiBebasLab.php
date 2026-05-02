<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiBebasLab extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;
    public $status;
    public $catatan;

    public function __construct($pengajuan, $status, $catatan = null)
    {
        $this->pengajuan = $pengajuan;
        $this->status = $status;
        $this->catatan = $catatan;
    }

    public function build()
    {
        $subjek = $this->status == 'disetujui' ? 'Selamat! Pengajuan Bebas Lab Disetujui' : 'Update: Pengajuan Bebas Lab Ditolak';
        
        return $this->subject($subjek)
                    ->view('emails.bebas-lab-notification');
    }
}