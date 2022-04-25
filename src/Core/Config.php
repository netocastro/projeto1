<?php

session_start();
date_default_timezone_set('America/Sao_Paulo');

$s = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on") ? 's' : '');

define("BASE_PATH", "http{$s}://{$_SERVER['HTTP_HOST']}/development/2022/trabalho/projeto1");

define('DATA_LAYER_CONFIG', [
    'driver' => 'mysql',
    'host' => 'localhost',
    'port' => '3306',
    'dbname' => 'projeto1',
    'username' => 'root',
    'passwd' => '',
    'options' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
    ],
]);
