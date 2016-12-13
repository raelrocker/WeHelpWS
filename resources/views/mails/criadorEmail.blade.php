<h1>WE HELP APP</h1>
<h2>Olá, @if ($evento->usuario->pessoa_id > 0) {{$evento->usuario->pessoa->nome}} @else {{$evento->usuario->ong->nome}} @endif</h2>
<p>O usuário @if ($participante->pessoa_id > 0) {{$participante->pessoa->nome}} @else {{$participante->ong->nome}} @endif
    está participando do evento <strong>{{ $evento->nome }}</strong></p>

@if ($requisitosMarcados)
    "--------------"
    <p>O usuário se comprometeu com os seguintes requisitos:</p>
    <ul>
        @for ($i = 0; $i < count($requisitosMarcados); $i++)
            <li>{{$requisitosMarcados[$i]['quant']}} {{$requisitosMarcados[$i]['un']}} - {{$requisitosMarcados[$i]['descricao']}}</li>
        @endfor
    </ul>
@endif

@if ($mensagem)
    ----------------
    <p>Mensagem do usuário</p>
    <p>
        <strong>{{$mensagem}}</strong>
    </p>
@endif