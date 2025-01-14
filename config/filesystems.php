<?php

namespace config;

class Filesystems
{
    public const PROJECT_ROOT = __DIR__;

    public const VIEW_PATH = __DIR__ . '/../views/pages/';

    public const CACHE_DIR = __DIR__ . '/../Core/Cache/views/';

    public const JSON_CACHE_FILE = __DIR__ . '/../Core/Cache/views/directory.json';

    public static string $envPath = __DIR__ . '/../.env';

    public static string $controllersPath = '\..\app\Http\Controller';

    public static function getPath(string $path): string
    {
        return self::PROJECT_ROOT . $path . '\\';
    }
}
