<?php

namespace Cynode\AssetManager;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use CoffeeScript\Compiler as CoffeeScript;

class CoffeephpFilter implements FilterInterface
{

    public $options = array();

    public function __construct($options = array())
    {
        if (!isset($options['header']))
            $options['header'] = false;
        $this->options = $options;
    }

    public function filterLoad(AssetInterface $asset)
    {
        $content = $asset->getContent();
        $asset->setContent(CoffeeScript::compile($content, $this->options));
    }

    public function filterDump(AssetInterface $asset)
    {
        $content = $asset->getContent();
        $this->options['filename'] = $asset->getSourcePath();
    }

}
