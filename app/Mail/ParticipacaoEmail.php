<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ParticipacaoEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $evento;
    public $participante;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($evento, $participante)
    {
        $this->evento = $evento;
        $this->participante = $participante;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('wehelpapplication@gmail.com')
                    ->subject('Evento: ' . $this->evento->nome)
                    ->view('mails.participacaoEmail');
    }
}
