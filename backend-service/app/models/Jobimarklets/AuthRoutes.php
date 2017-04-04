<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: AuthRoutes.php
 * Date: 31/03/2017
 * Time: 16:27
 */

namespace Jobimarklets;


class AuthRoutes extends AbstractRoutes
{
    public function buildRoutes(&$app)
    {

        $app->post('/auth', ['uses' => 'AuthController@authenticate']);

        $app->group(['middleware' => 'valtoken'], function () use ($app) {

            $app->get('/auth', ['uses' => 'AuthController@index']);

            $app->post('/auth/reset', ['uses' => 'AuthController@reset']);

            $app->get('/auth/logout', ['uses' => 'AuthController@logout']);

            $app->post('/auth/create', ['uses' => 'AuthController@create']);

            $app->post('/auth/update/{id:[0-9]+}', ['uses' => 'AuthController@update']);

            $app->post('/auth/delete', ['uses' => 'AuthController@unregister']);

        });
    }

}