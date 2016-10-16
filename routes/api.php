<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Login
Route::post('login', 'DefaultController@authenticate');
Route::post('auth/refresh', 'DefaultController@refreshToken');
Route::post('pessoas', 'PessoaController@store');
Route::post('ongs', 'OngController@store');

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('pessoas', 'PessoaController', ['except' => ['store', 'destroy']]);
    Route::resource('ongs', 'OngController', ['except' => ['store', 'destroy']]);
    Route::resource('categorias', 'CategoriaController', ['except' => ['destroy']]);
    Route::resource('eventos', 'EventoController', ['except' => ['destroy']]);
    Route::resource('comentarios', 'ComentarioController', ['except' => ['destroy']]);
    Route::resource('requisitos', 'RequisitoController', ['except' => ['destroy']]);
    Route::post('adicionar_participante', 'EventoController@AdicionarParticipante');
});
