<?php

namespace Lsnova\SimpleQueryExecutor\Query;

use Symfony\Component\Templating\EngineInterface;

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
     * @param string $template
     * @param EngineInterface $templating
     */
    public function __construct($template, EngineInterface $templating)
    {
        parent::__construct();
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