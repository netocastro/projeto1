<?php

require_once __DIR__ . '/only_one_database_config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Models/User.php';

use Example\Models\User;

/*
 * Atualize um usuário
 */
print PHP_EOL . '#atualize um usuário' . PHP_EOL;
$user = (new User())->findById(4);

if (is_null($user)) {
	echo 'Usuário não encontrado';
	exit;
}

$user->last_name = 'Oliveira';
$save = $user->change()->save();

/*
 * Obtenha erro
 */
if ($user->fail()) {
	echo $user->fail()->getMessage() . PHP_EOL;
}

if (!$save) {
	echo 'Usuário não atualizado';
} else {
	echo 'Usuário atualizado';
}