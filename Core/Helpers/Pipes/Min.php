<?php

namespace Core\Helpers\Pipes;

class Min
{
    public function handler(string $value, string $prop, int $min)
    {
        if ($value < $min) {
            return [$prop => "The value required to be at least {$min}"];
        }
    }
}
