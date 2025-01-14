<?php

namespace Core\Helpers\Middlewares;

use Core\Services\CsrfService;

class CsrfMiddleware
{
    public function __construct(
        private readonly CsrfService $csrfService
    ) {}

    public function handler()
    {
        return;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->csrfService->verify();
        }
    }
}
