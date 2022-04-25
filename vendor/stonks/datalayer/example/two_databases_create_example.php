<?php

require_once __DIR__ . '/two_databases_config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Models/Database_1/User.php';
require_once __DIR__ . '/Models/Database_2/User.php';

use Example\Models\Database_1\User as User_1;
use Example\Models\Database_2\User as User_2;

/*
 * Crie um usuário no banco de dados 1
 */
print PHP_EOL . '#crie um usuário no banco de dados 1' . PHP_EOL;
$user_1 = new User_1();
$user_1->functionSql('name', 'CONCAT(:first_name, " ", :last_name)');
$user_1->first_name = 'Giovanni';
$user_1->last_name = 'A. L. Oliveira';
$user_1->email = 'giovanni.al.oliveira@gmail.com';
$save_1 = $user_1->save(); // Ou $user->make()->save();

/*
 * Obtenha erro
 */
if ($user_1->fail()) {
	echo $user_1->fail()->getMessage() . PHP_EOL;
}

if (!$save_1) {
	echo 'Usuário não cadastrado';
} else {
	echo 'Usuário cadastrado';
}

/*
 * Crie um usuário no banco de dados 2
 */
print PHP_EOL . '#crie um usuário no banco de dados 2' . PHP_EOL;
$user_2 = new User_2();
$user_2->functionSql('name', 'CONCAT(:first_name, " ", :last_name)');
$user_2->first_name = 'Giovanni';
$user_2->last_name = 'Oliveira';
$user_2->email = 'giovanni.al.oliveira@gmail.com';
$save_2 = $user_2->save();

if ($user_2->fail()) {
	echo $user_2->fail()->getMessage() . PHP_EOL;
}

if (!$save_2) {
	echo 'Usuário não cadastrado';
} else {
	echo 'Usuário cadastrado';
}