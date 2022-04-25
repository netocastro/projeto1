<?php

use Stonks\Router\Router;

require_once "vendor/autoload.php";

$route = new Router(BASE_PATH);

/**
 * Rotas referente a aplicação
 */
$route->namespace('Source\Controllers\Site');

$route->group(null);

/** Realiza o login do usuario */
$route->post('/login', 'Requests:login', 'request.login');
/** Realiza o logout do usuario */
$route->post('/logout', 'Requests:logout', 'request.logout');


/**
 * Rotas referente a API
 */
$route->namespace('Source\Controllers\Api');

$route->group('api');

/** Retorna todos os usuarios */
$route->get('/user', 'UserController:index', 'user.index');
/** Insere um novo usuario */
$route->post('/user', 'UserController:store', 'user.store');
/** Exibe um usuario especifico */
$route->get('/user/{id}', 'UserController:show', 'user.show');
/** Atualiza um usuario */
$route->put('/user/{id}', 'UserController:update', 'user.update');
/** Deleta um usuario */
$route->delete('/user/{id}', 'UserController:delete', 'user.delete');

/** Executa as rotas */
$route->dispatch();

/** retornar erros da requisição */
if ($route->error()) {
    echo "<h4>{$route->error()}</h4>";
}
