<?php

namespace Stonks\DataLayer;

use DateTime;
use Exception;
use PDOException;
use stdClass;

/**
 * Trait CrudTrait
 *
 * @package Stonks\DataLayer
 */
trait CrudTrait
{

	/**
	 * @var Exception|PDOException|null
	 */
	private $fail;

	/**
	 * @param array $data
	 * @return int|string|null
	 */
	private function create(array $data)
	{
		if ((is_bool($this->timestamps) && $this->timestamps) || $this->timestamps === 'created_at') {
			$data['created_at'] = (new DateTime('now'))->format('Y-m-d H:i:s');
		}

		try {
			$connect = Connect::testConnection($this->database);
			$treatData = $this->treatDataCreate($data);

			$statement = $connect->prepare("INSERT INTO {$this->entity} ({$treatData->columns}) VALUES ({$treatData->values})");
			$statement->execute($this->filter($treatData->data));

			$lastInsertId = $connect->lastInsertId();
			$primary = $this->primary;

			return ($lastInsertId ? $lastInsertId : $this->data->$primary);
		} catch (PDOException $exception) {
			$this->fail = $exception;
			return null;
		}
	}

	/**
	 * @param array $data
	 * @param string $terms
	 * @param string $params
	 * @return int|null
	 */
	private function update(array $data, string $terms, string $params): ?int
	{
		if ((is_bool($this->timestamps) && $this->timestamps) || $this->timestamps === 'updated_at') {
			$data['updated_at'] = (new DateTime('now'))->format('Y-m-d H:i:s');
		}

		try {
			$connect = Connect::testConnection($this->database);
			$treatData = $this->treatDataUpdate($data);

			parse_str($params, $arrParams);

			$statement = $connect->prepare("UPDATE {$this->entity} SET {$treatData->values} WHERE {$terms}");
			$statement->execute($this->filter(array_merge($treatData->data, $arrParams)));

			return ($statement->rowCount() ?? 1);
		} catch (PDOException $exception) {
			$this->fail = $exception;
			return null;
		}
	}

	/**
	 * @param string $terms
	 * @param string|null $params
	 * @return bool
	 */
	private function delete(string $terms, ?string $params): bool
	{
		try {
			$connect = Connect::testConnection($this->database);
			$statement = $connect->prepare("DELETE FROM {$this->entity} WHERE {$terms}");

			if ($params) {
				parse_str($params, $arrParams);
				$statement->execute($arrParams);
				return true;
			}

			$statement->execute();

			return true;
		} catch (PDOException $exception) {
			$this->fail = $exception;
			return false;
		}
	}

	/**
	 * @param array $data
	 * @return array|null
	 */
	private function filter(array $data): ?array
	{
		$filter = [];

		foreach ($data as $key => $value) {
			$filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
		}

		return $filter;
	}

	/**
	 * @param array $data
	 * @return object
	 */
	private function treatDataCreate(array $data): object
	{
		$treatData = new stdClass();
		$treatData->data = $data;
		$treatData->columns = '';
		$treatData->values = '';

		if ($this->functionSql) {
			foreach ($this->functionSql as $key => $value) {
				if (array_key_exists($key, $treatData->data)) {
					unset($treatData->data[$key]);
				}
			}

			$data = array_merge($this->functionSql, $data);
			$treatData->values .= implode(', ', array_values($this->functionSql));
		}

		$treatData->columns = implode(', ', array_keys($data));
		$treatData->values .= ($treatData->values ? ', ' : '') . ':' . implode(', :', array_keys($treatData->data));

		return $treatData;
	}

	/**
	 * @param array $data
	 * @param array $dataSet
	 * @return object
	 */
	private function treatDataUpdate(array $data, array $dataSet = []): object
	{
		$treatData = new stdClass();
		$treatData->data = $data;
		$treatData->values = '';

		if ($this->functionSql) {
			foreach ($this->functionSql as $key => $value) {
				if (is_array($dataSet)) {
					$dataSet[] = "{$key} = {$this->functionSql[$key]}";
				}

				if (array_key_exists($key, $treatData->data)) {
					unset($treatData->data[$key]);
				}
			}
		}

		foreach ($treatData->data as $bind => $value) {
			$dataSet[] = "{$bind} = :{$bind}";
		}

		$treatData->values .= implode(', ', $dataSet);

		return $treatData;
	}
}