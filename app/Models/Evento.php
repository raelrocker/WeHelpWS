<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Evento extends Model
{
    protected $fillable = ['nome', 'categoria_id', 'usuario_id', 'pais', 'uf', 'cidade', 'rua', 'numero', 'complemento', 'cep',
                           'bairro', 'lat', 'lng', 'descricao', 'data_inicio', 'data_fim', 'ranking', 'status', 'certificado'];

    protected $dates = ['data_inicio'];

    public static $rules = [
        'nome' => 'required',
        'pais' => 'required',
        'uf' => 'required',
        'cidade' => 'required',
        'rua' => 'required',
        'lat' => 'required',
        'lng' => 'required',
        'descricao' => 'required',
        'data_inicio' => 'required',
        'usuario_id' => 'required',
        'categoria_id' => 'required'
    ];
    public static $messages = [
        'nome.required'    => 'Informe o nome',
        'categoria_id.required'    => 'Informe a categoria',
        'usuario_id.required'    => 'Informe o usuario',
        'pais.required'    => 'Informe o país',
        'uf.required'    => 'Informe o estado',
        'cidade.required'    => 'Informe a cidade',
        'rua.required'    => 'Informe a rua',
        'lat.required'    => 'Informe a latitude',
        'lng.required'    => 'Informe a longitude',
        'descricao.required'    => 'Informe a descrição',
        'data_inicio.required'    => 'Informe a data e hora do início',
        'usuario_id.required'    => 'Informe o id do usuário'
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
        return $this->hasMany('App\Models\Requisito')->with(['usuariosRequisito']);
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
                                WHERE DATE_ADD(data_inicio, INTERVAL 1 HOUR) >= NOW()
                                GROUP BY id, distance
                                HAVING distance < ?
                                ORDER BY distance
                                LIMIT 0 , 20;", [$lat, $lng, $lat, $perimeter]);

        $i = 0;
        $eventos = array();
        foreach ($e as $evento) {
            $evento = Evento::with(['categoria', 'requisitos', 'participantes'])->find($evento->id);
            $evento['numero_participantes'] = $evento->participantes()->count();
            $eventos[$i] = $evento;
            $i++;
        }
        return $eventos;
    }

}
