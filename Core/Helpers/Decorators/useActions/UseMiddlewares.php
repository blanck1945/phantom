<?php

namespace Core\Helpers\Decorators\UseActions;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class UseMiddlewares
{
    public function __construct(private array $middlewares) {}

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
