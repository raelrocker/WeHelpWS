<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model {

	protected $fillable = ['ong_id', 'pessoa_id', 'email', 'senha'];

    protected $primaryKey = "email";

    public $incrementing = false;

	protected $hidden = ['senha'];

	protected $dates = [];

	public static $rules = [
		'email' => 'required|email',
		'senha' => 'required'
	];

    public static $messages = [
        'email.required' => 'Informe o email',
        'email.email'    => 'E-mail inválido',
        'senha.required' => 'Informe a senha'
    ];

	// Relationships
	public function pessoa()
	{
	    return $this->belongsTo('App\Models\Pessoa');
	}

	// Relationships
	public function ong()
	{
		return $this->belongsTo('App\Models\Ong');
	}

}
