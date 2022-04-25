<?php

namespace Stonks\DataLayer;

use Exception;
use PDO;
use PDOException;
use stdClass;

/**
 * Class DataLayer
 *
 * @package Stonks\DataLayer
 */
class DataLayer
{

    use CrudTrait;

    /**
     * @var string
     */
    private $entity;

    /**
     * @var array|null
     */
    private $required;

    /**
     * @var string
     */
    private $primary;

    /**
     * @var bool|string
     */
    private $timestamps;

    /**
     * @var string|null
     */
    private $database;

    /**
     * @var string|null
     */
    private $statement;

    /**
     * @var array|null
     */
    private $params;

    /**
     * @var string|null
     */
    private $group;

    /**
     * @var string|null
     */
    private $order;

    /**
     * @var string|null
     */
    private $limit;

    /**
     * @var string|null
     */
    private $offset;

    /**
     * @var string|null
     */
    private $saveMethod;

    /**
     * @var array|null
     */
    private $functionSql;

    /**
     * @var Exception|PDOException|null
     */
    private $fail;

    /**
     * @var object|null
     */
    private $data;

    /**
     * DataLayer constructor.
     *
     * @param string $entity
     * @param array|null $required
     * @param string $primary
     * @param bool|string $timestamps
     * @param string|null $database
     */
    public function __construct(
        string $entity,
        ?array $required = [],
        string $primary = 'id',
        $timestamps = false,
        ?string $database = null
    ) {
        $this->entity = $entity;
        $this->required = $required;
        $this->primary = $primary;
        $this->timestamps = $timestamps;
        $this->database = $database;
        $this->functionSql = [];
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value): void
    {
        if (empty($this->data)) {
            $this->data = new stdClass();
        }

        $this->data->$name = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return isset($this->data->$name);
    }

    /**
     * @param $name
     * @return string|null
     */
    public function __get($name): ?string
    {
        $method = $this->toCamelCase($name);

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        if (method_exists($this, $name)) {
            return $this->$name();
        }

        return ($this->data->$name ?? null);
    }

    /**
     * @return Exception|PDOException|null
     */
    public function fail()
    {
        return $this->fail;
    }

    /**
     * @return object|null
     */
    public function data(): ?object
    {
        return $this->data;
    }

    /**
     * @param string|null $terms
     * @param string|null $params
     * @param string $columns
     * @return DataLayer
     */
    public function find(?string $terms = '', ?string $params = '', string $columns = '*'): DataLayer
    {
        if ($terms) {
            $this->statement = "SELECT {$columns} FROM {$this->entity} WHERE {$terms}";
            parse_str($params, $this->params);
            return $this;
        }

        $this->statement = "SELECT {$columns} FROM {$this->entity}";
        return $this;
    }

    /**
     * @param string $value
     * @param string $columns
     * @return DataLayer|null
     */
    public function findByPrimaryKey(string $value, string $columns = '*'): ?DataLayer
    {
        return $this->find("{$this->primary} = :{$this->primary}", "{$this->primary}={$value}", $columns)->fetch();
    }

    /**
     * @param string $id
     * @param string $columns
     * @return DataLayer|null
     */
    public function findById(string $id, string $columns = '*'): ?DataLayer
    {
        return $this->find('id = :id', "id={$id}", $columns)->fetch();
    }

    /**
     * @param string $group
     * @return $this|null
     */
    public function group(string $group): ?DataLayer
    {
        $this->group = " GROUP BY {$group}";
        return $this;
    }

    /**
     * @param string $order
     * @return $this|null
     */
    public function order(string $order): ?DataLayer
    {
        $this->order = " ORDER BY {$order}";
        return $this;
    }

    /**
     * @param int $limit
     * @return $this|null
     */
    public function limit(int $limit): ?DataLayer
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    /**
     * @param int $offset
     * @return $this|null
     */
    public function offset(int $offset): ?DataLayer
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    /**
     * @param bool $all
     * @return DataLayer|mixed|null
     */
    public function fetch(bool $all = false)
    {
        try {
            $connect = Connect::testConnection($this->database);
            $statement = $connect->prepare($this->statement . $this->group . $this->order . $this->limit . $this->offset);
            $statement->execute($this->params);

            if (!$statement->rowCount()) {
                return null;
            }

            if ($all) {
                return $statement->fetchAll(PDO::FETCH_CLASS, static::class);
            }

            return $statement->fetchObject(static::class);
        } catch (PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @return int|null
     */
    public function count(): ?int
    {
        try {
            $connect = Connect::testConnection($this->database);
            $statement = $connect->prepare($this->statement);
            $statement->execute($this->params);

            return $statement->rowCount();
        } catch (PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param string $column
     * @param string $function
     * @return void
     */
    public function functionSql(string $column, string $function): void
    {
        $this->functionSql[$column] = $function;
        $this->$column = $function;
    }

    /**
     * @return $this|null
     */
    public function make(): ?DataLayer
    {
        $this->saveMethod = 'create';
        return $this;
    }

    /**
     * @return $this|null
     */
    public function change(): ?DataLayer
    {
        $this->saveMethod = 'update';
        return $this;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $primary = $this->primary;
        $value = null;

        try {
            if (!$this->required()) {
                throw new Exception('Preencha os campos necessários');
            }

            if ($this->saveMethod === 'update') {
                $value = $this->data->$primary;
                $data = (array) $this->data;

                unset($data[$primary]);

                $this->update($data, "{$primary} = :{$primary}", "{$primary}={$value}");
            } else {
                $value = $this->create((array) $this->data);
            }

            if (!$value) {
                return false;
            }

            $this->data = $this->findByPrimaryKey($value)->data();

            return true;
        } catch (Exception $exception) {
            $this->fail = $exception;
            return false;
        }
    }

    /**
     * @return bool
     */
    public function destroy(): bool
    {
        $primary = $this->primary;
        $value = $this->data->$primary;

        if (empty($value)) {
            return false;
        }

        return $this->delete("{$primary} = :{$primary}", "{$primary}={$value}");
    }

    /**
     * @return bool
     */
    private function required(): bool
    {
        $data = (array) $this->data;

        foreach ($this->required as $field) {
            if (
                !isset($data[$field])
                || is_string($data[$field])
                && $data[$field] == ''
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $string
     * @return string
     */
    private function toCamelCase(string $string): string
    {
        $camelCase = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        $camelCase[0] = strtolower($camelCase[0]);
        return $camelCase;
    }
}
