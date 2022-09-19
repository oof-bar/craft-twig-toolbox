<?php

namespace oofbar\twigtoolbox\config;

use craft\config\BaseConfig;

class ToolboxConfig extends BaseConfig
{
    /**
     * @var array Extra filters to inject into the Twig environment.
     * @see https://twig.symfony.com/doc/3.x/advanced.html#filters
     */
    public array $filters = [];

    /**
     * @var array Extra functions to inject into the Twig environment.
     * @see https://twig.symfony.com/doc/3.x/advanced.html#functions
     */
    public array $functions = [];

    /**
     * @var array Extra global variables to inject into the Twig environment.
     * @see https://twig.symfony.com/doc/3.x/advanced.html#globals
     */
    public array $globals = [];

    /**
     * @var array Extra global variables to inject into the Twig environment.
     * @see https://twig.symfony.com/doc/3.x/advanced.html#tests
     */
    public array $tests = [];

    /**
     * @see ToolboxConfig::$filters
     * @param array $filters
     * @return self
     */
    public function filters(array $filters = []): self
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @see ToolboxConfig::$functions
     * @param array $functions
     * @return self
     */
    public function functions(array $functions = []): self
    {
        $this->functions = $functions;

        return $this;
    }

    /**
     * @see ToolboxConfig::$globals
     * @param array $globals
     * @return self
     */
    public function globals(array $globals = []): self
    {
        $this->globals = $globals;

        return $this;
    }

    /**
     * @see ToolboxConfig::$tests
     * @param array $tests
     * @return self
     */
    public function tests(array $tests = []): self
    {
        $this->tests = $tests;

        return $this;
    }
}
