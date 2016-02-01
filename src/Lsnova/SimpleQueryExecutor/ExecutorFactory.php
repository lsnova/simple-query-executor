<?php

namespace Lsnova\Imonitor\BackendBundle\Service;

use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\EntityManager;
use Lsnova\Imonitor\BackendBundle\DataCollector\CacheHitsContainer;
use Predis\Connection\ConnectionException;

/**
 * @package Lsnova\Imonitor\BackendBundle\Service
 * @author Patryk Szlagowski <patryksz@lsnova.pl>
 */
class ExecutorFactory
{

    /**
     * @param EntityManager $em
     * @param Cache $cache
     * @param CacheHitsContainer $hitsContainer
     * @return DbCacheableExecutor|DbExecutor
     */
    public static function build(EntityManager $em, $doCache = false, Cache $cache = null, CacheHitsContainer $hitsContainer = null)
    {
        if (!$cache) {
            return new DbExecutor($em);
        }
        try {
            $cache->getStats();
        } catch (ConnectionException $e) {
            return new DbExecutor($em);
        }
        if($doCache){
            return new DbCacheableExecutor($em, $cache, $hitsContainer);
        } else {
            return new DbExecutor($em);
        }

    }
}