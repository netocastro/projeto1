<?php

require_once __DIR__ . '/only_one_database_config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Models/User.php';

use Example\Models\User;

/*
 * Crie um usuário
 */
print PHP_EOL . '#crie um usuário' . PHP_EOL;
$user = new User();
$user->functionSql('name', 'CONCAT(:first_name, " ", :last_name)');
$user->first_name = 'Giovanni';
$user->last_name = 'A. L. Oliveira';
$user->email = 'giovanni.al.oliveira@gmail.com';
$save = $user->save(); // Ou $user->make()->save();

/*
 * Obtenha erro
 */
if ($user->fail()) {
	echo $user->fail()->getMessage() . PHP_EOL;
}

if (!$save) {
	echo 'Usuário não cadastrado';
} else {
	echo 'Usuário cadastrado';
}