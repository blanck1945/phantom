<?php

namespace Core\Interfaces\cookie;

interface CookieServiceInterface
{
    public function set(string $name, string $value, int $time = 3600): void;
    public function get(string $name): mixed;
    public function delete(string $name): void;
}
