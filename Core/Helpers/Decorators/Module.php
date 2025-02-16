<?php

namespace Core\Helpers\Decorators;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Module
{
    public string $controller;

    public function __construct($controller)
    {
        $this->controller = $controller;
    }
}
