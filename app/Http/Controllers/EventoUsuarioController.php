<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Evento;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventoUsuarioController extends Controller
{
    const MODEL = "App\Models\Evento";

    use RESTActions;

    public function store(Request $request) {
        try {
            $input = $request->all();

            $rules = [
                'usuario_id' => 'required',
                'evento_id' => 'required'
            ];
            $messages = [
                'usuario_id.required'    => 'Informe o Id do usuário',
                'evento_id.required'    => 'Informe o Id do evento',
            ];

            $validar = Validator::make($input, $rules, $messages);
            // Se falhar, retorna mensagens de erro
            if ($validar->fails())
                return response()->json($validar->errors(), $this->statusCodes['error']);

            $evento = Evento::find($input['evento_id']);
            if (!$evento)
                return response()->json("Evento {$input['evento_id']} não encontrado", $this->statusCodes['error']);

            $usuario = Usuario::find($input['usuario_id']);
            if (!$usuario)
                return response()->json("Usuário {$input['usuario_id']} não encontrado", $this->statusCodes['error']);




        } catch (Exception $ex) {
            return $this->respond('error', ['message' => $ex->getMessage()]);
        }
    }
}
