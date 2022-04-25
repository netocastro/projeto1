<?php

/*
 * Dois ou mais bancos de dados
 */
define('DATA_LAYER_CONFIG', [
	'db_1' => [
		'driver' => 'mysql',
		'host' => 'localhost',
		'port' => '3306',
		'dbname' => 'datalayer_example_1',
		'username' => 'root',
		'passwd' => '',
		'options' => [
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
			PDO::ATTR_CASE => PDO::CASE_NATURAL,
		],
	],
	0 => [
		'driver' => 'mysql',
		'host' => 'localhost',
		'port' => '3306',
		'dbname' => 'datalayer_example_2',
		'username' => 'root',
		'passwd' => '',
		'options' => [
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
			PDO::ATTR_CASE => PDO::CASE_NATURAL,
		],
	],
]);