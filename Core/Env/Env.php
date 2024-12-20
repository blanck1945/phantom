<?php

namespace Core\Env;

use Dotenv\Dotenv;
use Exception;

class Env
{

    public static function get($key)
    {
        return $_ENV[$key];
    }

    public static function all()
    {
        return $_ENV;
    }

    public static function has($key)
    {
        return isset($_ENV[$key]);
    }

    public static function unset($key)
    {
        unset($_ENV[$key]);
    }

    public static function clear()
    {
        $_ENV = [];
    }

    public static function load($path)
    {
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();
    }

    public static function required($key)
    {
        if (!isset($_ENV[$key])) {
            throw new Exception("Environment variable $key not found");
        }
    }
}