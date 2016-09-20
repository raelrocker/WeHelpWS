<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model {

	protected $fillable = ['nome', 'cpf', 'foto', 'telefone', 'ranking', 'moderador', 'sexo', 'data_nascimento'];

	protected $dates = [];

	protected $primaryKey = "pessoa_id";

	public static $rules = [
		'nome' => 'required',
		'cpf' => 'required|max:11',
		'sexo' => 'required|max:1',
		'data_nascimento' => 'required'
	];
	public static $messages = [
		'cpf.required'    => 'Informe o CPF',
		'cpf.max'    => 'Informe no máximo 11 caracter para o cpf',
		'nome.required'    => 'Informe o nome',
		'sexo.required'    => 'Informe o sexo',
		'sexo.max'    => 'Informe no máximo 1 caracter para o sexo',
		'data_nascimento.required'    => 'Informe a data de nascimento',
	];

	// Relacionamentos
	public function usuario()
	{
		return $this->hasOne('App\Models\Usuario', 'pessoa_id', 'pessoa_id');
	}

}
