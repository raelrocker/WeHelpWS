<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisito extends Model
{
    protected $fillable = ['evento_id', 'descricao', 'quant', 'un'];

    public static $rules = [
        'evento_id' => 'required',
        'descricao' => 'required',
        'quant' => 'required'
    ];

    public static $messages = [
        'evento_id.required'    => 'Informe o id do evento',
        'descricao.required'    => 'Informe a descricao',
        'quant.required'        => 'Informe a quantidade'
    ];

    // Relacionamentos
    public function evento()
    {
        return $this->belongsTo('App\Models\Evento', 'evento_id');
    }

    public function usuariosRequisito()
    {
        return $this->belongsToMany('\App\Models\Usuario')->withTimestamps()->withPivot('quant as quant', 'un as un');
    }


}
