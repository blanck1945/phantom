<?php

namespace Controller\Menu;

class MenuService
{

    public function index()
    {
        return [
            'view' => 'menu.blade.php',
            'menus' => [
                [
                    'name' => 'Vegan',
                    'url' => '/menu/vegan'
                ],
                [
                    'name' => 'Asian',
                    'url' => '/menu/asian'
                ],
                [
                    'name' => 'Mexican',
                    'url' => '/menu/mexican'
                ]
            ]
        ];
    }
}
