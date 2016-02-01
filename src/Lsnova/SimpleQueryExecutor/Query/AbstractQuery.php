<?php

namespace Lsnova\SimpleQueryExecutor\Query;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

abstract class AbstractQuery implements QueryInterface
{

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var ParameterBag
     */
    protected $replacements;

    /**
     * @var array
     */
    protected $requiredReplacements = array();

    /**
     * doba
     * @var integer
     */
    protected $ttl = 86400;

    public function __construct()
    {
        $this->replacements = new ParameterBag();
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addParameter($name, $value)
    {
        $this->parameters[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @param array $value
     */
    public function addArrayParameter($name, array $value)
    {
        $length = count($value);
        for ($i = 0; $i < $length; $i++) {
            $this->parameters[$name . "_" . $i] = $value[$i];
        }
        return $this;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $this->parameters[$key] = $value;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @throws \RuntimeException
     */
    public function checkReplacements()
    {
        $replacements = $this->replacements;
        array_map(
            function ($item) use ($replacements) {
                if (!$replacements->has($item)) {
                    throw new \RuntimeException(sprintf("Brak wartości dla klucza %s", $item));
                }
            },
            $this->requiredReplacements
        );
    }

    /**
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @param int $ttl
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    }
}