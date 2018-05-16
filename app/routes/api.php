<?php

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/login', ['uses' => 'AuthController@authenticate']);

    $router->group(
        [
            'prefix' => 'users',
            'middleware' => 'jwt.auth'
        ],
        function () use ($router) {
            $router->get('/', ['uses' => 'UserController@showAllUsers']);

            $router->get('/{id}', ['uses' => 'UserController@showOneUser']);

            $router->post('/', ['uses' => 'UserController@create']);

            $router->delete('/{id}', ['uses' => 'UserController@delete']);

            $router->put('/{id}', ['uses' => 'UserController@update']);
        }
    );
});
