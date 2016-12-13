<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CriadorEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $evento;
    public $participante;
    public $mensagem;
    public $requisitosMarcados;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($evento, $participante, $requisitosMarcados, $mensagem)
    {
        $this->evento = $evento;
        $this->participante = $participante;
        $this->mensagem = $mensagem;
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
            ->view('mails.criadorEmail');
    }
}
