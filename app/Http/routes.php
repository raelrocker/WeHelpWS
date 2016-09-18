<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$app->get('/', function () use ($app) {
    return "WE HELP ";
});

$app->get('pessoas', 'PessoasController@all');
$app->get('pessoas/{id}', 'PessoasController@get');
$app->post('pessoas', 'PessoasController@add');
$app->put('pessoas/{id}', 'PessoasController@put');
$app->delete('pessoas/{id}', 'PessoasController@remove');