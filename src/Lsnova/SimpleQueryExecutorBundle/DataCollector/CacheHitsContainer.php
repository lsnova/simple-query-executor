<?php

namespace Lsnova\SimpleQueryExecutorBundle\DataCollector;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @see http://symfony.com/doc/current/cookbook/profiler/data_collector.html
 * @see http://symfony.com/doc/current/cookbook/service_container/scopes.html
 * @package Lsnova\Imonitor\BackendBundle\DataCollector
 */
class CacheHitsContainer
{
    /**
     * @var ParameterBag
     */
    private $saves;

    /**
     * @var ParameterBag
     */
    private $reads;

    public function __construct()
    {
        $this->saves = new ParameterBag();
        $this->reads = new ParameterBag();
    }

    /**
     * @param $key
     * @param $value
     */
    public function addSave($key, $value)
    {
        $this->saves->set($key, $value);
    }

    /**
     * @param $key
     * @param $value
     */
    public function addRead($key, $value)
    {
        $this->reads->set($key, $value);
    }

    /**
     * @return ParameterBag
     */
    public function getReads()
    {
        return $this->reads;
    }

    /**
     * @return ParameterBag
     */
    public function getSaves()
    {
        return $this->saves;
    }
} 