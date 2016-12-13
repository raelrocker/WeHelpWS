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
    public $requisitosMarcados;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($evento, $participante, $requisitosMarcados)
    {
        $this->evento = $evento;
        $this->participante = $participante;
        $this->requisitosMarcados = $requisitosMarcados;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('wehelpapplication@outlook.com')
                    ->subject('Evento: ' . $this->evento->nome)
                    ->view('mails.participacaoEmail');
    }
}
