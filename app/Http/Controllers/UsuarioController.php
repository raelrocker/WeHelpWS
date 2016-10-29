<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Usuario;
use Mockery\CountValidator\Exception;

class UsuarioController extends Controller
{

    const MODEL = "App\Models\Usuario";

    use RESTActions;

    public function get(Request $request)
    {
        try {

            $user = $request->user();
            return Usuario::with(['pessoa', 'ong'])->find($user->id);
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }
}
