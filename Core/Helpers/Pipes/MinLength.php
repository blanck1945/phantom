<?php

namespace Core\Helpers\Pipes;

class MinLength
{
    public function handler(string $value, string $prop, int $length)
    {
        if (strlen($value) <  $length) {
            return [$prop => "The value required to be at least {$length} characters"];
        }
    }
}
