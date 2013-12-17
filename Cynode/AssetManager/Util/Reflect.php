<?php

namespace Cynode\AssetManager\Util;

class Reflect
{

    /**
     * Construct new class by class name
     * @param string $className class that want to be constructed.
     * @param array $args Constructor params if exist
     * @return object Constructed Class
     */
    public static function constructClass($className, array $args = array())
    {
        $reflector = new \ReflectionClass($className);
        if ($reflector->hasMethod('__construct') && count($args) > 0) {
            return $reflector->newInstanceArgs($args);
        }
        return $reflector->newInstanceArgs();
    }

}

