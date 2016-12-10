<h1>WE HELP APP</h1>
<h2>Olá, @if ($evento->usuario->pessoa_id > 0) {{$evento->usuario->pessoa->nome}} @else {{$evento->usuario->ong->nome}} @endif</h2>
<p>O usuário @if ($participante->pessoa_id > 0) {{$participante->pessoa->nome}} @else {{$participante->ong->nome}} @endif
    está participando do evento <strong>{{ $evento->nome }}</strong></p>
----------------
<p>Mensagem do usuário</p>
<p>
    <strong>{{$mensagem}}</strong>
</p>