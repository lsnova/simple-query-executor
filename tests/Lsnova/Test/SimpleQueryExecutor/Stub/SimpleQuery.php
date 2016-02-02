<?php

namespace Lsnova\Test\SimpleQueryExecutor\Stub;

use Lsnova\SimpleQueryExecutor\Query\AbstractQuery;

class SimpleQuery extends AbstractQuery
{

    /**
     * @var string
     */
    private $sql;

    /**
     * SimpleQuery constructor.
     * @param string $sql
     */
    public function __construct($sql)
    {
        parent::__construct();
        $this->sql = $sql;
    }

    /**
     * @return string
     */
    public function getPlainQuery()
    {
        foreach ($this->parameters as $parameter => $value) {
            $this->sql = str_replace(sprintf(":%s", $parameter), '?', $this->sql);
        }
        return $this->sql;
    }
}