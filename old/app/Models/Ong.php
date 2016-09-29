<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ong extends Model {

	protected $fillable = ['nome', 'cnpj', 'foto', 'telefone', 'nacionalidade', 'uf', 'cidade', 'rua', 'numero',
						   'complemento', 'cep', 'bairro', 'ranking', 'responsavel_nome', 'responsavel_cpf'];

    protected $table = 'Ongs';

	protected $dates = [];

	public static $rules = [
		'nome' => 'required|max:250',
        'cnpj' => 'required|max:14|unique:ongs',
        'telefone' => 'required|max:12',
        'uf' => 'required|max:2',
        'cidade' => 'required|max:250',
        'rua' => 'required|max:250',
        'cep' => 'required',
        'bairro' => 'required',
        'responsavel_nome' => 'required|max:250',
        'responsavel_cpf' => 'required|max:11'
	];

    public static $messages = [
        'nome.required' => 'Informe o nome da ONG',
        'nome.max' => 'O nome da ONG pode ter no máximo 250 caracteres',
        'cnpj.required' => 'Informe o CNPJ da ONG',
        'cnpj.max' => 'O CNPJ pode ter no máximo 14 caracteres',
        'cnpj.unique' => 'O CNPJ já cadastrado',
        'telefone.required' => 'Informe o telefone da ONG',
        'telefone.max' => 'O telefone pode ter no máximo 12 caracteres',
        'uf.required' => 'Informe a UF da ONG',
        'uf.max' => 'A UF pode ter no máximo 2 caracteres',
        'cidade.required' => 'Informe a cidade da ONG',
        'cidade.max' => 'A cidade pode ter no máximo 250 caracteres',
        'rua.required' => 'Informe a rua da ONG',
        'rua.max' => 'A rua pode ter no máximo 250 caracteres',
        'cep.required' => 'Informe o CEP da ONG',
        'bairro.required' => 'Informe o bairro da ONG',
        'responsavel_nome.required' => 'Informe o nome do responsável pela ONG',
        'responsavel_nome.max' => 'O nome do responsável pela ONG pode ter no máximo 250 caracteres',
        'responsavel_cpf.required' => 'Informe o CPF do responsável pela ONG',
        'responsavel_cpf.max' => 'O CPF do responsável pela ONG pode ter no máximo 11 caracteres'
    ];

	// Relationships
    public function usuario()
    {
        return $this->hasOne('App\Models\Usuario');
    }
}
