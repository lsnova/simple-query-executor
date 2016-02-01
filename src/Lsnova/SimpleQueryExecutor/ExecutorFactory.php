<?php

namespace Lsnova\Imonitor\BackendBundle\Service;

use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\EntityManager;
use Lsnova\SimpleQueryExecutorBundle\Collector\CacheHitsContainer;

/**
 * @package Lsnova\Imonitor\BackendBundle\Service
 */
class ExecutorFactory
{

    /**
     * @param EntityManager $em
     * @param Cache $cache
     * @param CacheHitsContainer $hitsContainer
     * @return DbCacheableExecutor|DbExecutor
     */
    public static function build(
        EntityManager $em,
        $doCache = false,
        Cache $cache = null,
        CacheHitsContainer $hitsContainer = null
    )
    {
        if (!$cache) {
            return new DbExecutor($em);
        }
        try {
            $cache->getStats();
        } catch (\Exception $e) {
            return new DbExecutor($em);
        }
        if ($doCache) {
            return new DbCacheableExecutor($em, $cache, $hitsContainer);
        } else {
            return new DbExecutor($em);
        }
    }
}