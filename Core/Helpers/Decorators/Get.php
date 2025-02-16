<?php

namespace Core\Helpers\Decorators;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Get
{
    public string $path;

    public function __construct(string $path = "")
    {
        $this->path = $path;
    }
}
