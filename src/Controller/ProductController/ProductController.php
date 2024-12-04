<?php
        
namespace Controller\ProductController;

use Core\Controller\ICoreController;

class ProductController implements ICoreController

{
    static public function inject()
    {
        return [];
    }

    static public function routes() 
    {
        return [
            '/products' => [
                'GET' => [
                    'controller' => CsvController::class,
                    'handler' => 'get_products'
                ]
            ]
        ];
    }

    public function get_products() {
        return [
            "view" => "products.php",
        ];
    }
}
        