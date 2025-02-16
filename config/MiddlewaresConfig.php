<?php

namespace config;

use Core\Helpers\Guards\ValidateHour;

class MiddlewaresConfig
{
    private static array $middlewaresMap = [];
    public static function getMiddleware(string $middleware): string
    {
        if (!isset(self::$middlewaresMap[$middleware])) {
            throw new \Exception('Middleware no encontrada');
        }
        return self::$middlewaresMap[$middleware];
    }
}
