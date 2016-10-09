<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'pessoa_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
        'password'
    ];

    public static $rules = [
        'email' => 'required|email|unique:usuarios',
        'password' => 'required'
    ];

    public static $messages = [
        'email.required' => 'Informe o email',
        'email.email'    => 'E-mail inválido',
        'email.unique'    => 'E-mail já cadastrado',
        'password.required' => 'Informe a senha'
    ];

    // Relationships
    public function pessoa()
    {
        return $this->belongsTo('App\Models\Pessoa');
    }

    public function ong()
    {
        return $this->belongsTo('App\Models\Ong');
    }

    public function eventos()
    {
        return $this->hasMany('App\Models\Evento');
    }




}
