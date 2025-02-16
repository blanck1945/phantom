<?php

namespace Core\Helpers\Decorators;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]

class Post
{
    public string $path;

    public function __construct(string $path = "")
    {
        var_dump("POST request");
        $this->path = $path;
    }
}
