<?php
/**
 * Created by IntelliJ IDEA.
 * User: abdulklara
 * Date: 02.02.2016
 * Time: 11:18
 */

namespace Lsnova\Test\SimpleQueryExecutor\Stub;


use Doctrine\DBAL\Cache\ArrayStatement;

class Executed extends ArrayStatement
{
    /**
     * @var array
     */
    private $executed;

    /**
     * Executed constructor.
     * @param array $executed
     */
    public function __construct(array $executed)
    {
        $this->executed = $executed;
    }

    /**
     * @param null $fetchMode
     * @return array
     */
    public function fetchAll($fetchMode = null)
    {
        return $this->executed;
    }
}