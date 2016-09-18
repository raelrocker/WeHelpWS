<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model {

	protected $fillable = ['nome', 'cpf', 'email', 'foto', 'telefone', 'ranking', 'moderador', 'sexo', 'data_nascimento'];

	protected $dates = [];

	protected $primaryKey = "pessoa_id";

	public static $rules = [
		'nome' => 'required',
		'cpf' => 'required|max:11',
		'email' => 'required|email',
		'sexo' => 'required|max:1',
		'data_nascimento' => 'required'
	];
	public static $messages = [
		'cpf.required'    => 'Informe o CPF',
		'cpf.max'    => 'Informe no máximo 11 caracter para o cpf',
		'nome.required'    => 'Informe o nome',
		'email.required'    => 'Informe o E-mail',
		'email.email'    => 'E-mail inválido',
		'sexo.required'    => 'Informe o sexo',
		'sexo.max'    => 'Informe no máximo 1 caracter para o sexo',
		'data_nascimento.required'    => 'Informe a data de nascimento',
	];

	// Relacionamentos
	public function usuario()
	{
		return $this->hasOne('App\Models\Usuario');
	}

}
