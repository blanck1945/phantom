<?php

namespace Core\Pipes;

class IsString
{
    public function handler(string $value)
    {
        echo "Iniciado la validacion";
        if (!is_string($value)) {
            throw new \Exception("The value is not a string");
        }

        echo "Paso la validacion";
        print_r($value);
    }
}
