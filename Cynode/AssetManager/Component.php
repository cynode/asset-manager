<?php

namespace Cynode\AssetManager;

use Assetic\AssetManager as Am;
use Assetic\Asset\AssetCache;
use Assetic\FilterManager;
use Assetic\Factory\AssetFactory;
use Assetic\Asset\AssetCollection;
use Assetic\Asset\AssetReference;

/**
 * Description of Component
 *
 * @author Nurcahyo al Hidayah <nurcahyo@inlabstudio.com>
 */
class Component
{

    private $_compiledAssets = array();
    protected
        $config = array(),
        /**
         * @var \Assetic\Factory\AssetFactory 
         */
        $factory,
        /**
         * @var \Assetic\AssetManager
         */
        $am,
        /**
         * @var \Assetic\FilterManager
         */
        $fm;

    public function init($configFile)
    {
        if (!is_file($configFile)) {
            throw new \RuntimeException(sprintf('File "%s" could not be found.', $configFile));
        }
        $this->config = require($configFile);
        $this->fm = new FilterManager();
        $this->factory = new AssetFactory(isset($this->config['baseDir']) ? $this->config['baseDir'] : "{$_SERVER['SCRIPT_FILENAME']}/..", isset($this->config['debug']) ? $this->config['debug'] : false);
        $this->am = new Am();
        $this->factory->setAssetManager($this->am);
        $this->factory->setFilterManager($this->fm);

        if (!isset($this->config['cacheDir'])) {
            throw new \RuntimeException("Cache directory not setted in configuration files, Please set it with key 'cacheDir'=>'valid/path/to/cache/directory'");
        } elseif (!is_dir($this->config['cacheDir'])) {
            throw new \RuntimeException(sprintf('Directory "%s" could not be found.', $this->config['cacheDir']));
        }
        if (!isset($this->config['baseUrl'])) {
            $this->config['baseUrl'] = substr_replace($this->config['assetDir'], '', 0, strlen($_SERVER['DOCUMENT_ROOT']));
        }
        $modifiedTime = filemtime($configFile);
        if (is_file($cached = "{$this->config['cacheDir']}/assetsLoader_{$modifiedTime}.bin")) {
            $this->_compiledAssets = $this->loadAssetsLoader($cached);
        }
        $this->compileAssets();
        $this->createAssetsLoader($this->config['cacheDir'] . "/assetsLoader_{$modifiedTime}.bin");
    }

    protected function loadAssetsLoader($filename)
    {
        return unserialize(file_get_contents($filename));
    }

    protected function createAssetsLoader($fileName)
    {
        $dir = realpath("{$this->config['cacheDir']}");
        foreach (scandir($dir) as $item) {
            if (substr($item, 0, strlen('assetsLoader_')) === 'assetsLoader_')
                unlink($dir . DIRECTORY_SEPARATOR . $item);
        }

        file_put_contents($fileName, serialize($this->_compiledAssets));
        @chmod($fileName, 0777);
    }

    protected function setFilters()
    {
        foreach ($this->config['filters'] as $alias => $filter) {
            $this->fm->set($alias, Util\Reflect::constructClass($filter['class'], isset($filter['__construct']) ? $filter['__construct'] : array()));
        }
    }

    protected function createAsset($name, array $assets)
    {

        $inputs = array();
        $collection = new AssetCollection();
        $filters = array();
        foreach ($assets as $type => $value) {
            if ($type === 'filters') {
                $filters = $value;
            } elseif (!is_array($value) && $value{0} === '@') {
                $collection->add(new AssetReference($this->am, substr_replace($value, '', 0, 1)));
            } elseif ($type === 'files') {
                foreach ($value as $keyOrSource => $sourceOrFilter) {
                    if (!is_array($sourceOrFilter))
                        $collection->add(new \Assetic\Asset\FileAsset($sourceOrFilter));
                    else {
                        $filter = array();
                        foreach ($sourceOrFilter as $filterName) {
                            $filter[] = $this->fm->get($filterName);
                        }
                        $collection->add(new \Assetic\Asset\FileAsset($keyOrSource, $filter));
                    }
                }
            } elseif ($type === 'globs') {
                foreach ($value as $keyOrGlob => $globOrFilter) {
                    if (!is_array($globOrFilter))
                        $collection->add(new \Assetic\Asset\GlobAsset($globOrFilter));
                    else {
                        $filter = array();
                        foreach ($globOrFilter as $filterName) {
                            $filter[] = $this->fm->get($filterName);
                        }
                        $collection->add(new \Assetic\Asset\GlobAsset($keyOrGlob, $filter));
                    }
                }
            }
        }
        $this->am->set($name, new AssetCache($collection, new \Assetic\Cache\FilesystemCache($this->config['cacheDir'] . '/chunks')));
        $filename = str_replace('_', '.', $name);
        $this->_compiledAssets[$name] = "{$this->config['baseUrl']}/" . $filename;
        file_put_contents("{$this->config['assetDir']}/$filename", $this->factory->createAsset("@$name", $filters)->dump());
        @chmod($filename, 0777);
    }

    protected function createAssets()
    {
        foreach ($this->config['assets'] as $name => $assets) {
            $this->createAsset($name, $assets);
        }
    }

    protected function compileAssets()
    {
        if (isset($this->config['filters']))
            $this->setFilters();
        if (isset($this->config['assets']))
            $this->createAssets();
    }

    public function assetUrl($alias)
    {
        if (isset($this->_compiledAssets[$alias]))
            return $this->_compiledAssets[$alias];
        return false;
    }

}

?>
