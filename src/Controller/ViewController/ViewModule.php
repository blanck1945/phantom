<?php

namespace Controller\ViewController;

use Controller\ViewController\ViewController;
use Core\Database\Database;
use Core\Interfaces\ICoreModule;

class ViewModule implements ICoreModule
{
    static public $controller = ViewController::class;

    static public function config()
    {
        return [
            'metadata' => [
                'global' => true,
                'js' => [
                    'draw',
                    'chart'
                ],
                'css' => [
                    'card'
                ],
            ]
        ];
    }

    static public function inject()
    {
        return [
            'databaseService' => Database::getInstance()
        ];
    }

    static public function routes()
    {
        return  [
            'routes' => [
                '/' => [
                    'GET' => 'home',
                    'POST' => 'home',
                ],
                '/about' => [
                    'GET' => 'about'
                ],
                '/:name' => [
                    'GET' => 'var_name'
                ],
                '/name/:name' => [
                    'GET' => 'var_name'
                ],
                '/ff' => [
                    'GET' => 'get_ff_data'
                ]
            ]
        ];
    }
}
