<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Usuario;
use App\Models\Requisito;
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

            if (isset($input['requisitos']))
            {
                for ($i = 0; $i < count($input['requisitos']); $i++) {
                    $input['requisitos'][$i]['evento_id'] = $evento->id;
                    $requisito = Requisito::create($input['requisitos'][$i]);
                    $evento->requisitos()->save($requisito);
                }
            }

            // commit nas alterações
            DB::commit();
            $data = Evento::with(['usuario', 'categoria', 'requisitos'])->find($evento->id);
            return response()->json($data, $this->statusCodes['created']);
        } catch (Exception $ex) {
            DB::rollback();
            return $this->respond('error', ['message' => $ex->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $evento  = Evento::with(['usuario', 'categoria', 'requisitos'])->find($id);
            if(is_null($evento)){
                return $this->respond('not_found');
            }
            return $this->respond('done', $evento);
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }

    public function index(Request $request)
    {
        try {
            $condicao = [];
            $input = $request->all();

            if (isset($input['participante_id']))
                $e = Evento::with(['usuario', 'categoria', 'requisitos'])
                    ->whereHas('participantes', function($q)
                    {
                        $q->where('usuario_id', '=', 11);
                    });
            else
                $e = Evento::with(['usuario', 'categoria', 'requisitos']);
            if (isset($input['cidade']))
                $condicao['cidade'] = $input['cidade'];
            if (isset($input['rua']))
                $condicao['rua'] = $input['rua'];
            if (isset($input['usuario_id']))
                $condicao['usuario_id'] = $input['usuario_id'];
            $e->where($condicao);

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
            $data = Evento::with(['usuario', 'categoria', 'requisitos'])->find($model->id);
            return $this->respond('done', $data);
        } catch (Exception $ex) {
            return $this->respond('erro', $ex->getMessage());
        }
    }

    public function AdicionarParticipante(Request $request) {
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
                return response()->json("Evento não encontrado", $this->statusCodes['error']);

            $usuario = Usuario::find($input['usuario_id']);
            if (!$usuario)
                return response()->json("Usuário não encontrado", $this->statusCodes['error']);

            $evento->participantes()->save($usuario);

            return $this->respond('done', ['message' => 'ok']);


        } catch (QueryException $ex) {
            $msg = $ex->getMessage();
            if (strpos($msg, 'Duplicate entry') !== false)
                $msg = "Usuário já está participando deste evento";
            return $this->respond('error', ['message' => $msg]);
        } catch (Exception $ex) {
            return $this->respond('error', ['message' => $ex->getMessage()]);
        }
    }

    public function RemoverParticipante(Request $request) {
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
                return response()->json("Evento não encontrado", $this->statusCodes['error']);

            $usuario = Usuario::find($input['usuario_id']);
            if (!$usuario)
                return response()->json("Usuário não encontrado", $this->statusCodes['error']);

            $evento->participantes()->detach($usuario->id);

            return $this->respond('done', ['message' => 'ok']);


        } catch (QueryException $ex) {
            $msg = $ex;
            return $this->respond('error', ['message' => $msg]);
        } catch (Exception $ex) {
            return $this->respond('error', ['message' => $ex->getMessage()]);
        }
    }

    public function EventosPorPerimetro(Request $request)
    {
        $input = $request->all();
        $evento = new Evento();
        $eventos = $evento->getByPerimeter($input['lat'], $input['lng'], $input['perimetro']);
        return $this->respond('done', $eventos);
    }
}
