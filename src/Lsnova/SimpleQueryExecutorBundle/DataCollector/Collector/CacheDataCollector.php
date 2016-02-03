<?php

namespace Lsnova\SimpleQueryExecutorBundle\DataCollector\Collector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Lsnova\SimpleQueryExecutorBundle\DataCollector\CacheHitsContainer;

/**
 * @see http://symfony.com/doc/current/cookbook/profiler/data_collector.html
 * @package Lsnova\Imonitor\BackendBundle\DataCollector
 */
class CacheDataCollector extends DataCollector
{

    /**
     * @var CacheHitsContainer
     */
    private $hitsContainer;

    /**
     * @param CacheHitsContainer $cacheHitsContainer
     */
    public function __construct(CacheHitsContainer $cacheHitsContainer = null)
    {
        $this->hitsContainer = $cacheHitsContainer;
    }

    /**
     * @param Request $request A Request instance
     * @param Response $response A Response instance
     * @param \Exception $exception An Exception instance
     *
     * @api
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['cache_saves'] = $this->hitsContainer->getSaves();
        $this->data['cache_read'] = $this->hitsContainer->getReads();
    }

    /**
     * @return array
     */
    public function getDataCollectionSaves()
    {
        return $this->data['cache_saves'];
    }

    /**
     * @return array
     */
    public function getDataCollectionGet()
    {
        return $this->data['cache_read'];
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     *
     * @api
     */
    public function getName()
    {
        return 'lsn_simple_query_executor_cache_collector';
    }


} 