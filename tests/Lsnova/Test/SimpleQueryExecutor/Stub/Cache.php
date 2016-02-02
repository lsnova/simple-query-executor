<?php

namespace Lsnova\Test\SimpleQueryExecutor\Stub;

class Cache implements \Doctrine\Common\Cache\Cache
{
    private $upAndRunning = false;
    private $exception = false;

    public function fetch($id)
    {
        // TODO: Implement fetch() method.
    }

    public function contains($id)
    {
        // TODO: Implement contains() method.
    }

    public function save($id, $data, $lifeTime = 0)
    {
        // TODO: Implement save() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function getStats()
    {
        if ($this->exception) {
            throw new \Exception();
        }
        return $this->upAndRunning;
    }

    public function up()
    {
        $this->upAndRunning = true;
    }

    public function setUpException()
    {
        $this->exception = true;
    }
}