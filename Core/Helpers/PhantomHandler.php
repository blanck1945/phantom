<?php

namespace Core\Helpers;

use Core\Dto\Dto;
use Core\Interfaces\IValidator;
use Core\Services\ValidatorService;

class PhantomHandler
{
    public function __construct(
        private readonly string $module,
        private readonly array $route_to_execute,
        private readonly array|string $handler,
        private readonly array $guards,
        private readonly array $pipes,
        private readonly array $middlewares,
        private readonly bool $csrf,
        private readonly ?string $validator,
        private readonly ?string $dto
    ) {}

    public function get_module(): string
    {
        return $this->module;
    }

    public function get_route_to_execute(): array
    {
        return $this->route_to_execute;
    }

    public function get_handler(): array|string
    {
        return $this->handler;
    }
    public function get_guards(): array
    {
        return $this->guards;
    }

    public function get_pipes(): array
    {
        return $this->pipes;
    }

    public function get_middlewares(): array
    {
        return $this->middlewares;
    }

    public function get_csrf(): bool
    {
        return $this->csrf;
    }

    public function get_validator(): ?ValidatorService
    {
        return $this->validator ? new ValidatorService($this->validator) : null;
    }

    public function get_dto(): ?string
    {
        return $this->dto ? $this->dto : null;
    }
}
