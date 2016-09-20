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

$app->get('api/pessoas', 'PessoasController@all');
$app->get('api/pessoas/{id}', 'PessoasController@get');
$app->post('api/pessoas', 'PessoasController@add');
$app->put('api/pessoas/{id}', 'PessoasController@put');
//$app->delete('api/pessoas/{id}', 'PessoasController@remove');