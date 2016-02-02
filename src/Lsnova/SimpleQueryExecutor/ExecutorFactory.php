<?php

namespace Lsnova\SimpleQueryExecutor;

use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\EntityManagerInterface;
use Lsnova\SimpleQueryExecutorBundle\DataCollector\CacheHitsContainer;

/**
 * @package Lsnova\Imonitor\BackendBundle\Service
 */
class ExecutorFactory
{

    /**
     * @param EntityManager $em
     * @param bool $doCache
     * @param Cache|null $cache
     * @param CacheHitsContainer|null $hitsContainer
     * @return DbCacheableExecutor|DbExecutor
     */
    public static function build(
        EntityManagerInterface $em,
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