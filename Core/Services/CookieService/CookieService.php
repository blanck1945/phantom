<?php

namespace Core\Services\CookieService;

use Core\Interfaces\cookie\CookieServiceInterface;

class CookieService implements CookieServiceInterface
{
    private array $defaultSetting = [
        'path' => '/',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ];

    /**
     * Set a cookie
     * 
     * @param string $name - The name of the cookie
     * @param string $value - The value of the cookie
     * @return void
     */
    public function set(string $name, string $value, int $time = 3600): void
    {
        $this->defaultSetting['expires'] = time() + $time;

        setcookie($name, $value, $this->defaultSetting);
    }

    public function get(string $name): mixed
    {
        return $_COOKIE[$name] ?? null;
    }

    public function delete($name): void
    {
        setcookie($name, '', time() - 3600, '/');
    }
}
