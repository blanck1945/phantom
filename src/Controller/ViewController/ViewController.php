<?php

namespace Controller\ViewController;

class ViewController
{

    public function __construct() {}

    public function home()
    {
        return [
            'view' => 'home.blade.php',
            'hello' => 'Hello World',
            'framework' => 'Phantom'
        ];
    }
}
