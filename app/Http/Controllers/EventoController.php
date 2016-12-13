<?php

namespace App\Http\Controllers;

use App\Mail\ParticipacaoEmail;
use App\Mail\CriadorEmail;
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
use Illuminate\Support\Facades\Mail;
use Mockery\CountValidator\Exception;
use Carbon\Carbon;

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
                $e = Evento::with(['usuario', 'categoria', 'requisitos', 'participantes'])
                    ->whereHas('participantes', function($q) use ($input)
                    {
                        $q->where('usuario_id', '=', $input['participante_id'])->orderBy('data_inicio');
                    });
            else
                $e = Evento::with(['usuario', 'categoria', 'requisitos', 'participantes']);
            if (isset($input['cidade']))
                $condicao['cidade'] = $input['cidade'];
            if (isset($input['rua']))
                $condicao['rua'] = $input['rua'];
            if (isset($input['usuario_id']))
                $condicao['usuario_id'] = $input['usuario_id'];
            $e->where($condicao)->orderBy('data_inicio');

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

            DB::beginTransaction();

            $evento = Evento::find($input['evento_id']);
            if (!$evento)
                return response()->json("Evento não encontrado", $this->statusCodes['error']);

            $usuario = Usuario::find($input['usuario_id']);
            if (!$usuario)
                return response()->json("Usuário não encontrado", $this->statusCodes['error']);

            try {
                $evento->participantes()->save($usuario);
            } catch (QueryException $ex) {
                $msg = $ex->getMessage();
                if (strpos($msg, 'Duplicate entry') === false) {
                    DB::rollback();
                    return $this->respond('error', ['message' => $msg]);
                }
            }
            $requisitosMarcados = [];
            $usuario->requisitos()->newPivotStatement()->where('evento_id',$evento->id)->where('usuario_id', $usuario->id)->delete();
            if (isset($input['requisitos'])) {

                for ($i = 0; $i < count($input['requisitos']); $i++) {
                    $requisito = Requisito::find($input['requisitos'][$i]['requisito_id']);
                    if (!$requisito)
                        continue;
                    try {
                        $requisito->usuariosRequisito()->save($usuario);
                        $requisitosMarcados[$i]['descricao'] = $requisito->descricao;
                        $requisitosMarcados[$i]['quant'] = $input['requisitos'][$i]['quant'];
                        $requisitosMarcados[$i]['un'] = $input['requisitos'][$i]['un'];
                    } catch (QueryException $ex) {
                        $msg = $ex->getMessage();
                        if (strpos($msg, 'Duplicate entry') === false) {
                            DB::rollback();
                            return $this->respond('error', ['message' => $msg]);
                        }
                    }
                    try {
                        $requisito->usuariosRequisito()->updateExistingPivot($usuario->id, ['quant' => $input['requisitos'][$i]['quant'], 'un' => $input['requisitos'][$i]['un'], 'evento_id' => $evento->id]);
                    } catch (Exception $ex) {}
                }
            }

            DB::commit();

            try {
                $when = Carbon::now()->addMinutes(2);
                $when2 = Carbon::now()->addMinutes(3);
                /*
                Mail::later(10, 'mails.participacaoEmail', ['evento' => $evento, 'participante' => $usuario, 'requisitosMarcados' => $requisitosMarcados], function($message) use ($usuario, $evento)
                {
                    $message->to($usuario->email)->subject('Evento: ' . $evento->nome);
                });
                Mail::later(20, 'mails.criadorEmail', ['evento' => $evento, 'participante' => $usuario, 'requisitosMarcados' => $requisitosMarcados, 'mensagem' => ""], function($message) use ($usuario, $evento)
                {
                    $message->to($usuario->email)->subject('Evento: ' . $evento->nome);
                });

                Mail::to($usuario->email)
                    ->send(new ParticipacaoEmail($evento, $usuario, $requisitosMarcados));
                Mail::to($usuario->email)
                    ->send(new CriadorEmail($evento, $usuario, $requisitosMarcados, ""));
                */
                //$this->EnviarEmail($usuario->email, 'Evento: ' . $evento->nome, $this->MontarEmailParticipar($evento, $usuario, $requisitosMarcados));
                //$this->EnviarEmail($evento->usuario->email, 'Evento: ' . $evento->nome, $this->MontarEmailCriador($evento, $usuario, $requisitosMarcados, ""));

            } catch (Exception $ex) {}

            return $this->respond('done', ['message' => 'ok']);

        /*
        } catch (QueryException $ex) {
            $msg = $ex->getMessage();
            if (strpos($msg, 'Duplicate entry') !== false)
                $msg = "Usuário já está participando deste evento";
            DB::rollback();
            return $this->respond('error', ['message' => $msg]);
        */
        } catch (Exception $ex) {
            DB::rollback();
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

            $usuario->requisitos()->detach();

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

    private function EnviarEmail($to, $subject, $message)
    {
        $mail = new \PHPMailer(true);
        try {

            $mail->isSMTP(); // tell to use smtp
            $mail->smtpConnect(
                array(
                    "ssl" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                        "allow_self_signed" => true
                    )
                )
            );
            $mail->CharSet = "utf-8"; // set charset to utf8
            $mail->SMTPAuth = true;  // use smpt auth
            $mail->SMTPSecure = "ssl"; // or ssl
            $mail->Host = "smtp.gmail.com"; //"smtp-mail.outlook.com";
            $mail->Port = 465; // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = "wehelpapplication@gmail.com";
            $mail->Password = "padremarcos";

            $mail->setFrom("wehelpapplication@gmail.com", "We Help APP");
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->IsHTML(true);
            $mail->addAddress($to, "");

            $mail->send();
        } catch (phpmailerException $e) {
            dd($e);
            return false;
        } catch (Exception $e) {
            dd($e);
            return false;
        }

        return true;
    }

    private function MontarEmailParticipar($evento, $participante, $requisitosMarcados)
    {
        $mensagem = "<h1>WE HELP APP</h1>
            <h2>Olá," .  ($participante->pessoa_id > 0 ? $participante->pessoa->nome : $participante->ong->nome)
            . "</h2><p>Você está participando do evento:</p><p>{$evento->nome}</p><p>Data: " . $evento->data_inicio->format('d/m/Y')
            . "</p><p>Endereço: {$evento->rua}, {$evento->numero} " . ($evento->complemento ? ", {$evento->complemento}" : "")
            . ", {$evento->cidade}, {$evento->uf}</p>"
            . "--------------"
            . "<p>Você se comprometeu com os seguintes requisitos</p><ul>";
        for ($i = 0; $i < count($requisitosMarcados); $i++)
            $mensagem .= "<li>{$requisitosMarcados[$i]['quant']} {$requisitosMarcados[$i]['un']} - {$requisitosMarcados[$i]['descricao']}</li>";
        $mensagem .= "</ul>";

        return $mensagem;
    }

    private function MontarEmailCriador($evento, $participante, $requisitosMarcados, $mensagemUsuario)
    {
        $mensagem = "<h1>WE HELP APP</h1><h2>Olá, " . ($evento->usuario->pessoa_id > 0 ? $evento->usuario->pessoa->nome : $evento->usuario->ong->nome)
        . "</h2><p>O usuário " . ($participante->pessoa_id > 0 ? $participante->pessoa->nome : $participante->ong->nome)
        . " está participando do evento <strong>{$evento->nome}</strong></p>"
        . "----------------"
        . "<p>O usuário se comprometeu com os seguintes requisitos</p><ul>";
        for ($i = 0; $i < count($requisitosMarcados); $i++)
            $mensagem .= "<li>{$requisitosMarcados[$i]['quant']} {$requisitosMarcados[$i]['un']} - {$requisitosMarcados[$i]['descricao']}</li>";

        $mensagem .= "</ul>";

        if ($mensagemUsuario)
            $mensagem .= "<p>Mensagem do usuário</p><p><strong>{$mensagemUsuario}</strong></p>";

        return $mensagem;
    }
}
