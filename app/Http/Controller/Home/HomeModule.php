<?php

namespace App\Http\Controller\Home;

use Core\Helpers\Traits\CoreModuleTrait;

class HomeModule
{
    use CoreModuleTrait;
    static public $controller = HomeController::class;

    static public function routes()
    {
        return  [
            '/' => [
                'GET' => 'home'
            ],
        ];
    }
}
