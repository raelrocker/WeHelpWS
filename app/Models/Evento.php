<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'data_fim' => 'required',
        'usuario_id' => 'required',
        'categoria_id' => 'required',
        'cep' => 'required'
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
        'data_fim.required'    => 'Informe a data e hora do fim',
        'usuario_id.required'    => 'Informe o id do usuário',
        'categoria_id.required'    => 'Informe o id da categoria',
        'cep.required'    => 'Informe o CEP'
    ];

    public function categoria()
    {
        return $this->belongsTo('App\Models\Categoria', 'categoria_id');
    }

    public function usuario()
    {
        return $this->belongsTo('App\Models\Usuario', 'usuario_id');
    }

    public function requisitos()
    {
        return $this->hasMany('App\Models\Requisito');
    }

    public function participantes()
    {
        return $this->belongsToMany('\App\Models\Usuario')->withTimestamps();
    }

    public function getByPerimeter($lat, $lng, $perimeter)
    {
        $e = DB::select("SELECT
                                  id, (
                                    6371 * acos (
                                      cos ( radians(?) )
                                      * cos( radians( lat ) )
                                      * cos( radians( lng ) - radians(?) )
                                      + sin ( radians(?) )
                                      * sin( radians( lat ) )
                                    )
                                  ) AS distance
                                FROM eventos
                                GROUP BY id, distance
                                HAVING distance < ?
                                ORDER BY distance
                                LIMIT 0 , 20;", [$lat, $lng, $lat, $perimeter]);

        foreach ($e as $evento)
            $eventos[] = Evento::with(['categoria', 'requisitos'])->find($evento->id);
        return $eventos;
    }
}
