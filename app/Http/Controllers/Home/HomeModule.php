<?php

namespace App\Http\Controllers\Home;

use Core\Helpers\Decorators\Module;
use Core\Interfaces\ICoreModule;
use Core\Helpers\Enums\RenderMethod;

#[Module(HomeController::class)]
class HomeModule implements ICoreModule
{
    // static public function routes()
    // {
    //     return  [
    //         '/' => [],
    //     ];
    // }
}
