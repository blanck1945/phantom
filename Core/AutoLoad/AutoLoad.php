<?php

namespace Core\Autoload;

class AutoLoad
{
    static public function loadAutoload()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__  . '/../../public/');
        $dotenv->load();
    }
}
