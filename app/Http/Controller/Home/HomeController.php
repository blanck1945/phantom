<?php

namespace App\Http\Controller\Home;

class HomeController
{

    public function __construct(private HomeService $homeService) {}

    public function home()
    {
        return [
            'view' => 'index.blade.php',
            'hello' => 'Hello World',
            'framework' => 'Phantom'
        ];
    }
}
