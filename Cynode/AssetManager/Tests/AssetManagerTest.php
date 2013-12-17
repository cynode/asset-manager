<?php

namespace Cynode\AssetManager\Tests;

use Cynode\AssetManager\AssetManager;

/**
 * Description of AssetManagerTest
 *
 * @author Nurcahyo al Hidayah <nurcahyo@inlabstudio.com>
 */
class AssetManagerTest extends \PHPUnit_Framework_TestCase
{

    protected $configDir;
    protected $config;

    public function deleteRecursive($dir)
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)) as $file) {
            if (!preg_match("#.gitinclude#", $file->getPathName())) {
                unlink($file->getPathname());
            }
        }
    }

    public function setUp()
    {
        $this->configDir = __DIR__ . '/config';
        $this->config = require($this->configDir . '/asset.php');
        parent::setUp();
    }

    protected function tearDown()
    {
        $this->deleteRecursive($this->config['cacheDir']);
        $this->deleteRecursive($this->config['assetDir']);
        parent::tearDown();
    }

    public function testInvalidMethod()
    {
        $methodName = 'nomethod';
        $this->setExpectedException('\Exception', 'Call to undefined method Cynode\AssetManager\AssetManager' . "::$methodName");
        AssetManager::$methodName();
    }

    public function testCallProtectedMethod()
    {
        $this->setExpectedException("\Exception", 'call_user_func_array() expects parameter 1 to be a valid callback, cannot access protected method Cynode\AssetManager\Component::createAssets()');
        AssetManager::createAssets();
    }

    public function testInitWrongConfigFile()
    {
        $configFile = 'wrongConfigFile';
        $this->setExpectedException('\RuntimeException', sprintf('File "%s" could not be found.', $configFile));
        AssetManager::init($configFile);
    }

    public function testInitWithoutCacheDir()
    {
        $configFile = $this->configDir . '/withoutCacheDir.php';
        $this->setExpectedException('\RuntimeException', "Cache directory not setted in configuration files, Please set it with key 'cacheDir'=>'valid/path/to/cache/directory'");
        AssetManager::init($configFile);
    }

    public function testInitWrongCacheDir()
    {
        $configFile = $this->configDir . '/wrongCacheDir.php';
        $this->setExpectedException('\RuntimeException', sprintf('Directory "%s" could not be found.', 'wrong'));
        AssetManager::init($configFile);
    }

    public function testInit()
    {
        $configFile = $this->configDir . '/asset.php';
        AssetManager::init($configFile);
    }

}

?>
