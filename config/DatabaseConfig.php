<?php

declare(strict_types=1);

namespace config;

use Core\Env\Env;

class DatabaseConfig
{
    public static function getDatabaseConfig(): array
    {
        return [
            'pgsql' => [
                'driver' => 'pgsql',
                'url' => '',
                'host' => Env::get('DB_HOST'),
                'port' => Env::get('DB_PORT'),
                'database' => Env::get('DB_NAME'),
                'username' => Env::get('DB_USER'),
                'password' => Env::get('DB_PASSWORD'),
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'search_path' => 'public',
                'sslmode' => 'prefer',
                'collation' => 'utf8_unicode_ci',
            ]
        ];
    }
}
