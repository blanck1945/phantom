<?php

namespace config;

use Core\Database\Database;
use Core\Services\CookieService\CookieService;
use Core\Services\FormService\FormService;
use Core\Ui\Forms\FormBuilder;

class BindingsConfig
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
            'Core\Database\Database' => fn() => Database::getInstance(),
            'Core\Interfaces\cookie\CookieServiceInterface' => fn() => new CookieService(),
            'Core\Services\FormService' => new FormService(new FormBuilder()),
        ];
    }
}
