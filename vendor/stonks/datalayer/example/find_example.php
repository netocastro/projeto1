<?php

require_once __DIR__ . '/only_one_database_config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Models/User.php';

use Example\Models\User;

/*
 * Modelo
 */
print PHP_EOL . '#modelo' . PHP_EOL;
$model = new User();
var_dump($model);

/*
 * Encontre
 */
print PHP_EOL . '#encontrar' . PHP_EOL;

// Encontre todos os usuários
$users = $model->find()->fetch(true);
var_dump($users);

// Encontre todos os usuários por grupo
$users = $model->find()->group('last_name')->fetch(true);
var_dump($users);

// Encontre todos os usuários e ordene por campo DESC
$users = $model->find()->order('email DESC')->fetch(true);
var_dump($users);

// Encontre todos os usuários com limite de 2
$users = $model->find()->limit(2)->fetch(true);
var_dump($users);

// Encontre todos os usuários com limite 2 e deslocamento 2
$users = $model->find()->limit(2)->offset(2)->fetch(true);
var_dump($users);

// Encontre um usuário por condição (apenas termos)
$user = $model->find('first_name = "Giovanni"')->fetch();
var_dump($user);

// Encontre um usuário por condição (termos e parâmetros)
$user = $model->find('last_name = :last_name', 'last_name=A. L. Oliveira')->fetch();
var_dump($user);

// Encontre um usuário por duas condições
$user = $model->find('first_name = :first_name AND last_name = :last_name', 'first_name=Giovanni&last_name=A. L. Oliveira')->fetch();
var_dump($user);

/*
 * Encontre pela chave primária
 */
print PHP_EOL . '#encontre pela chave primária' . PHP_EOL;
$user = $model->findByPrimarykey(2);
var_dump($user);

/*
 * Encontre pelo ID
 */
print PHP_EOL . '#encontre pelo id' . PHP_EOL;
$user = $model->findById(3);
var_dump($user);

/*
 * Contagem de registros encontrados
 */
print PHP_EOL . '#contagem de registros encontrados' . PHP_EOL;
$totalUsers = $model->find()->count();
var_dump($totalUsers);

/*
 * Listando os dados
 */
print PHP_EOL . '#listando os dados' . PHP_EOL;
$users = $model->find()->fetch(true);

if ($users) {
	foreach ($users as $user) {
		var_dump($user->data());
	}
} else {
	echo 'Nenhum usuário encontrado';
}

/*
 * Parâmetros seguros
 */
print PHP_EOL . '#parâmetros seguros' . PHP_EOL;
$params = http_build_query(['first_name' => 'Giovanni']);
$user = $model->find('first_name = :first_name', $params);
var_dump($user, $user->fetch());