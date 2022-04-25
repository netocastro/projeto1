<?php

namespace Stonks\DataLayer;

use PDO;
use PDOException;
use stdClass;

/**
 * Class Connect
 *
 * @package Stonks\DataLayer
 */
class Connect
{

	/**
	 * @var PDO|stdClass|null
	 */
	private static $instance;

	/**
	 * @var PDOException|stdClass|null
	 */
	private static $error;

	/**
	 * @param string|null $database
	 * @return PDO|null
	 */
	public static function getInstance(?string $database = null): ?PDO
	{
		if (empty(self::$instance)) {
			if (array_key_exists('driver', DATA_LAYER_CONFIG)) {
				self::getOnlyOneConnection();
			} else {
				self::getMultipleConnections();
			}
		}

		if ($database && isset(self::$instance->$database)) {
			return self::$instance->$database;
		}

		return (self::$instance instanceof PDO ? self::$instance : null);
	}

	/**
	 * @param string|null $database
	 * @return PDOException|null
	 */
	public static function getError(?string $database = null): ?PDOException
	{
		if ($database && isset(self::$error->$database)) {
			return self::$error->$database;
		}

		return (self::$error instanceof PDOException ? self::$error : null);
	}

	/**
	 * @return void
	 */
	private static function getOnlyOneConnection(): void
	{
		try {
			self::$instance = new PDO(
				DATA_LAYER_CONFIG['driver'] . ':host=' . DATA_LAYER_CONFIG['host'] . ';dbname=' . DATA_LAYER_CONFIG['dbname'] . ';port=' . DATA_LAYER_CONFIG['port'],
				DATA_LAYER_CONFIG['username'],
				DATA_LAYER_CONFIG['passwd'],
				DATA_LAYER_CONFIG['options']
			);
		} catch (PDOException $exception) {
			self::$error = $exception;
		}
	}

	/**
	 * @return void
	 */
	private static function getMultipleConnections(): void
	{
		self::$instance = new stdClass();
		self::$error = new stdClass();

		foreach (DATA_LAYER_CONFIG as $key => $config) {
			$dbname = (is_string($key) ? $key : $config['dbname']);

			try {
				self::$instance->$dbname = new PDO(
					"{$config['driver']}:host={$config['host']};dbname={$config['dbname']};port={$config['port']}",
					$config['username'],
					$config['passwd'],
					$config['options']
				);
			} catch (PDOException $exception) {
				self::$error->$dbname = $exception;
			}
		}
	}

	/**
	 * @param string|null $database
	 * @return PDO
	 */
	public static function testConnection(?string $database = null): PDO
	{
		$connect = self::getInstance($database);

		if (is_null($connect) || !$connect instanceof PDO) {
			throw (self::getError($database) ?? new PDOException("Unknown key or database '{$database}'"));
		}

		return $connect;
	}

	/**
	 * Connect constructor.
	 */
	final private function __construct()
	{
	}

	/**
	 * Connect clone.
	 */
	final private function __clone()
	{
	}
}