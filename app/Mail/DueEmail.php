<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DueEmail extends Mailable{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data){
        $this->data = $data;
    }

    public function build(){
        return $this->subject('Pengingat Jatuh Tempo Pajak Kendaraan & Kontrak Karyawan')->view('emails.due');
    }
}
