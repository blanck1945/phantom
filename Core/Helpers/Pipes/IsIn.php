<?php

namespace Core\Helpers\Pipes;

class IsIn
{
    private string $default_message = "The value is not in the list of options";
    public function handler(
        string $value,
        string $prop,
        array $options,
        string $message = ""
    ) {
        if (!in_array($value, $options)) {
            return [$prop => empty($message) ? $this->default_message : $message];
        }
    }
}
