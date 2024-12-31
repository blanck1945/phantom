<?php

namespace App\Http\Controller\Home;

use Core\Interfaces\ICoreModule;
use Core\Helpers\Enums\RenderMethod;

class HomeModule implements ICoreModule
{
    public const CONTROLLER = HomeController::class;

    static public function routes()
    {
        return  [
            '/' => [
                'GET' => [
                    'controller' => 'index',
                    'render' => RenderMethod::STATIC
                ]
            ],
        ];
    }
}
