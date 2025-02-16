<?php

namespace config;

class GuardsConfig
{
    private static array $guardsMap = [];

    public static function getGuard(string $guard): string
    {
        if (!isset(self::$guardsMap[$guard])) {
            throw new \Exception('Guard no encontrada');
        }

        return self::$guardsMap[$guard];
    }
}
