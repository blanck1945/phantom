<?php

namespace App\Http\Controllers\Home;

use Core\Interfaces\ICoreController;
use Core\Request\PhantomRequest;

class HomeController implements ICoreController
{

    public function __construct(private HomeService $homeService) {}

    public function index(PhantomRequest $phantomRequest): array
    {
        return $this->homeService->index($phantomRequest->getUser());
    }
}
