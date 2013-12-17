<?php

namespace Cynode\AssetManager\Tests\Template;

/**
 * Description of ReflectTestClass
 *
 * @author Nurcahyo al Hidayah <nurcahyo@inlabstudio.com>
 */
class ReflectTestClass
{

    public $foo;
    public $bar;

    public function __construct($foo=null, $bar=null)
    {
        $this->foo = $foo;
        $this->bar= $bar;
    }

}

?>
