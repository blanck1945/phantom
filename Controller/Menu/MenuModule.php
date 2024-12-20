<?php

namespace Controller\Menu;

use Core\Interfaces\ICoreModule;

class MenuModule  implements ICoreModule
{
    static public $controller = MenuController::class;

    static public function config()
    {
        return [];
    }

    static public function inject()
    {
        return ['menuService' => MenuService::class];
    }

    static public function routes()
    {
        return [
            '/menu' => [
                'GET' => 'get_all_menus'
            ]
        ];
    }
}
