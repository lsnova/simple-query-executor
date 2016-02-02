<?php

namespace Lsnova\SimpleQueryExecutor;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Lsnova\SimpleQueryExecutor\Query\QueryInterface;

class DbExecutor implements ExecutorInterface
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->connection = $em->getConnection();
    }

    /**
     * @param QueryInterface $query
     * @return int
     */
    public function execute(QueryInterface $query)
    {
        $query->checkReplacements();
        $plainQuery = $query->getPlainQuery();
        list($parameters, $plainQuery) = $this->modifyParametersFromArrayToScalar($query->getParameters(), $plainQuery);
        return $this->connection->executeQuery($query->getPlainQuery(), $parameters);
    }

    /**
     * @param QueryInterface $query
     * @return array
     */
    public function fetch(QueryInterface $query)
    {
        $query->checkReplacements();
        $plainQuery = $query->getPlainQuery();
        list($parameters, $plainQuery) = $this->modifyParametersFromArrayToScalar($query->getParameters(), $plainQuery);

        return $this->connection->fetchAll($plainQuery, $parameters);
    }

    /**
     * replace complex parameters to scalar's array
     *
     * @param array $parameters
     * @return array
     */
    protected function modifyParametersFromArrayToScalar(array $parameters = array(), $plainQuery)
    {
        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                $replacement = implode(", ", array_map(function ($index) use ($key) {
                    return ":" . $key . "__" . $index;
                }, range(0, count($value) - 1)));

                $plainQuery = $this->replaceOutsideQuotes(":" . $key, $replacement, $plainQuery);

                for ($i = 0; $i < count($value); $i++) {
                    $parameters[$key . "__" . $i] = $value[$i];
                }
                unset($parameters[$key]);
            }
        }

        return array($parameters, $plainQuery);
    }

    /**
     * @param string $replace
     * @param string $with
     * @param string $string
     * @return string
     */
    private function replaceOutsideQuotes($replace, $with, $string)
    {
        $result = "";
        $outside = preg_split('/(\'[^\']*\')/', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
        while ($outside) {
            $result .= preg_replace('/' . $replace . '\b/', $with, array_shift($outside)) . array_shift($outside);
        }

        return $result;
    }
}