<?php

namespace Lsnova\Test\SimpleQueryExecutor;

use Lsnova\SimpleQueryExecutor\DbCacheableExecutor;
use Lsnova\SimpleQueryExecutor\DbExecutor;
use Lsnova\SimpleQueryExecutor\Query\QueryInterface;
use Lsnova\SimpleQueryExecutorBundle\DataCollector\CacheHitsContainer;
use Lsnova\Test\SimpleQueryExecutor\Stub\Cache;
use Lsnova\Test\SimpleQueryExecutor\Stub\SimpleQuery;
use Lsnova\Test\SimpleQueryExecutor\Stub\EntityManager;

class QueryTest extends \PHPUnit_Framework_TestCase
{

    const QUERY_PARSED = 'SELECT * FROM users where id > ? AND username = ? AND number IN (?, ?, ?)';

    /**
     * @var QueryInterface
     */
    private $query;

    protected function setUp()
    {
        $this->query = new SimpleQuery('SELECT * FROM users where id > :id AND username = :username AND number IN (:number_0, :number_1, :number_2)');
        $this->query->setParameters(array('id' => 2));
        $this->query->addParameter('username', 'John');
        $this->query->addArrayParameter('number', array(0,1,2));
        $this->query->setTtl(1);
    }

    public function testQueryProperties()
    {
        $this->assertNotEmpty($this->query->getTtl());
        $this->assertNotEmpty($this->query->getPlainQuery());
        $this->assertNotEmpty($this->query->getParameters());
        $this->assertEquals(1, $this->query->getTtl());

        $this->assertEquals(array(
            'id' => 2,
            'username' => 'John',
            'number_0' => 0,
            'number_1' => 1,
            'number_2' => 2,
        ), $this->query->getParameters());
    }

    public function testParseSimpleQuery()
    {
        $this->assertEquals(self::QUERY_PARSED, $this->query->getPlainQuery());
    }

    public function testExecute()
    {
        $em = new EntityManager();
        $connection = $em->getConnection();

        $executor = new DbExecutor($em);
        $executor->execute($this->query);

        $executed = $connection->getLastExecuted();

        $this->assertEquals(self::QUERY_PARSED, $executed['query']);
        $this->assertEquals($this->query->getParameters(), $executed['params']);

        $fetched = $executor->fetch($this->query);
        $this->assertEquals(self::QUERY_PARSED, $fetched['query']);
        $this->assertEquals($this->query->getParameters(), $fetched['params']);
    }

    public function testExecuteCache()
    {
        $em = new EntityManager();
        $connection = $em->getConnection();

        $executor = new DbCacheableExecutor($em, new Cache(), new CacheHitsContainer());
        $executor->execute($this->query);

        $executed = $connection->getLastExecuted();

        $this->assertEquals(self::QUERY_PARSED, $executed['query']);
        $this->assertEquals($this->query->getParameters(), $executed['params']);

        $fetched = $executor->fetch($this->query);
        $this->assertEquals(self::QUERY_PARSED, $fetched['query']);
        $this->assertEquals($this->query->getParameters(), $fetched['params']);
    }
}