<?php

namespace Core\Helpers\Pipes;

class IsEmail
{
    public function handler(string $value, string $prop)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return [$prop => "The value is not a valid email"];
        }
    }
}
