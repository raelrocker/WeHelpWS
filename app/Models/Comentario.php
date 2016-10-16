<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = ['usuario_id', 'evento_id', 'comentario'];

    public static $rules = [
        'usuario_id' => 'required',
        'evento_id' => 'required',
        'comentario' => 'required'

    ];
    public static $messages = [
        'usuario_id.required'    => 'Informe o id do usuário',
        'evento_id.required'    => 'Informe o id do evento',
        'comentario.required'    => 'Informe o comentário'
    ];

    // Relacionamentos
    public function evento()
    {
        return $this->belongsTo('App\Models\Evento', 'evento_id');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Models\Usuario', 'usuario_id');
    }
}
