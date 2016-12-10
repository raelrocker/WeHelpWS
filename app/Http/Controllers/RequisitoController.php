<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Requisito;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RequisitoController extends Controller
{
    const MODEL = "App\Models\Requisito";
    use RESTActions;

    public function RequisitoUsuario(Request $request) {
        try {
            $input = $request->all();

            $rules = [
                'usuario_id' => 'required',
                'requisito_id' => 'required'
            ];
            $messages = [
                'usuario_id.required'    => 'Informe o Id do usuário',
                'requisito_id.required'    => 'Informe o Id do requisito',
            ];

            $validar = Validator::make($input, $rules, $messages);
            // Se falhar, retorna mensagens de erro
            if ($validar->fails())
                return response()->json($validar->errors(), $this->statusCodes['error']);

            $requisito = Requisito::find($input['requisito_id']);
            if (!$requisito)
                return response()->json("Requisito não encontrado", $this->statusCodes['error']);

            $usuario = Usuario::find($input['usuario_id']);
            if (!$usuario)
                return response()->json("Usuário não encontrado", $this->statusCodes['error']);

            $requisito->usuarios()->save($usuario);
            $requisito->usuarios()->updateExistingPivot($usuario->id, ['quant' => $input['quant'], 'un' => $input['un']]);
            return $this->respond('done');


        } catch (Exception $ex) {
            return $this->respond('error', ['message' => $ex->getMessage()]);
        }
    }
}
