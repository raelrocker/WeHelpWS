<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Ong;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Usuario;

class OngController extends Controller
{

    const MODEL = "App\Models\Ong";

    use RESTActions;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $o = Ong::with('usuario');
            return $this->respond('done', $o->get());
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            // Valida as entradas
            $validar = Validator::make($input,
                array_merge(Ong::$rules, Usuario::$rules),
                array_merge(Ong::$messages, Usuario::$messages));
            // Se falhar, retorna mensagens de erro
            if ($validar->fails())
                return response()->json($validar->errors(), $this->statusCodes['error']);

            // Inicia transação
            DB::beginTransaction();
            // salva pessoa
            $ong = Ong::create($input);
            // encripta a senha
            $input['password'] = bcrypt($input['password']);
            // salva o usuário
            $usuario = Usuario::create($input);
            // relaciona pessoa e usuário
            $ong->usuario()->save($usuario);
            // commit nas alterações
            DB::commit();

            $data = Ong::with('usuario')->find($ong->id);
            return response()->json($data, $this->statusCodes['created']);
        } catch (Exception $ex) {
            return $this->respond('error', ['message' => $ex->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $ong = Ong::with('usuario')->find($id);
            if(is_null($ong)){
                return $this->respond('not_found');
            }
            return $this->respond('done', $ong);
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();
            $rules = Ong::$rules;
            if (isset($input['cnpj'])) {
                $rules['cnpj'] = $rules['cnpj'] . ',cnpj,' . $id;
            }
            $validar = Validator::make($input, $rules, Ong::$messages);
            if ($validar->fails())
                return response()->json($validar->errors(), $this->statusCodes['error']);
            $model = Ong::find($id);
            if (is_null($model)) {
                return $this->respond('not_found');
            }
            $model->update($request->all());
            $data = Ong::with('usuario')->find($model->id);
            return $this->respond('done', $data);
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
