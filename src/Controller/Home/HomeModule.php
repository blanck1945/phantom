<?php

namespace Controller\Home;

use Core\Interfaces\ICoreModule;

class HomeModule implements ICoreModule
{
    static public $controller = HomeController::class;

    static public function config()
    {
        return [];
    }

    static public function inject()
    {
        return [];
    }

    static public function routes()
    {
        return  [
            '/' => [
                'GET' => 'home'
            ],
        ];
    }
}
