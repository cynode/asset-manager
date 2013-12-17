<?php

namespace Cynode\AssetManager\Tests;

use Cynode\AssetManager\CoffeephpFilter;
use Assetic\Asset\StringAsset;

/**
 * Description of CoffeephpFilterTest
 *
 * @author Nurcahyo al Hidayah <nurcahyo@inlabstudio.com>
 */
class CoffeephpFilterTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var \Cynode\AssetManager\CoffeephpFilter
     */
    protected $filter;
    protected $stringAsset;
    protected $unbaredCompiledStringAsset;
    protected $compiledStringAsset;

    protected function setUp()
    {
        $this->stringAsset = "test=()->alert('foo')";
        $this->compiledStringAsset = <<<EOD
var test;

test = function() {
  return alert('foo');
};

EOD;
        $this->unbaredCompiledStringAsset = <<<EOD
(function() {
  var test;

  test = function() {
    return alert('foo');
  };

}).call(this);

EOD;
        if (!class_exists('CoffeeScript\Compiler')) {
            $this->markTestSkipped('CoffeeScript\CoffeeScript is not installed');
        }
    }

    public function testFilterLoad()
    {
        $this->filter = new CoffeephpFilter(array('bare' => false));
        $asset = new StringAsset($this->stringAsset);
        $asset->load();
        $this->filter->filterLoad($asset);
        $this->assertEquals($this->unbaredCompiledStringAsset, $asset->getContent(),'->filterLoad() sets an include path based on source url');
    }

    public function testFilterLoadBared()
    {
        $this->filter = new CoffeephpFilter(array('bare' => false));
        $asset = new StringAsset($this->stringAsset);
        $this->filter->options['bare'] = true;
        $asset->load();
        $this->filter->filterLoad($asset);
        $this->assertEquals($this->compiledStringAsset, $asset->getContent(),'->filterLoad() sets an include path based on source url');
    }

}

?>
