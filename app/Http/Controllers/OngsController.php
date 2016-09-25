<?php namespace App\Http\Controllers;

use App\Models\Ong;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exception;


class OngsController extends Controller {

    const MODEL = "App\Models\Ong";

    use RESTActions;

    public function add(Request $request) {
        try {
            $input = $request->all();
            $this->validate($request, Ong::$rules, Ong::$messages);
            $this->validate($request, Usuario::$rules, Usuario::$messages);
            DB::beginTransaction();
            $ong = Ong::create($input);
            $usuario = Usuario::create($input);
            $ong->usuario()->save($usuario);
            DB::commit();
            $data = Ong::with('usuario')->find($ong->id);
            return response()->json($data, $this->statusCodes['created']);
        } catch (Exception $ex) {
            return $this->respond('erro', ['message' => $ex->getMessage()]);
        }
    }

    public function get($id)
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

    public function all()
    {
        try {
            $ong = Ong::with('usuario');
            return $this->respond('done', $ong->get());
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }

    public function put(Request $request, $id)
    {
        try {
            $m = self::MODEL;
            $rules = $m::$rules;
            $rules['cnpj'] = $rules['cnpj'] . ',cnpj,' . $id;
            $this->validate($request, $rules, $m::$messages);
            $model = $m::find($id);
            if (is_null($model)) {
                return $this->respond('not_found');
            }
            $model->update($request->all());
            return $this->respond('done', $model);
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }



}