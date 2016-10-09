<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
     protected $fillable = ['descricao'];

    protected $dates = [];

    public static $rules = [
        'descricao' => 'required',
        
    ];
    public static $messages = [
        'descricao.required'    => 'Descricao inválida',
       
    ];

    // Relacionamentos
    public function categoria()
    {
        return $this->belongsTo('App\Models\Evento', 'categoria_id');
    }
    

}
