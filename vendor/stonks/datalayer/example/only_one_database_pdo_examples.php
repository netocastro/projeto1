<?php

require_once __DIR__ . '/only_one_database_config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Stonks\DataLayer\Connect;

/*
 * Obtenha instância e erros de PDO
 */
$connect = Connect::getInstance();
$error = Connect::getError();

/*
 * Verifique o erro de conexão
 */
if ($error) {
	echo $error->getMessage();
	return;
}

/*
 * Obtenha dados
 */
$users = $connect->query('SELECT * FROM user LIMIT 3');
var_dump($users->fetchAll());