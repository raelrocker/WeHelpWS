<?php namespace App\Http\Controllers;

use App\Models\Pessoa;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exception;


class PessoasController extends Controller {

    const MODEL = "App\Models\Pessoa";

    use RESTActions;

    public function add(Request $request) {
        try {
            $input = $request->all();
            $this->validate($request, Pessoa::$rules, Pessoa::$messages);
            $this->validate($request, Usuario::$rules, Usuario::$messages);
            DB::beginTransaction();
            $pessoa = Pessoa::create($input);
            $usuario = Usuario::create($input);
            $pessoa->usuario()->save($usuario);
            DB::commit();
            //$data = ['pessoa' => $pessoa->toArray(), 'usuario' => $pessoa->usuario->toArray()];
            $data = Pessoa::with('usuario')->find($pessoa->pessoa_id);
            return response()->json($data, $this->statusCodes['created']);
        } catch (Exception $ex) {
            return $this->respond('erro', ['message' => $ex->getMessage()]);
        }
    }

    public function get($id)
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

    public function all()
    {
        try {
            $p = Pessoa::with('usuario');
            return $this->respond('done', $p->get());
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }



}