<?php

namespace Controller\Product;

class ProductController
{
    public function get_all_products()
    {
        return [
            'status' => 'success',
            'data' => [
                [
                    "id" => 1,
                    "title" => "Product 1",
                    "price" => 800,
                    "description" => "Description 1",
                ],
                [
                    "id" => 2,
                    "title" => "Product 2",
                    "price" => 1000,
                    "description" => "Description 2",
                ],
                [
                    "id" => 3,
                    "title" => "Product 3",
                    "price" => 2500,
                    "description" => "Description 3",
                ],
            ]
        ];
    }
}
