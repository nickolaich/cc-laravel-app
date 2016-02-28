<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

$api = app('Dingo\Api\Routing\Router');



$api->version('v1', function ($api) {
    $api->post('/login', 'App\Http\Controllers\AuthController@login');
    $api->delete('/logout/{token}', 'App\Http\Controllers\AuthController@logout');
    $api->get('/valid/{token}', 'App\Http\Controllers\AuthController@valid');
});

$api->version('v1', ['middleware' => 'token'], function ($api) {

    $api->resource('users', 'App\Http\Controllers\SuperAdmin\UserController');


    $api->get('persons/search/{keywords}', 'App\Http\Controllers\CMS\PersonController@search');
    $api->resource('persons', 'App\Http\Controllers\CMS\PersonController');


    $api->get('/scheme/{scheme}/form', 'App\Http\Controllers\SchemeController@form');
    $api->get('/scheme/{scheme}/sections', 'App\Http\Controllers\SchemeController@sections');
    $api->get('/scheme/{section}/questions', 'App\Http\Controllers\SchemeController@questions');

    $api->get('/scheme/{scheme}/{section}/data/{person}', 'App\Http\Controllers\SchemeController@data');



});

app('Dingo\Api\Exception\Handler')->register(function (Exception $exception) {
    /** @var \App\Exceptions\Handler $handler */
    $handler = app('App\Exceptions\Handler');
    return $handler->render(app('Illuminate\Http\Request'), $exception);
});
