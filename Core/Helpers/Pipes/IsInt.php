<?php

namespace Core\Helpers\Pipes;

use Core\Request\PhantomRequest;

class IsInt
{

    public function __construct(private PhantomRequest $phantomRequest) {}
    public function handler(string $value, string $prop)
    {
        // Verificar si el valor no es un int
        if (!is_numeric($value) || (int) $value != $value) {
            return [$prop => "The value required to be an integer"];
        }
    }
}
