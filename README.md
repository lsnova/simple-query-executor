SimpleQueryExecutor [![Build Status](https://travis-ci.org/lsnova/simple-query-executor.svg?branch=master)](https://travis-ci.org/lsnova/simple-query-executor)
==================

This library can help you to execute complex queries without query builders, entity operations, etc.

Why SimpleQueryExecutor
----------------------
1. it's fast - queries are executed in native mode
2. it's simple - you have `DbExecutor`, which can execute `QueryInterface` object
3. it's customizable - you can implements QueryInterface. For example, in `SimpleQueryExecutorBundle` we provided TemplatedQuery, which can parse Twig template.

Contents
--------

```
|
+-- SimpleQueryExecutor         # main library directory
|   +-- Query
|       +-- QueryInterface.php
|       +-- AbstractQuery.php   # almost done query with ttl, parameters operations
|   +-- ExecutorInterface.php
|   +-- DbExecutor.php          # base executor
|   +-- DbCacheableExecutor.php # extended BaseExecutor with cache support
|   +-- ExecutorFactory.php     # ugly, but working factory returning DbCacheableExecutor if you want to use cache AND cache server is running
+-- SimpleQueryExecutorBundle   # Symfony2 bundle
|   +-- DataCollector           # @see http://symfony.com/doc/current/cookbook/profiler/data_collector.html
|   +-- DependencyInjection
|   +-- Query
|           +-- TemplatingQuery # extended AbstractQuery with twig's templates support
|   +-- Resources
```

Installation
------------

1. Composer dependency
    ```bash
    $ composer require lsnova/simple-query-executor
    ```

2. Register bundle
    ```php

        public function registerBundles()
        {
            $bundles = array(
                [...]
                new \Lsnova\SimpleQueryExecutorBundle\LsnovaSimpleQueryExecutorBundle()
            );
    ```

3. Get `ExecutorInterface` by define:
    ```
    # services.yml
        foo.bar.db.executor:
            class: %foo.bar.db.executor.class%
            factory_service: lsn.simple_query_executor.factory.executor
            factory_method: build
            arguments: [@doctrine.orm.default_entity_manager]
    ```

4. Define your query (templated)

    ```
    # services.yml
        foo.bar.query.dummy:
            class: Lsnova\SimpleQueryExecutorBundle\Query\TemplatingQuery
            arguments: ['FooBarBundle:query:dummy.sql.twig', @twig]
    ```

    and execute

    ```php
    $query = $this->container->get('foo.bar.query.dummy');
    $executor = $this->container->get('foo.bar.db.executor');
    $executor->fetch($query);
    ```

    OR declare simple Query object:

    ```php
    <?php

    use Lsnova\SimpleQueryExecutor\Query\AbstractQuery;

    class Query extends AbstractQuery
    {
        /**
         * @var string
         */
        private $sql;

        /**
         * @param string $sql
         */
        public function __construct($sql)
        {
            $this->sql = $sql;
        }

        /**
         * @return string
         */
        public function getPlainQuery()
        {
            return $this->sql;
        }
    }
    ```

    and execute

    ```php
    $query = new Query('SELECT * from users');
    $executor = $this->container->get('foo.bar.db.executor');
    $executor->fetch($query);
    ```

Credits and License
-------

Software was made by [Logisfera Nova](http://lsn.io).

**Contributors**
* Patryk Szlagowski <patryksz@lsnova.pl>

This program is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation.