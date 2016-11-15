<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Comentario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ComentarioController extends Controller
{
    const MODEL = "App\Models\Comentario";

    use RESTActions;

    public function index(Request $resquest)
    {
        try {
            $max_result = 10;
            $input = $resquest->all();
            $condicao = [];
            $retorno = [];
            $page = 1;
            if (isset($input['evento_id']))
                $condicao['evento_id'] = $input['evento_id'];

            if (isset($input['page']))
                $page = $input['page'];

            $comentarios = Comentario::where($condicao)
                ->skip(($page - 1) * $max_result)
                ->take($max_result)
                ->orderBy('created_at')
                ->get();
            $retorno['comentarios'] = $comentarios;
            $registros = Comentario::where($condicao)->count();
            $retorno['total_registros'] = $registros;
            if ($registros > ($page * $max_result)) {
                $retorno['proxima_pagina'] = env('APP_URL') . '/api/comentarios?page=' . ($page + 1);
                if (isset($input['evento_id']))
                    $retorno['proxima_pagina'] .= '&evento_id=' . $input['evento_id'];
            }
            return $this->respond('done', $retorno);
        } catch (Exception $ex) {
            return $this->respond('error', $ex->getMessage());
        }
    }
}
