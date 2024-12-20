<?php

namespace Core\ContainerConfig;

class ContainerConfig
{
    /**
     * Dependency injection container definitions - key is the class name and value is the factory function
     * 
     * Add your class name and factory function here
     * 
     * @var array
     */
    private static array $config = [];

    /**
     * Get the container configuration
     * 
     * @return array
     */
    public static function get_config(): array
    {
        return self::$config;
    }
}
