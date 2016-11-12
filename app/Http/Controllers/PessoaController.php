<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Pessoa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PessoaController extends Controller
{
    const MODEL = "App\Models\Pessoa";

    use RESTActions;

    public function store(Request $request) {
        try {
            $input = $request->all();
            // Valida as entradas
            $validar = Validator::make($input,
                                        array_merge(Pessoa::$rules, Usuario::$rules),
                                        array_merge(Pessoa::$messages, Usuario::$messages));
            // Se falhar, retorna mensagens de erro
            if ($validar->fails())
                return response()->json($validar->errors(), $this->statusCodes['error']);

            // Inicia transação
            DB::beginTransaction();
            // salva pessoa
            $pessoa = Pessoa::create($input);
            // encripta a senha
            $input['password'] = bcrypt($input['password']);
            // salva o usuário
            $usuario = Usuario::create($input);
            // relaciona pessoa e usuário
            $pessoa->usuario()->save($usuario);
            // commit nas alterações
            DB::commit();

            $data =  Usuario::with(['pessoa'])->where('id', $usuario->id)->first();

            return response()->json($data, $this->statusCodes['created']);
        } catch (Exception $ex) {
            DB::rollback();
            return $this->respond('error', ['message' => $ex->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $pessoa = Pessoa::with('usuario')->find($id);
            if(is_null($pessoa)){
                return $this->respond('not_found');
            }
            return $this->respond('done', $pessoa);
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }


    public function index()
    {
        try {
            $p = Pessoa::with('usuario');
            return $this->respond('done', $p->get());
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            $validar = Validator::make($input, Pessoa::$rules, Pessoa::$messages);
            if ($validar->fails())
                return response()->json($validar->errors(), $this->statusCodes['error']);
            $model = Pessoa::find($id);
            if (is_null($model)) {
                return $this->respond('not_found');
            }
            $model->update($request->all());
            $data = Pessoa::with('usuario')->find($model->id);
            return $this->respond('done', $data);
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }
}
