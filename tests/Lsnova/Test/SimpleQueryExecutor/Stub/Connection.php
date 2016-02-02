<?php

namespace Lsnova\Test\SimpleQueryExecutor\Stub;

use Doctrine\DBAL\Driver\Connection as DriverConnection;

class Connection implements DriverConnection
{

    /**
     * @var array[]
     */
    private $executed = array();

    /**
     * @param string $query
     * @param array $params
     * @param array $types
     * @param mixed $qcp
     */
    public function executeQuery($query, array $params = array(), $types = array(), $qcp = null)
    {
        $this->executed[] = array(
            'query' => $query,
            'params' => $params,
            'types' => $types,
            'qcp' => $qcp
        );
        if ($qcp) {
            return new Executed($this->getLastExecuted());
        }
    }

    /**
     * @param string $sql
     * @param array $params
     * @param array $types
     * @return array
     */
    public function fetchAll($sql, array $params = array(), $types = array())
    {
        $this->executeQuery($sql, $params, $types);
        return $this->getLastExecuted();
    }

    /**
     * @return array[]
     */
    public function getExecuted()
    {
        return $this->executed;
    }

    /**
     * @return array
     */
    public function getLastExecuted()
    {
        return end($this->executed);
    }

    public function exec($statement)
    {
        // TODO: Implement prepare() method.
    }

    public function prepare($prepareString)
    {
        // TODO: Implement prepare() method.
    }

    public function query()
    {
        // TODO: Implement query() method.
    }

    public function quote($input, $type = \PDO::PARAM_STR)
    {
        // TODO: Implement quote() method.
    }

    public function lastInsertId($name = null)
    {
        // TODO: Implement lastInsertId() method.
    }

    public function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
    }

    public function commit()
    {
        // TODO: Implement commit() method.
    }

    public function rollBack()
    {
        // TODO: Implement rollBack() method.
    }

    public function errorCode()
    {
        // TODO: Implement errorCode() method.
    }

    public function errorInfo()
    {
        // TODO: Implement errorInfo() method.
    }


}