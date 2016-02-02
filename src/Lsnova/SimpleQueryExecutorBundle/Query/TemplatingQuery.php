<?php

namespace Lsnova\SimpleQueryExecutorBundle\Query;

use Symfony\Component\Templating\EngineInterface;
use Lsnova\SimpleQueryExecutor\Query\AbstractQuery;

/**
 * @package Lsnova\SimpleQueryExecutor\Query
 */
class TemplatingQuery extends AbstractQuery
{

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var string
     */
    protected $template;

    /**
     * TemplatingQuery constructor.
     * @param EngineInterface $templating
     * @param string $template
     */
    public function __construct($template, $templating)
    {
        $this->template = $template;
        $this->templating = $templating;
    }


    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getPlainQuery()
    {
        return $this->templating->render($this->template, $this->parameters);
    }
}