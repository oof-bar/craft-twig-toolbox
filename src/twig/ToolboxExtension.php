<?php

namespace oofbar\twigtoolbox\twig;

use yii\base\InvalidConfigException;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

use oofbar\twigtoolbox\TwigToolbox;

class ToolboxExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'Toolbox';
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        $settings = TwigToolbox::getInstance()->getSettings();
        $filters = [];

        foreach ($settings->filters as $name => $fn) {
            if (is_numeric($name)) {
                throw new InvalidConfigException('Filters must be declared with an alphanumeric name.');
            }

            $filters[] = new TwigFilter($name, $fn);
        }

        return $filters;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        $settings = TwigToolbox::getInstance()->getSettings();
        $functions = [];

        foreach ($settings->functions as $name => $fn) {
            if (is_numeric($name)) {
                throw new InvalidConfigException('Functions must be declared with an alphanumeric name.');
            }

            $functions[] = new TwigFunction($name, $fn);
        }

        return $functions;
    }

    /**
     * @inheritdoc
     */
    public function getGlobals(): array
    {
        $settings = TwigToolbox::getInstance()->getSettings();

        return $settings->globals;
    }

    /**
     * @inheritdoc
     */
    public function getTests(): array
    {
        $settings = TwigToolbox::getInstance()->getSettings();
        $tests = [];

        foreach ($settings->tests as $name => $test) {
            $tests[] = new TwigTest($name, $test);
        }

        return $tests;
    }
}
