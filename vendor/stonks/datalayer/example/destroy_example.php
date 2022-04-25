<?php

require_once __DIR__ . '/only_one_database_config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Models/User.php';

use Example\Models\User;

/*
 * Delete um usuário
 */
print PHP_EOL . '#delete um usuário' . PHP_EOL;
$user = (new User())->findById(4);

if (is_null($user)) {
	echo 'Usuário não encontrado';
	exit;
}

$destroy = $user->destroy();

/*
 * Obtenha erro
 */
if ($user->fail()) {
	echo $user->fail()->getMessage() . PHP_EOL;
	exit;
}

if (!$destroy) {
	echo 'Usuário não deletado';
} else {
	echo 'Usuário deletado';
}