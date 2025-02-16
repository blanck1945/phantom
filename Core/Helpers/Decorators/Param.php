<?php

namespace Core\Helpers\Decorators;

use Attribute;

#[Attribute]
class Param
{
    public function __construct(public string $name) {}
}
