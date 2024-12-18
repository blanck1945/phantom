<?php

namespace Controller\ViewController;

use Controller\ViewController\ViewController;
use Core\Interfaces\ICoreModule;

class ViewModule implements ICoreModule
{
    static public $controller = ViewController::class;

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
