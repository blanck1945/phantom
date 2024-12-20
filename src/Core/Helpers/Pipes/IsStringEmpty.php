<?php

namespace Core\Helpers\Pipes;

class IsStringEmpty
{
    public function handler(string $value, $prop)
    {
        if (empty($value)) {
            return [$prop => "The value is empty"];
        }
    }
}
