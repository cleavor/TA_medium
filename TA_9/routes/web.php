<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->group(['prefix' => '/'], function () use ($router) {
    $router->post('auth/register', ['uses' => 'AuthController@register']);
    $router->post('auth/login', ['uses' => 'AuthController@login']);
    $router->get('prodi', ['uses' => 'ProdisController@getAll']);
    $router->get('mahasiswa', ['uses' => 'MahasiswasController@getAll']);

    $router->group(['middleware' => 'jwt'], function () use ($router) {
        $router->get('mahasiswa/profile', ['uses' => 'MahasiswasController@getWithToken']);
        $router->delete('mahasiswa/{nim}', ['uses' => 'MahasiswasController@deleteByNim']);
    });
});
