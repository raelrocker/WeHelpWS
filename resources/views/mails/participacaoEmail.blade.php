<h1>WE HELP APP</h1>
<h2>Olá, @if ($participante->pessoa_id > 0) {{$participante->pessoa->nome}} @else {{$participante->ong->nome}} @endif</h2>
<p>Você está participando do evento:</p>
<p>{{ $evento->nome }}</p>
<p>Data: {{ $evento->data_inicio->format('d/m/Y') }}</p>
<p>Endereço: {{ $evento->rua }}, {{ $evento->numero }}
    @if ($evento->complemento)
        , {{$evento->complemento}}
    @endif
    , {{ $evento->cidade }}, {{$evento->uf}}
</p>

@if ($requisitosMarcados)
    "--------------"
    <p>Você se comprometeu com os seguintes requisitos:</p>
    <ul>
        @for ($i = 0; $i < count($requisitosMarcados); $i++)
            <li>{{$requisitosMarcados[$i]['quant']}} {{$requisitosMarcados[$i]['un']}} - {{$requisitosMarcados[$i]['descricao']}}</li>
        @endfor
    </ul>
@endif