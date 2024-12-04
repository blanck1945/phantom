<?php

namespace Core\Interfaces;

use Core\Database\Database;

/**
 * Interface ICoreModule
 * 
 * @package Core\Interfaces
 *  
 * @method static inject()
 * 
 * @method static routes()
 */
interface ICoreModule
{
    static public function inject();

    static public function routes();
}
