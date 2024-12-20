<?php

namespace Controller\Menu;

class MenuController
{
    public function __construct(private MenuService $menuService) {}

    public function get_all_menus()
    {
        return $this->menuService->index();
    }
}
