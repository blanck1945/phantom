<?php

namespace Core\Composable;

class Composable
{
    private object $composeClass;

    public function __construct(string $mainClass, array $dependencies = [])
    {
        $this->composeClass = new $mainClass($dependencies);
    }

    public function getComposableClass()
    {
        return $this->composeClass;
    }
}
