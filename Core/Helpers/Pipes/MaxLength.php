<?php

namespace Core\Helpers\Pipes;

class MaxLength
{
    public function handler(string $value, string $prop, int $length)
    {
        if (strlen($value) >  $length) {
            return [$prop => "The value required to be at most {$length} characters"];
        }
    }
}
