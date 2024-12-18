<?php

namespace Core\Helpers;

class PhantomValidator
{
    public function __construct(private string $prop, private array $pipes) {}

    public function validate(array $queries)
    {
        $value = $queries[$this->prop] ?? false;

        if (!$value) {
            return;
        }

        foreach ($this->pipes as $pipe) {
            $exec = new $pipe();
            $exec->handler($value);
        }

        return $value;
    }
}
