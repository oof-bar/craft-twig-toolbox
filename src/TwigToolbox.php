<?php
namespace oofbar\twigtoolbox;

use Craft;
use craft\base\Plugin;
use oofbar\twigtoolbox\config\ToolboxConfig;
use oofbar\twigtoolbox\twig\ToolboxExtension;

/**
 * Base TwigToolbox plugin class.
 * 
 * Responsible for bootstrapping and injecting custom features.
 */
class TwigToolbox extends Plugin
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Craft::$app->getView()->registerTwigExtension(new ToolboxExtension);
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): ToolboxConfig
    {
        return new ToolboxConfig;
    }
}
