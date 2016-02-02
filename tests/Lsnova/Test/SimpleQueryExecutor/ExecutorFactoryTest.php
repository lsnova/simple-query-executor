<?php

namespace Lsnova\Test\SimpleQueryExecutor;

use Lsnova\SimpleQueryExecutor\DbCacheableExecutor;
use Lsnova\SimpleQueryExecutor\DbExecutor;
use Lsnova\SimpleQueryExecutor\ExecutorFactory;
use Lsnova\SimpleQueryExecutorBundle\DataCollector\CacheHitsContainer;
use Lsnova\Test\SimpleQueryExecutor\Stub\Cache;
use Lsnova\Test\SimpleQueryExecutor\Stub\EntityManager;

class ExecutorFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testExecute()
    {
        $cacheHitsContainer = new CacheHitsContainer();
        $em = new EntityManager();
        $connection =& $em->getConnection();

        $executor = ExecutorFactory::build($em, false, null, $cacheHitsContainer);
        $this->assertTrue($executor instanceof DbExecutor);

        $cache = new Cache();

        $executor = ExecutorFactory::build($em, true, $cache, $cacheHitsContainer);
        $this->assertTrue($executor instanceof DbExecutor);

        $cache->up();
        $executor = ExecutorFactory::build($em, true, $cache, $cacheHitsContainer);
        $this->assertTrue($executor instanceof DbCacheableExecutor);

        $executor = ExecutorFactory::build($em, false, $cache, $cacheHitsContainer);
        $this->assertTrue($executor instanceof DbExecutor);

        $cache->setUpException();
        $executor = ExecutorFactory::build($em, true, $cache, $cacheHitsContainer);
        $this->assertTrue($executor instanceof DbExecutor);

    }
}