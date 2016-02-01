<?php

namespace Lsnova\SimpleQueryExecutor\Query;

interface QueryInterface
{

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addParameter($name, $value);

    /**
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters);

    /**
     * @return string
     */
    public function getPlainQuery();

    /**
     * @throws \RuntimeException
     */
    public function checkReplacements();

    /**
     * @return integer
     */
    public function getTtl();
}