<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group([

    'middleware' => 'api',
    // 'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

/*
 *  Rotas sem o middleware para rodar os testes, estou com problemas na 
 *  biblioteca do tymon\JWTAuth parace que perde o usuario na geração do token
 */

// Route::namespace('Api')->prefix('v1')->group(function(){
//     Route::apiResource('products', 'v1\ProductController', ['except' => 'edit']);
// });



/***
 * O comentario abaixo deve ser retirado quando for testar a as rotas no POSTMAN
 * E para rodar os testes vc deve daixar as linhas abaixo comentadas e retirar os comentarios das linhas acima
 */

Route::group([
    'namespace' => 'Api',
    'prefix' => 'v1',
    'middleware' => ['auth:api', 'jwt.auth'],

], function ($router) {

    Route::apiResource('products', 'v1\ProductController', ['except' => 'edit']);
});

