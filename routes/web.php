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
use App\Http\Controllers\api\v1\TransactaionsController;


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
  $router->get('transactaions',  ['uses' => 'api\v1\TransactaionsController@list']);
  $router->post('transactaions',  ['uses' => 'api\v1\TransactaionsController@save']);

});

