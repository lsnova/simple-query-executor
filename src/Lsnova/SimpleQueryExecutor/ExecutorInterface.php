<?php

namespace Lsnova\Imonitor\BackendBundle\Service;

use Lsnova\Imonitor\BackendBundle\Query\QueryInterface;

interface ExecutorInterface
{
    /**
     * @param QueryInterface $query
     * @return int
     */
    public function execute(QueryInterface $query);

    /**
     * @param QueryInterface $query
     * @return array
     */
    public function fetch(QueryInterface $query);
}