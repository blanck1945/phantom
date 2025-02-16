<?php

namespace Core\Helpers\Decorators\UseActions;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class UseGuard
{
    public function __construct(private array $guards, private string $message = "") {}

    public function getGuards(): array
    {
        return $this->guards;
    }
}
