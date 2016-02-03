<?php

namespace Lsnova\SimpleQueryExecutor;

use Doctrine\Common\Cache\Cache;
use Doctrine\DBAL\Cache\ArrayStatement;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Cache\ResultCacheStatement;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Lsnova\SimpleQueryExecutor\Query\QueryInterface;
use Lsnova\SimpleQueryExecutorBundle\DataCollector\CacheHitsContainer;

class DbCacheableExecutor extends DbExecutor
{

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var QueryCacheProfile
     */
    protected $queryCacheProfile;

    /**
     * @var CacheHitsContainer
     */
    protected $hitsContainer;

    /**
     * @param EntityManager $em
     * @param Cache $cache
     * @param CacheHitsContainer $hitsContainer
     */
    public function __construct(EntityManagerInterface $em, Cache $cache, CacheHitsContainer $hitsContainer)
    {
        $this->em = $em;
        $this->connection = $em->getConnection();
        $this->cache = $cache;
        $this->hitsContainer = $hitsContainer;
    }

    /**
     * @param QueryInterface $query
     * @return int
     */
    public function execute(QueryInterface $query)
    {
        $query->checkReplacements();
        list($parameters, $plainQuery) = $this->modifyParametersFromArrayToScalar(
            $query->getParameters(),
            $query->getPlainQuery()
        );

        return $this
            ->connection
            ->executeQuery(
                $plainQuery,
                $parameters,
                array(),
                $this->buildCache($query)
            );
    }

    /**
     * @param QueryInterface $query
     * @return array
     */
    public function fetch(QueryInterface $query)
    {
        $query->checkReplacements();

        $executed = $this->execute($query);
        $data = $executed->fetchAll();

        if ($executed instanceof ResultCacheStatement) {
            $this->hitsContainer->addSave($query->getPlainQuery(), serialize($data));
        }
        if ($executed instanceof ArrayStatement) {
            $this->hitsContainer->addRead($query->getPlainQuery(), serialize($data));
        }
        $executed->closeCursor();

        return $data;
    }

    /**
     * @param QueryInterface $query
     * @return QueryCacheProfile
     */
    protected function buildCache(QueryInterface $query)
    {
        if ($query->getTtl() === 0) {
            return null;
        }
        return (new QueryCacheProfile($query->getTtl(), null, $this->cache));
    }
}