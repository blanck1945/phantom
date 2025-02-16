<?php

namespace App\Http\Controllers\Home;

use Core\Helpers\Decorators\Controller;
use Core\Helpers\Decorators\Get;
use Core\Helpers\Decorators\Param;
use Core\Interfaces\ICoreController;
use Core\Request\PhantomRequest;

#[Controller('')]
class HomeController implements ICoreController
{
    public function __construct(private HomeService $homeService) {}

    #[Get()]
    public function index(): array
    {
        return $this->homeService->index();
    }

    #[Get('about')]
    public function about(): string
    {
        return "About page with id:";
    }

    #[Get('about/:id')]
    public function aboutWithId(#[Param('id')] string $id): string
    {
        return "About page with id: $id";
    }
}
