<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable = ['categoria_id', 'usuario_id', 'pais', 'uf', 'cidade', 'rua', 'numero', 'complemento', 'cep',
                           'bairro', 'lat', 'lng', 'descricao', 'data_inicio', 'data_fim', 'ranking', 'status', 'certificado'];

    public static $rules = [
        'pais' => 'required',
        'uf' => 'required',
        'cidade' => 'required',
        'rua' => 'required',
        'bairro' => 'required',
        'lat' => 'required',
        'lng' => 'required',
        'descricao' => 'required',
        'data_inicio' => 'required',
        'data_fim' => 'required'
    ];
    public static $messages = [
        'categoria_id.required'    => 'Informe a categoria',
        'usuario_id.required'    => 'Informe o usuario',
        'pais.required'    => 'Informe o país',
        'uf.required'    => 'Informe o estado',
        'cidade.required'    => 'Informe a cidade',
        'rua.required'    => 'Informe a rua',
        'bairro.required'    => 'Informe o bairro',
        'lat.required'    => 'Informe a latitude',
        'lng.required'    => 'Informe a longitude',
        'descricao.required'    => 'Informe a descrição',
        'data_inicio.required'    => 'Informe a data e hora do início',
        'data_fim.required'    => 'Informe a data e hora do fim'
    ];

    public function categoria()
    {
        return $this->belongsTo('App\Models\Categoria', 'categoria_id');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Models\Usuario', 'usuario_id');
    }
}
