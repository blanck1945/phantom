<?php

namespace Core\Helpers\Pipes;

class MinLength
{
    public function handler(
        string $value,
        string $prop,
        int $length,
        string $message = ""
    ) {
        $default_message = "The value required to be at least {$length} characters";
        if (strlen($value) <  $length) {
            return [$prop => empty($message) ? $default_message : $message];
        }
    }
}
