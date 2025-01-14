<?php

namespace Core\Services\SessionService;

class SessionService
{
    public function __construct()
    {
        // session_start();
    }

    public function set(string $key, mixed $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function remove(string $key)
    {
        unset($_SESSION[$key]);
    }

    public function close()
    {
        session_write_close();
    }

    public function destroy()
    {
        session_destroy();
    }
}
