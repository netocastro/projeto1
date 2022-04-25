<?php

require_once __DIR__ . '/two_databases_config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Stonks\DataLayer\Connect;

/*
 * Obtenha instâncias e erros de PDO
 */
$database_1 = 'db_1';
$database_2 = 'datalayer_example_2';

$connect_1 = Connect::getInstance($database_1);
$connect_2 = Connect::getInstance($database_2);

$error_1 = Connect::getError($database_1);
$error_2 = Connect::getError($database_2);


/*
 * Verifique os erros de conexão
 */
if (is_null($error_1) && is_null($connect_1)) {
	echo "Unknown key or database '{$database_1}'";
	exit;
}

if (!is_null($error_1)) {
	echo $error_1->getMessage();
	exit;
}

if (is_null($error_2) && is_null($connect_2)) {
	echo "Unknown key or database '{$database_2}'";
	exit;
}

if (!is_null($error_2)) {
	echo $error_2->getMessage();
	exit;
}

/*
 * Obtenha dados
 */
$users_1 = $connect_1->query('SELECT * FROM user LIMIT 3');
var_dump($users_1->fetchAll());

$users_2 = $connect_2->query('SELECT * FROM user LIMIT 3');
var_dump($users_2->fetchAll());