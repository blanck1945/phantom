<?php

namespace Controller\Product;

use Core\Interfaces\ICoreModule;

class ProductModule  implements ICoreModule
{
    static public $controller = ProductController::class;

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
        return [
            '/products' => [
                'GET' => 'get_all_products'
            ]
        ];
    }
}
