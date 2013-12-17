<?php

namespace Cynode\AssetManager;

/**
 * Description of AssetManager
 *
 * @author Nurcahyo al Hidayah <nurcahyo@inlabstudio.com>
 */
class AssetManager
{

    /**
     * @var Cynode\AssetManager\Component; 
     */
    private static $_accessor;

    public static function __callStatic($name, $arguments)
    {
        if (!static::$_accessor) {
            static::$_accessor = new Component();
        }
        if (method_exists(static::$_accessor, $name))
            return call_user_func_array(array(static::$_accessor, $name), $arguments);
        throw new \Exception('Call to undefined method ' . get_class() . "::$name");
    }

}

?>
