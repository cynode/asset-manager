<?php

namespace Cynode\AssetManager\Tests\Util;

use Cynode\AssetManager\Util\Reflect;

/**
 * Description of ReflectTest
 *
 * @author Nurcahyo al Hidayah <nurcahyo@inlabstudio.com>
 */
class ReflectTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructClass()
    {
        $instance = 'Cynode\AssetManager\Tests\Template\ReflectTestClass';
        $class = Reflect::constructClass($instance);
        $this->assertInstanceOf($instance, $class);
    }

    public function testConstructClassWithArgs()
    {
        $foo = 'foo';
        $bar = 'bar';
        $class = Reflect::constructClass('Cynode\AssetManager\Tests\Template\ReflectTestClass', array($foo, $bar));
        $this->assertEquals($foo, $class->foo);
        $this->assertEquals($bar, $class->bar);
    }

}

?>
