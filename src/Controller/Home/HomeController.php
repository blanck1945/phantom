<?php

namespace Controller\Home;

class HomeController
{

    public function __construct() {}

    public function home()
    {
        return [
            'view' => 'index.blade.php',
            'hello' => 'Hello World',
            'framework' => 'Phantom'
        ];
    }
}
