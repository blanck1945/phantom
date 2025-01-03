<?php

namespace Core\Helpers\Container;

use Core\Database\Database;

class ContainerConfig
{
    /**
     * Dependency injection container definitions - key is the class name and value is the factory function
     * 
     * Add your class name and factory function here
     * 
     * @return array
     */
    public static function get_config(): array
    {
        return [
            'Core\Database\Database' => fn() => Database::getInstance()
        ];
    }
}
