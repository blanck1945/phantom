<?php

namespace Core\Helpers\Decorators;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Controller
{
    public string $basePath;

    public function __construct(string $basePath = "/")
    {
        $this->basePath = '/' . $basePath;
    }
}
