<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisito extends Model
{
    protected $fillable = ['evento_id', 'descricao'];

    public static $rules = [
        'evento_id' => 'required',
        'descricao' => 'required'

    ];
    public static $messages = [
        'evento_id.required'    => 'Informe o id do evento',
        'descricao.required'    => 'Informe a descricao'
    ];

    // Relacionamentos
    public function evento()
    {
        return $this->belongsTo('App\Models\Evento', 'evento_id');
    }
}
