<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use Illuminate\Foundation\Auth\User;
use App\Http\Requests;
use App\Models\Pessoa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventoController extends Controller
{
    const MODEL = "App\Models\Evento";

    use RESTActions;

    public function store(Request $request) {
        try {
            $input = $request->all();
            // Valida as entradas
            $validar = Validator::make($input, Evento::$rules, Evento::$messages);
            // Se falhar, retorna mensagens de erro
            if ($validar->fails())
                return response()->json($validar->errors(), $this->statusCodes['error']);

            // Inicia transação
            DB::beginTransaction();
            // salva pessoa
            $evento = Evento::create($input);
            // commit nas alterações
            DB::commit();
            $data = Evento::with(['usuario', 'categoria'])->find($evento->id);
            return response()->json($data, $this->statusCodes['created']);
        } catch (Exception $ex) {
            DB::rollback();
            return $this->respond('error', ['message' => $ex->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $evento  = Evento::with(['usuario', 'categoria'])->find($id);
            if(is_null($evento)){
                return $this->respond('not_found');
            }
            return $this->respond('done', $evento);
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }


    public function index()
    {
        try {
            $e = Evento::with(['usuario', 'categoria']);
            return $this->respond('done', $e->get());
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            $validar = Validator::make($input, Evento::$rules, Evento::$messages);
            if ($validar->fails())
                return response()->json($validar->errors(), $this->statusCodes['error']);
            $model = Evento::find($id);
            if (is_null($model)) {
                return $this->respond('not_found');
            }
            $model->update($request->all());
            $data = Evento::with(['usuario', 'categoria'])->find($model->id);
            return $this->respond('done', $data);
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }
}
