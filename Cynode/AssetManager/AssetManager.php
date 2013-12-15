<?php

namespace Cynode\AssetManager;

/**
 * Description of AssetManager
 *
 * @author Nurcahyo al Hidayah <nurcahyo@inlabstudio.com>
 */
class AssetManager
{

    private $_packages = array();

    public function registerPackage($package)
    {
        $this->_packages[] = $package;
    }

    public function publish($path, $options = [])
    {
        
    }

}

?>
