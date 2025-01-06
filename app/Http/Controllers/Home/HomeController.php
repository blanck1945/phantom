<?php

namespace App\Http\Controllers\Home;

use Core\Interfaces\ICoreController;

class HomeController implements ICoreController
{

    public function __construct(private HomeService $homeService) {}

    public function index()
    {
        return [
            'view' => 'index.blade.php',
            'hello' => 'Hello World',
            'framework' => 'Phantom'
        ];
    }
}
