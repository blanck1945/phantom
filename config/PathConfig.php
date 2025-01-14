<?php

namespace config;

class PathConfig
{
    private static array $publicPaths = ['/docs', '/users/:id'];

    public static function publicPaths(string $path): bool
    {
        return in_array($path, self::$publicPaths);
    }
}
