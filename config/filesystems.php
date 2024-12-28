<?php

namespace config;

class Filesystems
{
    public const PROJECT_ROOT = __DIR__;

    public static string $envPath = __DIR__ . '/../.env';

    public static string $controllersPath = '\..\app\Http\Controller';

    public static function getPath(string $path): string
    {
        var_dump(self::PROJECT_ROOT . $path);
        return self::PROJECT_ROOT . $path . '\\';
    }
}
