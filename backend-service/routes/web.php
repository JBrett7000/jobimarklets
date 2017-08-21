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


$app->get(
    '/account/activate/{checksum:[0-9a-zA-Z]+}',
    ['uses' => 'AuthController@activate']
);


$app->get('/[{any}]', function () use ($app) {
    return $app->make('view')->make('welcome');
});

$app->group(['prefix' => 'api'], function () use ($app) {
    // Custom User Routes
    new \Jobimarklets\AuthRoutes($app);
});
