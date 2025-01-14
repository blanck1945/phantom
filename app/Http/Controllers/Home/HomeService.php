<?php

namespace App\Http\Controllers\Home;

use Core\Services\SessionService\SessionService;

class HomeService
{

    public function __construct(
        private readonly SessionService $sessionService
    ) {}

    public function index(): array
    {
        return [
            'view' => 'index.blade.php',
            'hello' => 'Hello World',
            'framework' => 'Phantom'
        ];
    }
}
